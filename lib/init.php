<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */

ini_set('display_errors',0);

include_once ("class.php");

$config = new Config();
$define = $config->buscaTodos();

foreach ($define as $df){
    if ($df->campo == 'URL_SITE'){
        define("URL_SITE",$df->info);
    }
}

define("BASE_SITE_URL_VIEW", URL_SITE . '/view');
define("BASE_SITE_CSS", URL_SITE . '/view/css');
define("BASE_SITE_JS", URL_SITE . '/view/js');
define("BASE_SITE_IMG", URL_SITE . '/view/images');
define("BASE_SITE_UPLOADS", URL_SITE . '/uploads');

define("SITE_CSS", URL_SITE . '/view/css');
define("SITE_JS", URL_SITE . '/view/js');
define("SITE_IMG", URL_SITE . '/view/img');

?>