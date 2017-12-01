<?php
/**
 * Created by PhpStorm.
 * User: mnnyang
 * Date: 17-11-21
 * Time: 下午8:33
 */

namespace app\admin\controller;


use think\Db;
use think\Validate;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\AuthRule as AuthRuleModel;


class AuthGroup extends Common {
    public function lst() {
        $list = Db::name('auth_group')->select();

        $list = ($list === null) ? array() : $list;
        $this->assign('list', $list);
        return $this->fetch('list');
    }

    public function add() {
        if (request()->isPost()) {
            $data = input('post.');
            $rule = [
                ['title', 'require', '用户组标题不能为空'],
            ];
            $validate = new Validate($rule);
            if ($validate->check($data)) {
                if (!isset($data['status'])) {
                    $data['status'] = 0;
                }
                if (isset($data['rules'])) {
                    $data['rules'] = implode(',', $data['rules']);
                }

                $authGroup = new AuthGroupModel();
                $result = $authGroup->save($data);
                if ($result) {
                    $this->success("添加成功！",
                        url('index/index') . "#" . url('auth_group/lst'));
                } else {
                    $this->error("添加失败！");
                }
            } else {
                $this->error($validate->getError());
            }
        }

        $authRuleModel = new AuthRuleModel();
        $list = $authRuleModel->ruleTree();

        $list = $list === null ? array() : $list;
        $this->assign('list', $list);
        return $this->fetch();
    }
}