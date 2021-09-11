var id_registro = $('body').attr('id');
$(document).ready(function () {
    $('#loading').addClass('ativo');
    setTimeout(function () {
        $('#loading').addClass('not');
    }, 10000);
    $('input#senha').keyup( function (event) {
        if ($(this).val() != ''){
            $(this).parent().find('#vizualizar_senha').css('display','block');
        }else{
            $(this).parent().find('#vizualizar_senha').css('display','none');
        }
    });
    $("#vizualizar_senha").on('click', function () {
        if ($(this).hasClass('ativo')){
            $(this).removeClass('ativo');
            $('input#senha').attr('type','password');
        }else{
            $(this).addClass('ativo');
            $('input#senha').attr('type','text');
        }
    });
    if (id_registro == 'login') {
        $('form#login').submit(function (event) {
            var form = $(this);
            var m_c = $("input[name='manter_conectado']:checked").val();
            if (!validaFormulario($("form#login").selector)) {
                event.preventDefault();
            } else {
                $('#carregar').addClass('ativo');
                $('body').addClass('overflow');
                window.setTimeout(function () {
                    $.ajax({
                        url: window.location.href,
                        method: "POST",
                        dataType: "json",
                        data: {
                            acao: 'verifica_login',
                            email: form.find('input#email').val(),
                            manter_conectado: m_c,
                            senha: form.find('input#senha').val()
                        },
                        success: function (resposta) {
                            console.log(resposta.retorno);
                            if (resposta.retorno == true) {
                                window.location.href = url_padrao + '/home'
                            } else if (resposta == 2) {
                                $('#carregar').removeClass('ativo');
                                $('#form_mensagem div.mensagem').removeClass('success');
                                $('#form_mensagem div.mensagem').addClass('error');
                                $('#form_mensagem').addClass('ativo');
                                $("#form_mensagem div.mensagem span.mensagem").html('Aviso ao logal na plataforma. Verifique senha e e-mail se estão corretos.')
                                $("#form_mensagem div.mensagem span.button").html('Tente <br />novamente');
                            }
                        },
                        error: function () {
                            $('#carregar').removeClass('ativo');
                            $('#form_mensagem div.mensagem').removeClass('success');
                            $('#form_mensagem div.mensagem').addClass('error');
                            $('#form_mensagem').addClass('ativo');
                            $("#form_mensagem div.mensagem span.mensagem").html('Aviso a logal na plataforma. Verifique senha e e-mail se estão corretos.')
                            $("#form_mensagem div.mensagem span.button").html('Tente <br />novamente');
                        }
                    });
                }, 1000);
                return false;
            }
        });
    }
    if (id_registro == 'cadastro') {
        $('#cep').mask('00000-000');
        $('#cpf').mask('000.000.000-00', {reverse: true});
        $('#cnpj').mask('00.000.000/0000-00', {reverse: true});

        $('select#tipo_pessoa').change(function () {
            if ($(this).val() == 'F'){
                $('input#cpf').parent().parent().removeClass('null');
                $('input#cpf').removeClass('opcional');
                $('input#cnpj').val('');
                $('input#razao_social').val('');
                $('input#razao_social').parent().parent().addClass('null');
                $('input#cnpj').parent().parent().addClass('null');
                $('input#razao').addClass('opcional');
                $('input#cnpj').addClass('opcional');
            }else {
                $('input#cnpj').parent().parent().removeClass('null');
                $('input#cnpj').removeClass('opcional');
                $('input#razao_social').parent().parent().removeClass('null');
                $('input#razao_social').removeClass('opcional');
                $('input#cpf').val('');
                $('input#cpf').parent().parent().addClass('null');
                $('input#cpf').addClass('opcional');
            }
        });

        $('form#cadastro').submit(function (event) {
            var form = $(this);
            if (!validaFormulario($("form#cadastro").selector)) {
                event.preventDefault();
            } else {
                $('#carregar').addClass('ativo');
                $('body').addClass('overflow');
                window.setTimeout(function () {
                    $.ajax({
                        url: window.location.href,
                        method: "POST",
                        dataType: "json",
                        data: {
                            acao: 'incluir',
                            tipo_pessoa: form.find('select#tipo_pessoa').val(),
                            nome_completo: form.find('input#nome_completo').val(),
                            email: form.find('input#email').val(),
                            cpf: form.find('input#cpf').val(),
                            cnpj: form.find('input#cnpj').val(),
                            razao_social: form.find('input#razao_social').val(),
                            senha: form.find('input#senha').val()
                        },
                        success: function (resposta) {
                            $('#carregar').removeClass('ativo');
                            if (resposta.duplicado == true) {
                                $('#form_mensagem div.mensagem').removeClass('success');
                                $('#form_mensagem div.mensagem').addClass('error');
                                $('#form_mensagem').addClass('ativo');
                                $("#form_mensagem div.mensagem span.mensagem").html('Aviso, já exixte um cadastro utilizando essas informações.');
                                $('#form_mensagem div.mensagem span.button').removeClass('limpar');
                                $("#form_mensagem div.mensagem span.button").html('Tente outro<br />usuário');
                            }else if (resposta.retorno == true){
                                $('#form_mensagem').addClass('ativo');
                                $('#form_mensagem div.mensagem').removeClass('error');
                                $('#form_mensagem div.mensagem').addClass('success');
                                $("#form_mensagem div.mensagem span.mensagem").html('Cadastro realizado com sucesso.')
                                $('#form_mensagem div.mensagem span.button').addClass('limpar');
                                $("#form_mensagem div.mensagem span.button").html('Cadastrar outro <br />usuário');
                            }else{
                                $('#form_mensagem div.mensagem').removeClass('success');
                                $('#form_mensagem div.mensagem').addClass('error');
                                $('#form_mensagem').addClass('ativo');
                                $("#form_mensagem div.mensagem span.mensagem").html('Aviso, não foi possível salvar seus dados');
                                $('#form_mensagem div.mensagem span.button').removeClass('limpar');
                                $("#form_mensagem div.mensagem span.button").html('Tente <br />novamente');
                            }
                        },
                        error: function () {
                            $('#carregar').removeClass('ativo');
                            $('#form_mensagem div.mensagem').removeClass('success');
                            $('#form_mensagem div.mensagem').addClass('error');
                            $('#form_mensagem').addClass('ativo');
                            $("#form_mensagem div.mensagem span.mensagem").html('Aviso, não foi possível salvar seus dados');
                            $('#form_mensagem div.mensagem span.button').removeClass('limpar');
                            $("#form_mensagem div.mensagem span.button").html('Tente <br />novamente');
                        }
                    });
                    return false
                }, 1000);
            }
            return false
        });
    }
    $('input#foto_perfil').change( function () {
        var nome = $(this).val().split("\\");
        if (nome != '') {
            $(this).parent().children('.perfil').text(nome[2]);
        } else {
            $(this).parent().children('.perfil').text('Adicionar Foto do Perfil');
        }
    });
    $('#button_redirect .redirect').on('click', function () {
        if($(this).parent().children('#editar').hasClass('ativo')){
            $(this).parent().children('#editar').removeClass('ativo');
        }else{
            $('#button_redirect #editar').removeClass('ativo');
            $(this).parent().children('#editar').addClass('ativo');
        }
    });
    $(document).on('click', '#form_mensagem div.mensagem span.button', function () {
        $('#form_mensagem').removeClass('ativo');
        $('body').removeClass('overflow');
        if($(this).hasClass('limpar')) {
            $('form#cadastro input,form#cadastro select').val('');
            if(id_registro == 'cadastro') {
                $('input#cpf').parent().parent().removeClass('null');
                $('input#cpf').removeClass('opcional');
                $('input#cnpj').parent().parent().addClass('null');
                $('input#cnpj').addClass('opcional');
                $('input#razao').addClass('opcional');
                $('input#razao_social').parent().parent().addClass('null');
            }
        }
    });
    $('.dropdown-menu #sair').on('click', function () {
        $('#carregar').addClass('ativo');
        $('body').addClass('overflow');
        console.log(url_padrao + '/sair');
        window.setTimeout(function () {
            $.ajax({
                url: url_padrao + '/sair',
                method: "POST",
                dataType: "json",
                success: function (resposta) {
                    if (resposta == '1') {
                        window.location.href = url_padrao
                    } else if (resposta == 2) {
                        $('#carregar').removeClass('ativo');
                        $('#form_mensagem div.mensagem').removeClass('success');
                        $('#form_mensagem div.mensagem').addClass('error');
                        $('#form_mensagem').addClass('ativo');
                        $("#form_mensagem div.mensagem span.mensagem").html('Erro ao sair do sistema');
                        $("#form_mensagem div.mensagem span.button").html('Tente <br />novamente');
                    }
                },
                error: function () {
                    $('#carregar').removeClass('ativo');
                    $('#form_mensagem div.mensagem').removeClass('success');
                    $('#form_mensagem div.mensagem').addClass('error');
                    $('#form_mensagem').addClass('ativo');
                    $("#form_mensagem div.mensagem span.mensagem").html('Erro ao sair do sistema');
                    $("#form_mensagem div.mensagem span.button").html('Tente <br />novamente');
                }
            });
        }, 1000);
        return false;
    });
    $('form#form_adm').submit(function (event) {
        var form = $(this);
        if (!validaFormulario($("form#form_adm").selector)) {
            event.preventDefault();
        }
    });
    $('#button_redirect #editar .dropdown-item.deletar').on('click', function () {
        var id = $(this).attr('id_registro');
        var table = $(this).attr('table');
        var url = $(this).attr('url');
        $('#carregar').addClass('ativo');
        $('body').addClass('overflow');
        window.setTimeout(function () {
            $.ajax({
                url: window.location.href,
                method: "POST",
                dataType: "json",
                data: {
                    acao: 'deletar',
                    id_registro: id,
                    tabela: table
                },
                success: function (resposta) {
                    if (resposta == 1){
                        window.location.href = url_padrao + '/' + url + '-lista';
                    }else{
                        $('#carregar').removeClass('ativo');
                        $('#form_mensagem div.mensagem').removeClass('success');
                        $('#form_mensagem div.mensagem').addClass('error');
                        $('#form_mensagem').addClass('ativo');
                        $("#form_mensagem div.mensagem span.mensagem").html('Houve um ao apagar registro.');
                        $("#form_mensagem div.mensagem span.button").html('Tente <br />novamente');
                    }
                },
                error: function () {
                    $('#carregar').removeClass('ativo');
                    $('#form_mensagem div.mensagem').removeClass('success');
                    $('#form_mensagem div.mensagem').addClass('error');
                    $('#form_mensagem').addClass('ativo');
                    $("#form_mensagem div.mensagem span.mensagem").html('Houve um erro ao apagar registro.');
                    $("#form_mensagem div.mensagem span.button").html('Tente <br />novamente');
                }
            });
        }, 1000);
        return false;
    });
    window.setTimeout(function () {
        $('.main-content .bg-secondary #avisos.success').slideUp(400)
    }, 3000);

});
window.onresize = function () {

};

