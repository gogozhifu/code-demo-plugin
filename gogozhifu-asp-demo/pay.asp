<!--#include file="md5.asp"-->
<%
    res = gogozhifu("1", "0.08")
    response.write(res)


    '调用GOGO支付下单接口, payType支付类型 1微信，2支付宝, price付款金额
    Function gogozhifu(payType, price)
        appid         = "这里填入您的GOGO支付APPID" 'GOGO支付商户APP_ID
        appsecret     = "这里填入您的GOGO支付APPSECRET" 'GOGO支付商户APP_SECRET
        apiUrl        = "https://www.gogozhifu.com/shop/api/createOrder" 'api接口地址
        payId         = ToUnixTime(Now, +8) '这里用当前时间戳当成单号
        param         = "gogo-asp-demo" '选填, 商户自定义的参数，回调通知的时候会原样返回

        '支付完成后通知开发者服务器的url。(不传会获取GOGO支付商户后台设置的默认回调地址) 这里要修改成商户自己接收支付成功回调通知的地址，该地址不能有访问权限
        notifyUrl     = "http://localhost/notify.asp"
        '支付完成后跳转页面url。(不传会获取GOGO支付商户后台设置的默认跳转地址)
        returnUrl     = "http://localhost/return.asp"

        'md5加密生成sign
        set md5 = new md5utf8
        sign = md5(appid&payId&param&payType&price&appsecret)

        dataStr = "payId="&payId&"&param="&param&"&type="&payType&"&price="&price&"&sign="&sign&"&notifyUrl="&notifyUrl&"&returnUrl="&returnUrl&"&isHtml=1"
        gogozhifu = goPost(apiUrl,dataStr,appid,appsecret)
    End Function

    'GOGO支付发起接口请求，header里需要有appid和appsecret
    Function goPost(url,data,appid,appsecret)
        Set XmlObj = Server.CreateObject("Microsoft.XMLHTTP")
        XmlObj.open "POST",url,false
        XmlObj.setrequestheader "App-Id",appid
        XmlObj.setrequestheader "App-Secret",appsecret
        XmlObj.setrequestheader "Connection","Keep-Alive"
        XmlObj.setrequestheader "Cache-Control","no-cache"
        XmlObj.setrequestheader "Content-Length",len(data)
        XmlObj.setrequestheader "Content-Type", "application/x-www-form-urlencoded"
        XmlObj.send(data)
        goPost = XmlObj.responseText
        Set XmlObj = nothing
    End Function

    '时间戳函数
    Function ToUnixTime(dateTime, TimeZone)
        ToUnixTime = DateAdd("h", -TimeZone, dateTime)
        ToUnixTime = DateDiff("s", "1970-1-1 0:0:0", ToUnixTime)
    End Function
%>