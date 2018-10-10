<?php
    // **__ Database params __**
    $host = "localhost"; // Host
    $username = "root"; // Database User
    $password = "root"; // Database Password
    $db_name = "phplogin"; // Database Name

    $tbl_prefix = "pl_"; //Database prefix

    $tbl_users = $tbl_prefix."users";
    $tbl_tokens = $tbl_prefix."tokens";
    $tbl_cookies = $tbl_prefix."cookies";
    $tbl_attempts = $tbl_prefix."attempts";
