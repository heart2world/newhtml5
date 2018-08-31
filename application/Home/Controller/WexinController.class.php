<?php
/**
 * 微信授权登录
 */
namespace Home\Controller;
use Think\Controller;
class WexinController extends Controller {
   
	const APPID ='wxf6af0ebce8b994cc';
    const APPSECRET='7a4fabb41b86a898b49fe361ad01c89b';
    const TOKEN = "weixin";
    public function get_user()
    {
        $wechatObj = new \Think\WeChat(self::APPID,self::APPSECRET,self::TOKEN);
        $code=$_GET['code'];
        $openidarr=$wechatObj->get_snsapi_base($code);
        
        if($openidarr['scope']=='snsapi_base'){
            dump($openidarr['openid']);
        }
        $access_token=$openidarr['access_token'];
        $openid=$openidarr['openid'];
                
        if($openidarr['scope']=='snsapi_userinfo'){           
            $info=$wechatObj->get_snsapi_userinfo($access_token, $openid); 
        }   
        $userinfo = M('member')->where("openid = '" . $info['openid'] . "'")->find();		
        if($userinfo)
        {
            $_SESSION['users'] =$userinfo;
            redirect(U('Home/index/joinfenqi'));exit();
        }
        else
        {             
			
            $_SESSION['users']['openid'] =$info['openid']; 
			$_SESSION['users']['headimg'] =$info['headimgurl']; 
			$data['openid'] =$info['openid'];
			$data['nicename'] =$info['nickname'];
			$data['addtime']=time();
			$userid=M('member')->add($data);
			$_SESSION['users']['id'] =$userid; 
            redirect(U('Home/index/joinfenqi'));exit();
        }        
    }
}