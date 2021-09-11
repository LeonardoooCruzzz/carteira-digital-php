<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class ModelUrlredirect
{
    private $id_url_redirect;
    private $caminho_url;
    private $url_destino;
    private $tipo_url;
    private $status;
    //get
    public function getIdUrlRedirect(){return $this->id_url_redirect;}
    public function getCaminhoUrl(){return $this->caminho_url;}
    public function getUrlDestino(){return $this->url_destino;}
    public function getTipoUrl(){return $this->tipo_url;}
    public function getStatus(){return $this->status;}

    //set
    public function setIdUrlRedirect($id_url_redirect){$this->id_url_redirect = $id_url_redirect;}
    public function setCaminhoUrl($caminho_url){$this->caminho_url = $caminho_url;}
    public function setUrlDestino($url_destino){$this->url_destino = $url_destino;}
    public function setTipoUrl($tipo_url){$this->tipo_url = $tipo_url;}
    public function setStatus($status){$this->status = $status;}

    private $table = "url_redirect";
    private $id_table = "id_url_redirect";

    protected function Url($url_destino)
    {
        $sql = "SELECT * FROM " . $this->table ." WHERE url_destino = '" . $url_destino . "'";
        $execute = conexao::toConnect()->executeS($sql);
        return $execute;
    }
    /**
     * @author Leonardo Cruz - Desenvolvedor WEB
     * @param $tables string, nomes das tabelas usadas no sql
     * @return object
     */
    protected function diretorio($caminho){

        if(file_exists($caminho)){
            return true;
        }else{
            return false;
        }
    }
}