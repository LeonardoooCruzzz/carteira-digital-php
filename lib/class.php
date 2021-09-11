<?php
/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
function __autoload($classe) {

    $classe = strtolower($classe);

    if (file_exists(__DIR__."/{$classe}.php"))
        include_once __DIR__."/{$classe}.php";

    if (file_exists(__DIR__."/.././model/class.{$classe}.php"))
        include_once __DIR__."/.././model/class.{$classe}.php";

    if (file_exists(__DIR__."/.././controller/class.{$classe}.php"))
        include_once __DIR__."/.././controller/class.{$classe}.php";
}
