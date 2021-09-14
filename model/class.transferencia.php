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
    protected function getListagem($idUsuario){
        $sql = "SELECT DISTINCT t.*,(SELECT u.nome FROM usuarios u WHERE u.id_usuario = t.id_usuario_envia) AS 'usuario_envia',
        (SELECT u.nome FROM usuarios u WHERE u.id_usuario = t.id_usuario_recebe) AS 'usuario_recebe' FROM `".$this->table."` t 
        WHERE t.`id_usuario_envia` = ".$idUsuario." OR t.`id_usuario_recebe` = ".$idUsuario." ORDER BY t.`data_hora` DESC";
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz*/
    protected function getCountTranferencia($idUsuario){
        $sql = "SELECT DISTINCT t.* FROM `".$this->table."` t WHERE t.`id_usuario_envia` = ".$idUsuario." ORDER BY t.`data_hora` DESC";
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