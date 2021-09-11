<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class ModelConfig extends sets
{
    private $campo;
    private $info;
    //get
    public function getCampo(){return $this->campo;}
    public function getInfo(){return $this->info;}

    //set
    public function setCampo($campo){$this->campo = $campo;}
    public function setInfo($info){$this->info = $info;}

    private $table = "config";

    public function buscaTodos()
    {
        $sql = "SELECT * FROM " . $this->table;
        $execute = conexao::toConnect()->executeS($sql);
        return $execute;
    }
}