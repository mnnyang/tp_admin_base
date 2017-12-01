<?php
/**
 * Created by PhpStorm.
 * User: mnnyang
 * Date: 17-11-22
 * Time: 下午4:41
 */

namespace app\admin\validate;


use think\Validate;

class AuthRule extends Validate {
    protected $rule = [
        ['pid', 'require', '上级权限不能为空'],
        ['title', 'require', '权限标题不能为空'],
        ['name', 'require', '权限【控/方】不能为空'],
    ];
}