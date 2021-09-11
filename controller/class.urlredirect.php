<?php

/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 21/07/2018
 * Time: 15:15
 */
class Urlredirect extends ModelUrlredirect
{
    //Declaração do Método Construtor Padrão
    public function __construct(){
    }

    public function Url($url_destino)
    {
        return parent::Url($url_destino);
    }
    public function diretorio($caminho)
    {
        return parent::diretorio($caminho);
    }
}