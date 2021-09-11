<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class Transferencia extends ModelTransferencia
{
    public  $pagina = "transferencias-form";
    public  $pagina_lista = "transferencias-lista";

    //Declaração do Método Construtor Padrão
    public function __construct($return = false){
        $retorno =  parent::contruct($return);
        $this->PostSubmit($retorno);
    }

    public function PostSubmit($acao){
        switch ($acao){
            case 'incluir':
                if ($this->incluirRegistros()){
                    header("Location: " . URL_SITE . '/'.$this->pagina.'?id='.$_GET['id'].'&msg=ok');
                }else{
                    header("Location: " . URL_SITE . '/'.$this->pagina.'?id='.$_GET['id'].'msg=erro');
                }
                break;
        }
    }

    public function getListagem()
    {
        return parent::getListagem();
    }

    public  function buscaRegistro($id)
    {
        return parent::buscaRegistro($id);
    }

    /*
     * author: Leonardo Cruz
     * Inserir registros
     * */
    public function incluirRegistros(){
        date_default_timezone_set('America/Sao_Paulo');
        $this->setData_hora(date('Y-m-d H:i:s'));
        $this->setId_usuario_envia($this->get_registro('id_usuario_envia'));
        $this->setId_usuario_recebe($this->get_registro('id_usuario_recebe'));
        $this->setValor($this->get_registro('valor'));
        $this->setStatus('A');

        $retorno = parent::incluir();

        return $retorno;
    }
}