<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class ModelDinheiro extends sets
{
    public $id_usuario_dinheiro;
    public $id_usuario;
    public $valor;

    public $table = "usuarios_dinheiro";
    public $table_id = "id_usuario_dinheiro";
    public $id_table = "id_usuario_dinheiro";


    /*
     * author: Leonardo Cruz
     * Insert dinheiro
     * */
    protected function insertDinheiro($idUsuario,$valor){
        $sql = "INSERT INTO ".$this->table." 
        (id_usuario,valor) 
        VALUES 
        (
            '". $idUsuario ."',
            '". $valor ."'
        )
        ";
        $execute_into = conexao::toConnect()->executeQuery($sql);
        if (count($execute_into) > 0) {
            return $execute_into;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz
     * Update dinheiro
     * */
    protected function updateDinheiro($idUsuario,$valor){
        $sql = "UPDATE ".$this->table." 
        SET  valor = '" . $valor . "'
        WHERE id_usuario = " . $idUsuario;
        $execute_into = conexao::toConnect()->executeQuery($sql);
        if (count($execute_into) > 0) {
            return $execute_into;
        }else{
            return false;
        }
    }
}