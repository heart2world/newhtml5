<?php
/**
 *  接口
 */
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	
	const APPID ='wxf6af0ebce8b994cc';
    const APPSECRET='7a4fabb41b86a898b49fe361ad01c89b';
    public function index(){
		if(!$_SESSION['users']['openid'])
		{
			$str=urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php?g=Home&m=Wexin&a=get_user');
			$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.self::APPID.'&redirect_uri='.$str.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
			header("location:$url");
		}else
		{
			redirect(U('home/index/joinfenqi'));
		}
	}
	public function joinfenqi(){
		//if(!$_SESSION['users']['openid'])
		//{
		//	redirect(U('home/index/index'));
		//}
		$randomNum =time()*1000;
		$data ='outUserId=1&platform=heyi&randomNum='.$randomNum.rand(1000,9999).'&type=0001';
		//$data =json_encode($data);
		var_dump($this->rsaSign($data,'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIkF7VZPVNfrSiMHOFQQMz+Ajp90vLU3wZNARGBqzcdFSYYiMMuP6+GLeFA2KxDjYzDlRQXlU12Bl/dEdnfxCo+zhxTtZNVkv/W/6U3R1dVwJDkDp887Q78fofaFmRE6E5dCV+iVVRvEzm4W+4ft+HL3fPG3wqT9qkJqJRNqfuQfAgMBAAECgYBRdFuNdmV6Yd3ViuI6XtMISfT+55eSps2FKqw7IOKpNhAqE8MsD6dqkc146WqahIIfu/tXMOdo67QaAvHmBT2AIkuCRq3LnQD2shfb4axtzyF1J2Qzj5mzLxrTdUNEeFUP4i51MyjQ1ld85NSDTXv7smi5F5alhlstZwqMdUlNgQJBAOTxuI0qjUdvXyvcRWoL1bZX2t1Dh/Jo2BB6nw5OgMfJ8It9kToRC/kiO3QVNyVNdB9DjPRSKlmR457DoS2gXsECQQCZN05kPEpeo9abGsKE7EHLKerZdLuKS0gKwfKAd1jxmPfuLAePiBW3DPleLWgNRHXSxik8Dv2lr/VhgJU+HNrfAkEAik1TdUOtUOgAkBhifmtj0OFFv8BZ0aBwVZQdnaDivs5I15slLfS6TOfXDor6Yzhk27YM4lL4bl9pJ7F6HnvwgQJAOKTWyX30rLp7q8of4g6KYHb1yUE72GvujXOYmOAGtQMtnhMPFIRmKs+UHbpBvq3xtWPneLm+EpRT7qEgC9+VFwJAEvlKD2m/Qu+tCctCiFQkYqKwjWRtTshRADLKmQIDWy4FvoKK+s7U3KvoT1Nyz0DumIbhYO5vAp3gsFbPrOvDbA=='));
		
		//echo $this->verify($data,$this->sign($data));
	}
	
	
	/**
	 * RSA签名
	 * @param $data 待签名数据
	 * @param $private_key 商户私钥字符串
	 * return 签名结果
	 */
	function rsaSign($data, $private_key) {
		//以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
		$private_key=str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
		$private_key=str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
		$private_key=str_replace("\n","",$private_key);

		$private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";

		$res=openssl_pkey_get_private($private_key);

		if($res)
		{
			openssl_sign($data, $sign,$res);
		}
		else {
			return false;
		}
		openssl_free_key($res);
		//base64编码
		$sign = base64_encode($sign);
		return $sign;
	}

	/**
	 * RSA验签
	 * @param $data 待签名数据
	 * @param $alipay_public_key 支付宝的公钥字符串
	 * @param $sign 要校对的的签名结果
	 * return 验证结果
	 */
	function rsaVerify($data, $alipay_public_key, $sign)  {
		//以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
		$alipay_public_key=str_replace("-----BEGIN PUBLIC KEY-----","",$alipay_public_key);
		$alipay_public_key=str_replace("-----END PUBLIC KEY-----","",$alipay_public_key);
		$alipay_public_key=str_replace("\n","",$alipay_public_key);

		$alipay_public_key='-----BEGIN PUBLIC KEY-----'.PHP_EOL.wordwrap($alipay_public_key, 64, "\n", true) .PHP_EOL.'-----END PUBLIC KEY-----';
		$res=openssl_get_publickey($alipay_public_key);
		if($res)
		{
			$result = (bool)openssl_verify($data, base64_decode($sign), $res);
		}
		else {
		   return false;
		}
		openssl_free_key($res);    
		return $result;
	}
}
	