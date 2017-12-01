<?php
/**
 * Created by PhpStorm.
 * User: mnnyang
 * Date: 17-11-21
 * Time: 下午9:50
 */

namespace app\admin\controller;


use app\admin\model\AuthRule as AuthRuleModel;
use think\Controller;
use think\Db;

class AuthRule extends Common {
    public function lst() {
        $authRuleModel = new AuthRuleModel();
        $list = $authRuleModel->ruleTree();

        $list = $list === null ? array() : $list;
        $this->assign('list', $list);
        return $this->fetch('list');
    }

    public function add() {
        if (request()->isPost()) {
            $data = input('post.');

            $validate = new \app\admin\validate\AuthRule();
            if ($validate->check($data)) {
                //init level
                $level = Db::name('auth_rule')->where('id', $data['pid'])->value('level');
                $data['level'] = ($level === null) ? 0 : $level + 1;

                //insert
                $result = Db::name('auth_rule')->insert($data);

                if ($result) {
                    $this->success("权限添加成功！",
                        url('index/index') . "#" . url('auth_rule/lst'));
                } else {
                    $this->error("权限添加失败！",
                        url('index/index') . "#" . url('auth_rule/lst'));
                }
            } else {
                $this->error($validate->getError(),
                    url('index/index') . "#" . url('auth_rule/lst'));
            }
        }

        $authRuleModel = new AuthRuleModel();
        $authList = $authRuleModel->ruleTree();

        $this->assign('list', $authList);
        return $this->fetch();
    }

    public function del($id) {
        if (!$id || !is_numeric($id)) {
            $this->error("删除失败！");
        }
        $result = (new AuthRuleModel())->del($id);
        if ($result === false) {
            $this->error("删除失败！");
        } else {
            $this->success("删除成功！");
        }
    }

    public function edit() {
        if (request()->isPost()) {
            $data = input('post.');
            $validate = new \app\admin\validate\AuthRule();
            $validate->rule([['id', 'require', '权限修改失败，没有找到ID']]);
            if ($validate->check($data)) {
                //init level
                $level = Db::name('auth_rule')->where('id', $data['pid'])->value('level');
                $data['level'] = ($level === null) ? 0 : $level + 1;

                //insert
                $result = Db::name('auth_rule')->update($data);

                if ($result !== false) {
                    $this->success("权限修改成功！",
                        url('index/index') . "#" . url('auth_rule/lst'));
                } else {
                    $this->error("权限修改失败！",
                        url('index/index') . "#" . url('auth_rule/lst'));
                }
            } else {
                $this->error($validate->getError(),
                    url('index/index') . "#" . url('auth_rule/lst'));
            }
        }

        $id = input('id');
        var_dump($id);

        if (!$id || !is_numeric($id)) {
            $this->error("编辑失败！");
        }

        $authRule = \db('auth_rule')->where('id', $id)->find();
        if ($authRule == null) {
            $this->error("编辑失败！没有找到该条数据");
        }

        $authRuleModel = new AuthRuleModel();
        $authList = $authRuleModel->ruleTree();

        $this->assign('list', $authList);
        $this->assign('editItem', $authRule);
        return $this->fetch();
    }
}