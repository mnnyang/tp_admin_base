<?php
/**
 * Created by PhpStorm.
 * User: mnnyang
 * Date: 17-11-22
 * Time: 下午3:48
 */

namespace app\admin\model;


use think\Model;

class AuthRule extends Model {
    public function ruleTree() {
        $ruleres = $this->order('sort', 'asc')->select();
        if ($ruleres === null) {
            return null;
        }
        return $this->sort($ruleres);
    }

    public function sort($data, $pid = 0) {
        static $arr = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
                $this->sort($data, $v['id']);
            }
        }
        return $arr;
    }

    public function del($id) {
        $result = self::destroy($this->getChildrenId($id));

        if ($result === false) {
            return false;
        }

        return $this->where('id', $id)->delete();
    }

    public function getChildrenId($ruleId) {
        $ruleres = $this->select();
        return $this->_getChildrenId($ruleres, $ruleId);
    }

    public function _getChildrenId($ruleres, $ruleId) {
        static $arr = array();
        foreach ($ruleres as $k => $v) {
            if ($v['pid'] == $ruleId) {
                $arr[] = $v['id'];
                $this->_getChildrenId($ruleres, $v['id']);
            }
        }


        return $arr;
    }
}