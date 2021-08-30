/* GOOG支付接入示例代码 - JAVA版本 */
/* 更多信息请前往官网：https://www.gogozhifu.com */

import com.alibaba.fastjson.JSONObject;
import org.springframework.util.StringUtils;

import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class GoGoZhiFu {

    private final static String APP_ID = "填入GOGO支付商户自己的AppId";
    private final static String APP_SECRET = "填入GOGO支付商户自己的AppSecret";

    // 支付时调用该方法，设置好传入参数PayParams
    public void pay(PayParams payParams, HttpServletResponse response) {
        if (null != payParams) {
            //计算并设置签名sign
            payParams.setSign(generateOrderSign(payParams, APP_ID, APP_SECRET));

            String paramsString = JSONObject.toJSONString(payParams);
            String apiUrl = "https://www.gogozhifu.com/shop/api/createOrder";
            //发起的goPost请求里需要设置请求头App-Id和App-Secret
            String result = HttpUtils.goPost(apiUrl, paramsString);

            // 如果使用了GOGO支付收银台页面，直接在跳转的页面完成支付即可
            if (payParams.getIsHtml == 1) {
                response.setContentType("text/html; charset=utf-8");
            } else {
                // 如果是使用自定义JSON模式，这里需要用户自己来对数据做处理，根据返回的支付数据自定义收款页面
            }

            PrintWriter out = null;
            try {
                out = response.getWriter();
                out.write(result);
                out.flush();
            } catch (IOException e) {
                e.printStackTrace();
            } finally {
                if (out != null) {
                    out.close();
                }
            }
        }
    }

    // 支付成功后，GOGO支付会通知开发者设置的notifyUrl
    /*
    建议的controller写法如下：
    @PostMapping("/notifyUrl")
    public String notifyUrl(@RequestBody NotifyParams notifyParams, HttpServletResponse response) {
        return notify(notifyParams, response);
    }
    */

    // 接收GOGO支付完成的回调通知方法，在该函数中主要用于商户自己根据支付完成处理相应的数据逻辑
    public String notify(NotifyParams notifyParams, HttpServletResponse response) {
        if (null != notifyParams) {
            String sign = generateNotifySign(notifyParams, APP_ID, APP_SECRET);
            if (sign.equals(notifyParams.getSign())) {
                // 在这里根据商户自己需求完成后续逻辑操作
                // 订单数据更新操作...

                // 成功完成后正确返回
                response.setStatus(HttpServletResponse.SC_OK);
                return "success";
            }
        }
        response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
        return "error"
    }

    //生成回调通知的签名sign
    private static String generateNotifySign(NotifyParams params, String appId, String appSecret) {
        if (!StringUtils.isEmpty(secret)) {
            StringBuilder sb = new StringBuilder();
            sb.append(appId);
            sb.append(params.getPayId());
            sb.append(params.getParam());
            sb.append(params.getType());
            sb.append(params.getPrice());
            sb.append(params.getReallyPrice());
            sb.append(appSecret);
            return md5(sb.toString());
        }
        return "";
    }

    //生成下单签名sign
    private static String generateOrderSign(PayParams params, String appId, String appSecret) {
        if (!StringUtils.isEmpty(appId) && !StringUtils.isEmpty(appSecret)) {
            StringBuilder sb = new StringBuilder();
            sb.append(appId);
            sb.append(params.getPayId());
            sb.append(params.getParam());
            sb.append(params.getType());
            sb.append(params.getPrice());
            sb.append(appSecret);
            return md5(sb.toString());
        }
        return "";
    }

    // md5加密
    private static String md5(String data) {
        String ret = "";
        try {
            MessageDigest md5 = MessageDigest.getInstance("MD5");
            byte[] bytes = md5.digest(data.getBytes());
            StringBuilder sb = new StringBuilder();
            for (byte b : bytes) {
                String temp = Integer.toHexString(b & 0xff);
                temp = temp.toLowerCase();
                if (temp.length() == 1) {
                    sb.append("0");
                }
                sb.append(temp);
            }

            ret = sb.toString();
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        }
        return ret;
    }
}
