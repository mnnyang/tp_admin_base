<?php
/**
 * Created by PhpStorm.
 * User: mnnyang
 * Date: 17-11-21
 * Time: 下午8:56
 */

namespace app\admin\model;


use think\Model;

class AuthGroup extends Model {
    public function add($data) {
        return $this->save($data);
    }
}