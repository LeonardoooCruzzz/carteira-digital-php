<?php
include_once("lib/init.php");

$url_redirect = (isset($_GET['redirect']) && ($_GET['redirect'] != "")) ? $_GET['redirect'] : 'login';
$urlredirect = new Urlredirect();
$urltable = $urlredirect->Url($url_redirect);
$todasUrl = explode('/', $url_redirect);
$outrasUrl = $todasUrl[1];

if (count($urltable) > 0) {
    if ($urltable[0]->tipo_url == 'S') {
        session_start();
        if (isset($_SESSION['login']) || $todasUrl[0] == 'cadastro') {
            if ($urlredirect->diretorio($urltable[0]->caminho_url) == true) {
                include_once($urltable[0]->caminho_url);
            } else {
                include_once('view/erro/404.phtml');
            }
        } else {
            include_once('view/logar.phtml');
        }
    } else {
        include_once('view/erro/404.phtml');
    }
} else {
    if ($outrasUrl != '') {
        switch ($outrasUrl){
            case "":

                break;
        }
    } else {
        include_once('view/erro/404.phtml');
    }
}