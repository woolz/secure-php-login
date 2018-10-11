<?php

namespace SecurePHPLogin;


class sys_info {

    public $name;

    public $url;

    public $sys_base;

    public $favicon;

    public $sys_logo;



    public function __construct() {

        require './config/sys.php';

        $this->name = $sys_name;
        $this->url = $sys_url;
        $this->sys_base  = $sys_base;
        $this->favicon = $sys_favicon;
        $this->sys_logo  = $sys_logo;
}

    public function __destruct() {
        $this->name = null;
        $this->url = null;
        $this->sys_base = null;
        $this->favicon = null;
        $this->sys_logo = null;
    }

}
