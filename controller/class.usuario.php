<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class Usuario extends ModelUsuario
{
    public  $pagina = "usuario-form";
    public  $pagina_lista = "usuario-lista";

    //Declaração do Método Construtor Padrão
    public function __construct($return = false){
       $retorno =  parent::contruct($return);
       $this->PostSubmit($retorno);
    }

    public function PostSubmit($acao){
        switch ($acao){
            case 'verifica_login':
                if ($this->verificaLogin()) {
                    $retorno = array('retorno' => true);
                    exit(json_encode($retorno));
                } else {
                    $retorno = array('retorno' => false);
                    exit(json_encode($retorno));
                }

                break;
            case 'incluir':
                if ($this->verificaCadastroDuplicado()){
                    $retorno = array('duplicado' => true, 'retorno' => false);
                    exit(json_encode($retorno));
                }else {
                    $id_registro = $this->incluirRegistros();
                    if ($id_registro) {
                        $retorno = array('duplicado' => false, 'retorno' => true);
                        exit(json_encode($retorno));
                    } else {
                        $retorno = array('duplicado' => false, 'retorno' => false);
                        exit(json_encode($retorno));
                    }
                }
                break;
            case 'alterar':
                if ($this->alterarRegistros($_GET['id'])){
                    header("Location: " . URL_SITE . '/'.$this->pagina.'?id='.$_GET['id'].'&msg=ok');
                }else{
                    header("Location: " . URL_SITE . '/'.$this->pagina.'?id='.$_GET['id'].'msg=erro');
                }
                break;
            case 'deletar':
                if(parent::deletar($_POST['id_registro'],$_POST['tabela'])){
                    exit('1');
                }else{
                    exit('2');
                }

                break;
        }
    }

    /*
    * author: Leonardo Cruz
    * Verifica se já existe o usuário cadastrado
    * */
    public function verificaCadastroDuplicado(){
        //pessoa Fisíca
        if($this->get_registro('tipo_pessoa') == 'F') {
            if (parent::cadastroDuplicadoPessoaFisica(
                $this->get_registro('email'),
                $this->get_registro('cpf')
            )) {
                return true;
            } else {
                return false;
            }
        }
        //pessoa Jurídica
        else {
            if (parent::cadastroDuplicadoPessoaJuridica(
                $this->get_registro('email'),
                $this->get_registro('cnpj')
            )) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function verificaLogin(){
        session_start();
        $return = parent::verificaLogin($this->get_registro('email'),md5($this->get_registro('senha')),'A');
        if ($return){
            $_SESSION['login'] = $return[0]->nome;
            $_SESSION['id_registro'] = $return[0]->id_usuario;
            $_SESSION['email'] = $return[0]->email;
            $_SESSION['tipo_pessoa'] = $return[0]->tipo_pessoa;
            if ($_POST['manter_conectado'] == 'on'){
                $nome = "usuario_conectado";
                $valor = $_POST['email'].','.$this->get_registro('senha');
                $expira = time() + 3600;
                setcookie($nome, $valor, $expira);
            }
            return true;
        }else{
            unset ($_SESSION['login']);
            return false;
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

    public function buscaRegistroPessoaFisica($id)
    {
        return parent::buscaRegistroPessoaFisica($id);
    }

    public function buscaRegistroPessoaJuridica($id)
    {
        return parent::buscaRegistroPessoaJuridica($id);
    }

    /*
     * author: Leonardo Cruz
     * Inserir registros
     * */
    public function incluirRegistros(){
        date_default_timezone_set('America/Sao_Paulo');
        $this->setData_hora(date('Y-m-d H:i:s'));
        $this->setTipo_pessoa($this->get_registro('tipo_pessoa'));
        $this->setNome(utf8_decode($this->get_registro('nome_completo')));
        $this->setEmail(utf8_decode($this->get_registro('email')));

        $senha = md5($_POST['senha']);
        $this->setSenha($senha);
        $this->setStatus('A');

        $retorno = parent::incluir();

        if ($retorno) {
            if($this->get_registro('tipo_pessoa') == 'F') {
                parent::insertPessoaFisica($retorno,$this->get_registro('cpf'));
            } else {
                parent::insertPessoaJuridica($retorno,$this->get_registro('cnpj'),$this->get_registro('razao_social'));
            }
            $dinheiro = new Dinheiro();
            $dinheiro->insertDinheiro($retorno,'500.00');
        }

        return $retorno;
    }

    /*
     * Leonardo Cruz
     * author: Alterar registros
     * */
    public function alterarRegistros($id){
        $this->setIdTable($id);

        $this->setTipo_pessoa($this->get_registro('tipo_pessoa'));
        $this->setNome(utf8_decode($this->get_registro('nome_completo')));
        $this->setEmail(utf8_decode($this->get_registro('email')));

        if ($this->get_registro('senha')) {
            $senha = md5($this->get_registro('senha'));
            $this->setSenha($senha);
        }

        $retorno = parent::alterar();

        if ($retorno) {
            if($this->get_registro('tipo_pessoa') == 'F') {
                parent::updatePessoaFisica($id,$this->get_registro('cpf'));
            } else {
                parent::updatePessoaJuridica($id,$this->get_registro('cnpj'),$this->get_registro('razao_social'));
            }
        }

        return $retorno;
    }

    /*
     * Leonardo Cruz
     * author: Deslogar usuário
     * */
    public function sair()
    {
        session_start();
        unset ($_SESSION['login']);
        unset ($_SESSION['id_registro']);
        unset ($_SESSION['email']);
        unset ($_SESSION['tipo_pessoa']);
        if ($_SESSION['login'] == ''){
            exit('1');
        }else{
            exit('2');
        }
    }
}