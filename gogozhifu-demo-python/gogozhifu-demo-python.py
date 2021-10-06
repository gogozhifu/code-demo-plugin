#
# GOGO支付接入代码DEMO - python版本
# 感谢ys提供该demo，QQ:279124388
#
# GOGO支付 - 更好的个人支付解决方案
# 官网： https://www.gogozhifu.com
# QQ：   653107385
# 微信： gump994
#

import requests
import hashlib
import time



class GoApiClient(object):
    def __init__(self, AppId, AppSecret , type ):
        self.AppId = AppId
        self.AppSecret = AppSecret
        self.tpye = type
        self.headers = {"App-Id": self.AppId, "App-Secret": self.AppSecret}

    def pay(self, method,param_json, **kwargs):
        params = param_json
        if self.tpye =='Create':
           params['sign'] =  hashlib.md5((self.AppId + params['payId'] + params['param'] + params['type'] + params['price'] + self.AppSecret).encode('utf-8')).hexdigest()   #创建订单时生成校验签名
        resp = requests.post(method, params=params, **kwargs, headers=self.headers)
        print(resp.url)
        return (resp.text)






AppId = "你的appid"
AppSecret = "你的AppSecret"

client = GoApiClient(AppId, AppSecret,'Create')
resp = client.pay("https://www.gogozhifu.com/shop/api/createOrder",           #创建订单
                                {  'payId':  time.strftime("%Y%m%d%H%M%S", time.localtime()) , #【必传】商户订单号，可以是时间戳，不可重复
                                   'type': '1' ,                              #【必传】微信支付传入1 支付宝支付传入2
                                   'price': '50.00',                          #【必传】订单金额，保留两位小数的字符串，例如“1.00”
                                   'param':'',                                #【可选】传输参数，将会原样返回到异步和同步通知接口
                                   'notifyUrl':'http://localhost/notify.php', #【可选】传入则设置该订单的异步通知接口为该参数，不传或传空则使用后台设置的接口
                                   'returnUrl':'http://localhost/return.php', #【可选】传入则设置该订单的同步跳转接口为该参数，不传或传空则使用后台设置的接口
                                   'title':'',                                #【可选】订单主题、类型，之后可根据该值统计分析数据，最多10个字符
                                   'isHtml':'1',                              #【可选】传入1则自动跳转到支付页面，否则返回创建结果的json数据
                                   'content':'',                              #【可选】描述订单具体内容、备注等说明文字
                                   'returnParam':''                           #【可选】默认是0，传1即可让官方支付页跳转returnUrl带上回调通知的参数
                                }
                                           )

print(resp)



#
#
# client = GoApiClient(AppId, AppSecret ,'Inquire' )
# resp = client.pay("https://www.gogozhifu.com/getOrder",               #查询订单信息
#                                   {'orderId': '202110061010199251'   #【必传】云端订单号，创建订单返回的
#                                    })
# print(resp)
#
#
#
# client = GoApiClient(AppId, AppSecret ,'Inquire' )
# resp = client.pay("https://www.gogozhifu.com/checkOrder",               #查询订单状态
#                                   {'orderId': '202110032010346128'   #【必传】云端订单号，创建订单返回的
#                                    })
# print(resp)
#
#
# client = GoApiClient(AppId, AppSecret ,'Inquire' )
# resp = client.pay("https://www.gogozhifu.com/closeOrder",                  #关闭订单
#                                   {'orderId': '202110051310219985'
#                                    'payId':''
#                                    })
# print(resp)
#
# client = GoApiClient(AppId, AppSecret ,'Inquire' )
# resp = client.pay("https://www.gogozhifu.com/deleteOrder",                #删除订单
#                                   {'orderId': '202110061010199251',
#                                    'payId':'20211006101819'
#                                    })
# print(resp)