function validaFormulario(form) {
    var error = false;
    var camposVazios = [];

    $(form).find("input , select, textarea").each(function () {

        $(this).parent().find(".erro").remove();
        if (!$(this).hasClass("opcional") && $(this).val() == "" && $(this).attr("type") != "hidden") {
            var erros = {
                'campo': $(this).attr("id"),
                'msg': "Preencha este campo"
            };
            camposVazios.push(erros);
        }
        else {
            $(this).next(".erro").remove();
        }

    });
    if (camposVazios.length > 0) {
        for (var i = 0; i < camposVazios.length; i++) {
            $(form + " input" + "#" + camposVazios[i].campo).parent().append("<span class='erro'><span>" + camposVazios[i].msg + "</span></span>");
        }
        for (var x = 0; x < camposVazios.length; x++) {
            $(form + " select" + "#" + camposVazios[x].campo).parent().append("<span class='erro'><span>" + camposVazios[x].msg + "</span></span>");
        }
        for (var j = 0; j < camposVazios.length; j++) {
            $(form + " textarea" + "#" + camposVazios[j].campo).parent().append("<span class='erro'><span>" + camposVazios[j].msg + "</span></span>");
        }
        return false;
    }
    return true;
}
function limpar(x) {
    return x.replace(",", "").replace(".", "").replace("R$", "").replace(" ", "");
}
function formatarDinheiro(numero) {

    if (isNaN(numero)) return "Valor não preenchido corretamente";
    var negativo = numero < 0;
    numero = Math.abs(numero);
    var resposta = "";
    var t = numero + "";
    for (var i = t.length - 1; i >= 0; i--) {
        var j = t.length - i;
        resposta = t.charAt(i) + resposta;
        if (j == 2) {
            resposta = "," + resposta;
        } else if (j % 3 == 2 && i != 0) {
            resposta = "." + resposta;
        }
    }
    if (resposta.length < 4) {
        resposta = "0,00".substring(0, 4 - resposta.length) + resposta;
    }
    if (negativo) resposta = "-" + resposta;
    return resposta;
}

