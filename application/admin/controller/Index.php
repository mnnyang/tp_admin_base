<?php

namespace app\admin\controller;



class Index extends Common {
    public function index() {
        return $this->fetch();
    }

    public function home() {
        return $this->fetch();
    }

}
