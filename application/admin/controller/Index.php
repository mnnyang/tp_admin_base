<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        if (request()->isAjax()){
            trace("ajax请求index");
            return view('index_ajax');
        }
            trace("请求index");
        return view('index');
    }
}
