/* GOOG支付接入示例代码 - JAVA版本 */
/* 更多信息请前往官网：https://www.gogozhifu.com */

public class PayParams {
    private String price; //必填
    private String type; //必填
    private String payId; //必填
    private String sign; //必填
    private String param; //选填
    private String notifyUrl; //选填
    private String returnUrl; //选填
    private String isHtml; //选填

    public void setPrice(String price) {
        this.price = price;
    }

    public String getPrice() {
        return price;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getType() {
        return type;
    }

    public void setPayId(String payId) {
        this.payId = payId;
    }

    public String getPayId() {
        return payId;
    }

    public void setParam(String param) {
        this.param = param;
    }

    public String getParam() {
        return param;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public String getSign() {
        return sign;
    }

    public void setReturnUrl(String returnUrl) {
        this.returnUrl = returnUrl;
    }

    public String getReturnUrl() {
        return returnUrl;
    }


    public void setNotifyUrl(String notifyUrl) {
        this.notifyUrl = notifyUrl;
    }

    public String getNotifyUrl() {
        return notifyUrl;
    }

    public void setReturnUrl(String returnUrl) {
        this.returnUrl = returnUrl;
    }

    public String getReturnUrl() {
        return returnUrl;
    }

    public void setIsHtml(String isHtml) {
        this.isHtml = isHtml;
    }

    public String getIsHtml() {
        return isHtml;
    }
}
