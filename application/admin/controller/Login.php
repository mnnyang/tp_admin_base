<?php
/**
 * User: mnnyang
 * Mail: mnnyang.dev@gmail.com
 * Date: 17-11-29
 * Time: 下午3:24
 */

namespace app\admin\controller;

use think\Controller;
use think\Validate;
use app\admin\model\User as UserModel;

class Login extends Controller {
    public function index() {
        if (request()->isPost()) {
            $data = input('post.');

            $rule = [['username', 'require', '请输入用户名'],
                ['password', 'require', '请输入密码'],
            ];
            $validate = new Validate($rule);
            if ($validate->check($data)) {
                $user = new UserModel;
                $result = $user->login($data);
                if ($result === true) {
                    $this->success("登录成功！", url('index/index'));
                } elseif ($result === 0) {
                    $this->error("没有找到该用户！");
                } else {
                    $this->error("用户名或密码错误！");
                }
            } else {
                $this->error($validate->getError());
            }
        }

        return $this->fetch();
    }

    public function logout() {
        $user = new UserModel();
        $user->logout();
        $this->success("注销成功！", url('index'));
    }
}