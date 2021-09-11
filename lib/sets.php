<?php
/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class sets extends conexao{
    //metodos
    public function contruct($return = false)
    {
        if (isset($_POST['acao']) && $_POST['acao'] != '' && $return == true) {
            return $_POST['acao'];
        }

    }
    public function __call($metodo, $parametros)
    {
        if (substr($metodo, 0, 3) == 'set') {
            $atributos = substr(strtolower($metodo), 4);
            if (isset($parametros[0])) $this->$atributos = $parametros[0];
        } elseif (substr($metodo, 0, 3) == 'get') {
            $atributos = substr(strtolower($metodo), 4);
            return $this->$atributos;
        }
    }

    function uploadImagem($file, $filetpm, $dir_file, $nome, $thumb = false, $dir_thumb = array(), $sizes = array())
    {
        $nome_imagem = $file;
        preg_match('/\.(gif|png|jpg|jpeg|bmp){1}$/i', $nome_imagem, $ext);
        $ext = isset($ext[1]) ? strtolower($ext[1]) : '';
        $nome_imagem_formatado = $nome . "." . $ext;
        $caminho = $dir_file . $nome_imagem_formatado;
        move_uploaded_file($filetpm, $caminho);
        if ($thumb) {
            for ($i = 0; $i < count($dir_thumb); $i++) {
                $caminho_thumb = $dir_file . $dir_thumb[$i] . $nome_imagem_formatado;
                $this->CroppedThumbnail($caminho, $caminho_thumb, $sizes[$i][0], $sizes[$i][1]);
            }
        }
        return $nome_imagem_formatado;
    }
    public function uploadImagemBgTransparente($src_origem, $src_destino, $width, $height)
    {
        list($width_original, $height_original, $mimeType) = getimagesize($src_origem);
        $mimeType = image_type_to_mime_type($mimeType);

        //Redimensiona a camada 1
        $thumb = imagecreatetruecolor($width, $height);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);

        //Redimensiona a camada transparente
        $source = imagecreatefrompng($src_origem);
        imagealphablending($source, true);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $width_original, $height_original);

        //Verifica o tipo de imagem a ser criada
        if ($mimeType == "image/png" || $mimeType == "image/x-png") {
            imagepng($thumb, $src_destino);
        } else {
            imagegif($thumb, $src_destino);
        }
        imagedestroy($thumb);
        imagedestroy($source);
    }

    public function CroppedThumbnail($imgSrc, $caminho_thumb, $thumbnail_width, $thumbnail_height)
    {
        list($width_orig, $height_orig, $mimeType) = getimagesize($imgSrc);
        $mimeType = image_type_to_mime_type($mimeType);

        if ($mimeType == "image/png" || $mimeType == "image/x-png" || $mimeType == "image/gif") {
            $this->uploadImagemBgTransparente($imgSrc, $caminho_thumb, $thumbnail_width, $thumbnail_height);
        } else {
            $myImage = imagecreatefromjpeg($imgSrc);
            $ratio_orig = $width_orig / $height_orig;


            if ($thumbnail_width / $thumbnail_height > $ratio_orig) {
                $new_height = $thumbnail_width / $ratio_orig;
                $new_width = $thumbnail_width;
            } else {
                $new_width = $thumbnail_height * $ratio_orig;
                $new_height = $thumbnail_height;
            }

            $x_mid = $new_width / 2;
            $y_mid = $new_height / 2;

            $process = imagecreatetruecolor(round($new_width), round($new_height));
            imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
            imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

            imagedestroy($process);
            imagedestroy($myImage);
            imagejpeg($thumb, $caminho_thumb, 100);
        }
    }


    public function tratarUrl($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }


    /*
     * author: Leonardo Cruz*/
    public function incluir(){
        header("Content-type: text/html;charset=utf-8");
        $sql = "SHOW FULL FIELDS FROM " . $this->table;
        $execute = conexao::toConnect()->executeS($sql);
        $atributos = "";
        $values = "";
        $id_registro = "";
        $contador = count($execute) - 1;
        foreach ($execute as $key => $attr){
            if ($attr->Key != 'PRI') {
                $atributos_field = $attr->Field;
                $select_p = substr(strtoupper($atributos_field), 0, 1);
                $select_t = substr($atributos_field, 1);
                $get = 'get' . $select_p . $select_t;
                if ($contador != $key) {
                    $atributos .= $attr->Field . ',';
                    $values .= "'".$this->$get() ."',";
                } else {
                    $atributos .= $attr->Field;
                    $values .= "'".$this->$get() ."'";
                }
            }else{
                $id_registro = $attr->Field;
            }
        }
        $insert = "INSERT INTO ".$this->table." (".$atributos.") VALUES($values)";
        $execute_into = conexao::toConnect()->executeQuery($insert);
        if (count($execute_into) > 0) {
            $sql = "SELECT ".$id_registro." FROM ".$this->table." ORDER BY ".$id_registro." DESC LIMIT 1";
            $id_r = conexao::toConnect()->executeS($sql);
            return $id_r[0]->$id_registro;
        }else{
            return false;
        }

    }
    #alterar registros
    /*
     * author: Leonardo Cruz*/
    protected function alterar(){
        header("Content-type: text/html;charset=utf-8");
        $sql = "SHOW FULL FIELDS FROM " . $this->table;
        $execute = conexao::toConnect()->executeS($sql);
        $sets = "";
        $id_registro = '';
        $contador = "";
        $count = 1;
        foreach ($execute as $contar) {
            if ($contar->Key != 'PRI') {
                $atributos_field = $contar->Field;
                $select_p = substr(strtoupper($atributos_field), 0, 1);
                $select_t = substr($atributos_field, 1);
                $get = 'get' . $select_p . $select_t;
                if ($this->$get() != null) {
                    $contador = $contador + 1;
                }
            }
        }
        foreach ($execute as $key => $attr){
            if ($attr->Key != 'PRI') {
                $atributos_field = $attr->Field;
                $select_p = substr(strtoupper($atributos_field), 0, 1);
                $select_t = substr($atributos_field, 1);
                $get = 'get' . $select_p . $select_t;
                if ($this->$get() != null) {
                    if ($count != $contador) {
                        $sets .= $attr->Field . " = '" . $this->$get() . "',";
                    } else {
                        $sets .= $attr->Field . " = '" . $this->$get()."'";
                    }
                    $count = $count + 1;
                }
            }else{
                $id_registro = $attr->Field;
            }
        }
        $update = "UPDATE ".$this->table." SET ".$sets." WHERE ".$id_registro." = ".$this->getIdTable();

        $execute_into = conexao::toConnect()->executeQuery($update);
        if (count($execute_into) > 0) {
            return $execute_into;
        }else{
            return false;
        }
    }
    /*
     * author: Leonardo Cruz*/
    public function getRegistros(){
        $sql = "SELECT * FROM ".$this->getTable()." WHERE ".$this->getTableId()." = ".$this->getIdTable();
        $execute = conexao::toConnect()->executeS($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }
    /*
     * author: Leonardo Cruz*/
    public function deletar($id,$tabela){
        $sql = "DELETE FROM ".$tabela." WHERE id_".$tabela." = ".$id;
        $execute = conexao::toConnect()->executeQuery($sql);
        if (count($execute) > 0) {
            return $execute;
        }else{
            return false;
        }
    }
    protected function get_registro($event){
        return $_POST[$event];
    }
}
