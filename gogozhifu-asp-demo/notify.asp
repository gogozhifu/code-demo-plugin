<!--#include file="md5.asp"-->
<%
'支付成功异步回调接口
appid         = "这里填入您的GOGO支付APPID" 'GOGO支付商户APP_ID
appsecret     = "这里填入您的GOGO支付APPSECRET" 'GOGO支付商户APP_SECRET
'接收参数
payId=Request.QueryString("payId")
param=Request.QueryString("param")
payType=Request.QueryString("type")
price=Request.QueryString("price")
reallyPrice=Request.QueryString("reallyPrice")
sign=Request.QueryString("sign")

'md5加密生成sign
set md5 = new md5utf8
mySign = md5(appid&payId&param&payType&price&reallyPrice&appsecret)
'与请求里的sign进行对比校验
if sign=mySign then
    '签名校验成功，处理商户业务逻辑
    'TODO:在这里写支付成功后的逻辑代码
    '该接口需要支持多次访问调用，根据自身业务设置好状态判断，避免订单被二次更新而出错
else
    response.Write("error_sign")
end if
response.Write("success")
%>