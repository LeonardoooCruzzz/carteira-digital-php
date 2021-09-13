<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class ModelUsuario extends sets
{
    public $id_usuario;
    public $data_hora;
    public $nome;
    public $email;
    public $foto_perfil;
    public $senha;
    public $admin;
    public $status;

    public $table = "usuarios";
    public $table_id = "id_usuario";
    public $id_table = "id_usuario";


    protected function cadastroDuplicadoPessoaFisica($email,$cpf){
        $sql = "SELECT u.`id_usuario` FROM ".$this->table." u
        INNER JOIN `".$this->table."_pessoa_fisica` uf ON uf.`id_usuario` = u.`id_usuario`           
        WHERE u.email = '". $email ."' 
        OR uf.cpf = '". $cpf ."'";

        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }

    protected function cadastroDuplicadoPessoaJuridica($email,$cnpj){
        $sql = "SELECT u.`id_usuario` FROM ".$this->table." u
        INNER JOIN ".$this->table."_pessoa_juridica uj ON uj.`id_usuario` = u.`id_usuario`           
        WHERE u.email = '". $email ."' 
        OR uj.`cnpj` = '". $cnpj ."'";

        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz*/
    protected function buscaRegistroPessoaFisica($id){
        $sql = "SELECT * FROM ".$this->table."_pessoa_fisica WHERE ".$this->id_table." = ".$id;
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz*/
    protected function buscaRegistroPessoaJuridica($id){
        $sql = "SELECT * FROM ".$this->table."_pessoa_juridica WHERE ".$this->id_table." = ".$id;
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz*/
    protected function getListagem(){
        $sql = "SELECT * FROM ".$this->table . ' ORDER BY ' . $this->table_id . ' DESC';
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

    /*
     * author: Leonardo Cruz*/
    protected function verificaLogin($email,$senha,$status){
        $sql = "SELECT * FROM ".$this->table." 
        WHERE email = '".$email."' 
        AND senha = '".$senha."' 
        AND status = '".$status."'";
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz
     * Insert pessoa fisíca
     * */
    protected function insertPessoaFisica($idUsuario,$cpf){
        $sql = "INSERT INTO ".$this->table."_pessoa_fisica 
        (id_usuario,cpf) 
        VALUES 
        (
            '". $idUsuario ."',
            '". $cpf ."'
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
     * Update pessoa fisíca
     * */
    protected function updatePessoaFisica($idUsuario,$cpf){
        $sql = "UPDATE ".$this->table."_pessoa_fisica 
        SET  cpf = '" . $cpf . "'
        WHERE id_usuario = " . $idUsuario;
        $execute_into = conexao::toConnect()->executeQuery($sql);
        if (count($execute_into) > 0) {
            return $execute_into;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz
     * Insert pessoa jurídica
     * */
    protected function insertPessoaJuridica($idUsuario,$cnpj,$razao_social){
        $sql = "INSERT INTO ".$this->table."_pessoa_juridica 
        (id_usuario,cnpj,razao_social) 
        VALUES 
        (
            '". $idUsuario ."',
            '". $cnpj ."',
            '". $razao_social ."'
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
   * Update pessoa jurídica
   * */
    protected function updatePessoaJuridica($idUsuario,$cnpj,$razao_social){
        $sql = "UPDATE ".$this->table."_pessoa_juridica 
        SET cnpj = '" . $cnpj . "', razao_social = '" .$razao_social. "'
        WHERE id_usuario = " . $idUsuario;
        $execute_into = conexao::toConnect()->executeQuery($sql);
        if (count($execute_into) > 0) {
            return $execute_into;
        }else{
            return false;
        }
    }

    /*
     * author: Leonardo Cruz
     * verifica se usuário existe para transferir o dinheiro na conta dele
     * */
    protected function verificaUsuarioTransferencia($email){
        $sql = "SELECT DISTINCT u.*,CASE WHEN u.tipo_pessoa = 'J' THEN uj.cnpj ELSE uf.cpf END AS 'identificacao', ud.valor
        FROM `".$this->table."` u
        LEFT JOIN `".$this->table."_pessoa_fisica` uf ON u.`id_usuario` = uf.`id_usuario`
        LEFT JOIN `".$this->table."_pessoa_juridica` uj ON u.`id_usuario` = uj.`id_usuario`
        INNER JOIN `usuarios_dinheiro` ud ON u.`id_usuario` = ud.`id_usuario`
        WHERE u.`email` = '". $email ."'";
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }
}