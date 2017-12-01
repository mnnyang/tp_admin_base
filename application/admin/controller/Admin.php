<?php
/**
 * User: mnnyang
 * Mail: mnnyang.dev@gmail.com
 * Date: 17-11-29
 * Time: 下午10:33
 */

namespace app\admin\controller;


use app\admin\model\User;
use auth\Auth;
use think\Db;
use think\Validate;

class Admin extends Common {
    public function lst() {
        $user = new User();
        $list = $user->adminList();

        if ($list) {
            $auth = new Auth();
            foreach ($list as $k => &$v) {
                $_groupTitle = $auth->getGroups($v['user_id']);
                $groupTitle = $_groupTitle[0]['title'];
                $v['group_title'] = $groupTitle;
            }
        } else {
            $list = array();
        }

        $this->assign('list', $list);
        return $this->fetch('list');
    }

    public function add() {
        if (request()->isPost()) {
            $data = input('post.');
            $rule = [
                ['username', 'require|unique:user', '用户名不能为空|用户名重复'],
                ['password', 'require', '密码不能为空'],
                ['group_id', 'require', '所属用户组不能为空'],
            ];

            $validate = new Validate($rule);

            if ($validate->check($data)) {
                $user = new User();
                if ($user->saveAdmin($data)) {
                    $this->success("添加成功!",
                        url('index/index') . '#' . url('admin/lst'));
                } else {
                    $this->error('添加失败!');
                }
            } else {
                $this->error($validate->getError());
            }
        }

        $list = Db::name('auth_group')->select();

        $list = ($list === null) ? array() : $list;

        return json($list);
//        $this->assign('list', $list);
//
//        return $this->fetch();
    }
}