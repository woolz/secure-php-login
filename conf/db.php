<?php
    //Paramêtros do banco de dados
    $host = "localhost"; // Servidor
    $username = "root"; // Nome do usuário MYSQL
    $password = "vertrigo"; // Senha do usuário MYSQL
    $db_name = "phplogin"; // Nome do Banco de Dados

    $tbl_prefix = "pl_"; //Prefixo da Tabela no Banco de Dados

    $tbl_users = $tbl_prefix."users";
    $tbl_tokens = $tbl_prefix."tokens";
    $tbl_cookies = $tbl_prefix."cookies";
    $tbl_attempts = $tbl_prefix."attempts";
