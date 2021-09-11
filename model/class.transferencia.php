<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class ModelTransferencia extends sets
{
    public $id_transferencia;
    public $data_hora;
    public $id_usuario_envia;
    public $id_usuario_recebe;
    public $valor;
    public $url_imagem;

    public $table = "transferencias";
    public $table_id = "id_transferencia";
    public $id_table = "id_transferencia";

    /*
     * author: Leonardo Cruz*/
    protected function getListagem(){
        $sql = "SELECT * FROM ".$this->table." ORDER BY ".$this->id_table." DESC";
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz*/
    protected function buscaRegistro($id){
        $sql = "SELECT * FROM ".$this->table." WHERE ".$this->id_table." = ".$id;
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }
}