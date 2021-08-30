/* GOOG支付接入示例代码 - JAVA版本 */
/* 更多信息请前往官网：https://www.gogozhifu.com */

public class NotifyParams {
    private String payId;
    private String param;
    private String type;
    private String price;
    private String reallyPrice;
    private String sign;

    public NotifyParams() {
    }

    public NotifyParams(String payId, String param, String type, String price, String reallyPrice) {
        this.payId = payId;
        this.param = param;
        this.type = type;
        this.price = price;
        this.reallyPrice = reallyPrice;
    }

    public void setPayId(String payId) {
        this.payId = payId;
    }

    public String getPayId() {
        return payId;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getType() {
        return type;
    }

    public void setParam(String param) {
        this.param = param;
    }

    public String getParam() {
        return param;
    }

    public void setPrice(String price) {
        this.price = price;
    }

    public String getPrice() {
        return price;
    }

    public void setReallyPrice(String reallyPrice) {
        this.reallyPrice = reallyPrice;
    }

    public String getReallyPrice() {
        return reallyPrice;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public String getSign() {
        return sign;
    }
}
