<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class Dinheiro extends ModelDinheiro
{

    //Declaração do Método Construtor Padrão
    public function __construct($return = false){
       $retorno =  parent::contruct($return);
       $this->PostSubmit($retorno);
    }

    public function PostSubmit($acao){}

    public function insertDinheiro($idUsuario, $valor)
    {
        return parent::insertDinheiro($idUsuario, $valor);
    }

    public function updateDinheiro($idUsuario, $valor)
    {
        return parent::updateDinheiro($idUsuario, $valor);
    }
}