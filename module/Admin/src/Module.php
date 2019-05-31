<?php
namespace Admin;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module
{
    public function getConfig()
    {
        return include __DIR__.'/../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap($e)
    {
        $this->rendercommon($e);
    }

    public function rendercommon($e)
    {

        $user_session = new Container('user');
        //未登录状态时，判断当前的action 是否可以访问
        if($user_session->userId == null || $user_session->userId == ""){
            $needAccess = true;
            //如果是首页登录页无需检查
            if('/' == $_SERVER['REQUEST_URI']){
                $needAccess = false;
            }else{
                $needAccess = false;
            }

            if($needAccess) {
                //如果是get请求返回到登录界面，post请求返回json
                if($_SERVER['REQUEST_METHOD'] == 'GET'){
                    header("X-Frame-Options: deny");
                    header("X-XSS-Protection: 0");
                    header("Location: http://" . $_SERVER['HTTP_HOST']);
                    exit();
                }else{
                    return json_encode(array('result'=>false,'msg'=>"登陆超时,请重新登陆",'reason'=>'timeout'));
                }
            }
        }else{
            /*再次检查当前的用户的ID是否是正确的数字*/
            $RegExp = '/^[\d]{1,11}$/';
            if(!(preg_match($RegExp, $user_session->userId) && $user_session->userId > 0)){
                header("X-Frame-Options: deny");
                header("X-XSS-Protection: 0");
                header("Location: http://" . $_SERVER['HTTP_HOST'].'/user/logout');
                exit;
            }
        }
    }

}
