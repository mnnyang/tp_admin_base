<?php
/**
 * Created by PhpStorm.
 * User: mnnyang
 * Date: 17-11-21
 * Time: 下午8:13
 */

namespace app\admin\controller;


use auth\Auth;
use think\Controller;
use think\Request;

class Common extends Controller {

    private static $NOT_CHECK_ARR = array('index/index', 'admin/lst');

    protected function _initialize() {
        if (!session('uid') || !session('username')) {
            $this->error("请登录！", url('login/index'));
        }

        $auth = new Auth();
        $request = Request::instance();
        $controller = $request->controller();
        $action = $request->action();
        $name = $controller . '/' . $action;
        $name = uncamelize($name);


        if (!in_array($name, self::$NOT_CHECK_ARR)
            && !$auth->check($name, session('uid'))
        ) {
            $this->error("你没有权限!", url('index/index'));
        }
    }
}