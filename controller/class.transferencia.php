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
                $retorno = $this->incluirRegistros();
                exit(json_encode($retorno));
            case 'verificaSaldo':

                $formatarMoeda = str_replace(',', '.', str_replace('.', '', $this->get_registro('valor')));
                $dinheiro = new Dinheiro();
                $saldo = $dinheiro->saldoAtualizado($this->get_registro('id_usuario'));
                if($formatarMoeda <= $saldo[0]->valor) {
                    $retorno = array('retorno' => true);
                    exit(json_encode($retorno));
                } else {
                    $retorno = array('retorno' => false);
                    exit(json_encode($retorno));
                }
        }
    }

    public function getListagem($idUsuario)
    {
        return parent::getListagem($idUsuario);
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
        $dinheiro = new Dinheiro();
        $usuario = new Usuario();

        $formatarMoeda = str_replace(',', '.', str_replace('.', '', $this->get_registro('valor')));
        $saldo = $dinheiro->saldoAtualizado($this->get_registro('id_usuario'));

        //verifica se o usuário contém saldo para envio

        if($formatarMoeda <= $saldo[0]->valor) {

            $usuarioRecebe = $usuario->verificaUsuarioTransferencia($this->get_registro('email'));

            if($usuarioRecebe) {

                if($usuarioRecebe[0]->identificacao == $this->get_registro('cpf_cnpj')) {
                    //consultar serviço para autorizar transferência
                    $retornoApiAutorizador = $this->api('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');

                    if (isset($retornoApiAutorizador->message) && $retornoApiAutorizador->message == 'Autorizado') {

                        //inclui tranferencia
                        date_default_timezone_set('America/Sao_Paulo');
                        $this->setData_hora(date('Y-m-d H:i:s'));
                        $this->setId_usuario_envia($this->get_registro('id_usuario'));
                        $this->setId_usuario_recebe($usuarioRecebe[0]->id_usuario);
                        $this->setValor($formatarMoeda);
                        $this->setStatus('C');

                        $idInserido = parent::incluir();

                        if ($idInserido) {
                            $atualizaValorEnvia = $saldo[0]->valor - $formatarMoeda;
                            //atualiza saldo de quem enviou
                            $atualizaValorEnviaRetorno = $dinheiro->updateDinheiro($this->get_registro('id_usuario'), $atualizaValorEnvia);
                            if ($atualizaValorEnviaRetorno) {
                                $atualizaValorRecebe = $usuarioRecebe[0]->valor + $formatarMoeda;
                                //atualiza saldo de quem recebe
                                $atualizaValorEnviaRecebe = $dinheiro->updateDinheiro($usuarioRecebe[0]->id_usuario, $atualizaValorRecebe);
                                if ($atualizaValorEnviaRecebe) {
                                    //envia e-mail para o usuário
                                    $retornoApiEnvioEmail = $this->api('http://o4d9z.mocklab.io/notify');
                                    if($retornoApiEnvioEmail->message == 'Success') {
                                        $retorno = array(
                                            'saldo' => true,
                                            'retorno' => true,
                                            'emailEnvio' => true,
                                            'saldoAtualizado' => str_replace('.', ',', str_replace(',', '', $atualizaValorEnvia)),
                                        );
                                        return $retorno;
                                    } else {
                                        $retorno = array(
                                            'saldo' => true,
                                            'retorno' => true,
                                            'saldoAtualizado' => str_replace('.', ',', str_replace(',', '', $atualizaValorEnvia)),
                                        );
                                        return $retorno;
                                    }
                                } else {
                                    //volta valor antes de ter atualizado de quem enviou
                                    $dinheiro->updateDinheiro($this->get_registro('id_usuario'), $saldo[0]->valor);
                                    parent::deletar($idInserido, 'transferencias');
                                    $retorno = array(
                                        'saldo' => true,
                                        'retorno' => false,
                                    );
                                    return $retorno;
                                }
                            } //caso algo de errado apaga transferencia;
                            else {
                                parent::deletar($idInserido, 'transferencias');
                                $retorno = array(
                                    'saldo' => true,
                                    'retorno' => false,
                                );
                                return $retorno;
                            }
                        } else {
                            $retorno = array(
                                'saldo' => true,
                                'retorno' => false,
                            );
                            return $retorno;
                        }
                    } else {
                        $retorno = array(
                            'saldo' => true,
                            'retorno' => false,
                        );
                        return $retorno;
                    }
                } else {
                    $retorno = array(
                        'saldo' => true,
                        'retorno' => false,
                    );
                    return $retorno;
                }
            }else{
                $retorno = array(
                    'saldo' => true,
                    'retorno' => false,
                );
                return $retorno;
            }
        } else {
            $retorno = array(
                'saldo' => false,
                'retorno' => false,
            );
            return $retorno;
        }
    }

    public function api($url){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8'));
        $xml= curl_exec($curl);
        curl_close($curl);
        $xml_json = json_decode($xml);
        return $xml_json;
    }

    public function getCountTranferencia($idUsuario)
    {
        return parent::getCountTranferencia($idUsuario);
    }
}