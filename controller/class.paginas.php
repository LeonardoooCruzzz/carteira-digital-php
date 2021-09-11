<?php

/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 21/07/2018
 * Time: 15:15
 */
class Paginas extends ModelPaginas
{

    //Declaração do Método Construtor Padrão
    public function __construct($return = false){
       $retorno =  parent::contruct($return);
       $this->PostSubmit($retorno);
    }
    public function PostSubmit($acao){
        switch ($acao){

        }
    }
    public function get_lista($menu)
    {
        return parent::get_lista($menu);
    }
}