<?php

namespace app\admin\model;

use think\Model;
use think\Session;

/**
 * Created by PhpStorm.
 * User: mnnyang
 * Date: 17-11-21
 * Time: 下午7:54
 */
class User extends Model {

    /**
     * 显示管理员列表
     */
    public function adminList() {
        $list = db('user u')
            ->join('tp_auth_group_access a', 'u.user_id=a.uid')
            ->field('user_id,username')
            ->select();

        return $list;
    }

    /**
     * 添加管理员
     */
    public function saveAdmin($data) {
        $userData = array();
        $userData['username'] = $data['username'];
        $userData['password'] = $this->encrypt($data['password']);

        //TODO 应该使用事物
        $result = $this->data($userData)->save();
        if (!$result) {
            return false;
        }

        $groupData['group_id'] = $data['group_id'];
        $groupData['uid'] = $this->user_id;
        $result = db('auth_group_access')->insert($groupData);

        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * 登录
     * @param $data
     * @return bool|int 0 not find
     */
    public function login($data) {
        $user = $this->where('username', $data['username'])->find();

        if (!$user) {
            return 0;
        }
        if ($user['password'] == $this->encrypt($data['password'])) {
            session('uid', $user['user_id']);
            session('username', $user['username']);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 注销
     */
    public function logout() {
        Session::clear();
    }

    /**
     * 加密密码
     * @param $pwd
     * @return string
     */
    public function encrypt($pwd) {
        return md5($pwd);
    }
}