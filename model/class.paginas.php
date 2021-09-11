<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class ModelPaginas extends sets
{
    public $id_paginas;
    public $url_destino;
    public $nome;
    public $status;
    public $menu;

    public $table = "paginas";
    public $id_table = "id_paginas";


    /*
     * author: Leonardo Cruz*/
    protected function get_lista($menu){
        $sql = "SELECT * FROM ".$this->table." WHERE menu = '".$menu."' AND status = 'A'";
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }
}