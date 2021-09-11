<?php

/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 21/07/2018
 * Time: 15:15
 */
class Config extends ModelConfig
{
    public function __construct($return = false){
        $retorno =  parent::contruct($return);
        $this->PostSubmit($retorno);
    }
    public function PostSubmit($acao){
        switch ($acao){}
    }
    public  function buscaTodos()
    {
        return parent::buscaTodos();
    }
}