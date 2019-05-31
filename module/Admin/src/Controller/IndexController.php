<?php

namespace Admin\Controller;

use Model\Base\BaseController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Model\Admin\Base;

class IndexController extends BaseController
{
    /**
     * 主界面
     */
    public function indexAction()
    {
        try {

            $user_session = new Container('user');
            if ($user_session->userId == null || $user_session->userId == "") {
                $layout = $this->layout();
                $layout->setTemplate("view/main");
                //模拟路由
                $navData = [
                    [
                        "id" => "1",
                        "name" => "页面A",
                        "parid" => "",
                        "icon" => "fa fa-laptop",
                        "url" => "/admin/index/viewa",
                        "attrid" => "viewa",
                        "seq" => "1"
                    ],
                    [
                        "id" => "2",
                        "name" => "页面B",
                        "parid" => "",
                        "icon" => "fa fa-laptop",
                        "url" => "",
                        "childnode" => [
                            [
                                "id" => "3",
                                "name" => "页面C",
                                "parid" => "2",
                                "icon" => "fa-laptop",
                                "url" => "/admin/index/viewc",
                                "seq" => "1",
                                "attrid" => "viewc",
                            ]
                        ],
                        "attrid" => "viewb",
                        "seq" => "2"
                    ],
                ];
                return new ViewModel(
                    array(
                        'nav' => $navData
                    )
                );
            } else {
                header("X-Frame-Options: deny");
                header("X-XSS-Protection: 0");
                header("Location: http://" . $_SERVER['HTTP_HOST']);
                exit;
            }
        } catch (\Exception $e) {
            $model = new ViewModel();
            $model->setTemplate('error/500');
            return $model;
        }
    }

    public function homeAction()
    {
        try {

            $user_session = new Container('user');
            if ($user_session->userId == null || $user_session->userId == "") {
                return new ViewModel();
            } else {
                header("X-Frame-Options: deny");
                header("X-XSS-Protection: 0");
                header("Location: http://" . $_SERVER['HTTP_HOST']);
                exit;
            }
        } catch (\Exception $e) {
            $model = new ViewModel();
            $model->setTemplate('error/500');
            return $model;
        }
    }

    public function viewaAction()
    {
        try {

            $user_session = new Container('user');
            if ($user_session->userId == null || $user_session->userId == "") {
                return new ViewModel();
            } else {
                header("X-Frame-Options: deny");
                header("X-XSS-Protection: 0");
                header("Location: http://" . $_SERVER['HTTP_HOST']);
                exit;
            }
        } catch (\Exception $e) {
            $model = new ViewModel();
            $model->setTemplate('error/500');
            return $model;
        }
    }

    public function viewcAction()
    {
        try {

            $user_session = new Container('user');
            if ($user_session->userId == null || $user_session->userId == "") {
                return new ViewModel();
            } else {
                header("X-Frame-Options: deny");
                header("X-XSS-Protection: 0");
                header("Location: http://" . $_SERVER['HTTP_HOST']);
                exit;
            }
        } catch (\Exception $e) {
            $model = new ViewModel();
            $model->setTemplate('error/500');
            return $model;
        }
    }

    public function getdataAction()
    {
        try {

            $request = $this->getRequest();
            if ($request->isPost()) {
                $BaseModel = new Base($this->getDbAdapter());
                $sqlRet = $BaseModel->testData();
                if (!$sqlRet['result']) {
                    return new JsonModel(array('result' => false, 'msg' => '获取数据失败',"error"=>$sqlRet['msg']));
                }
                return new JsonModel(array('result' => true, 'msg' => '获取到后台数据', 'data' => $sqlRet['msg']));
            } else {
                $this->getResponse()->setStatusCode(404);
                return;
            }
        } catch (\Exception $e) {
            $model = new ViewModel();
            $model->setTemplate('error/500');
            return $model;
        }
    }
}
