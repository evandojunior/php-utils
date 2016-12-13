<?php
/**
 * Algumas funcionalidades básicas que fazem a gente repetir muito código
 * @author Evando Junior <evando.junior@live.com>
 * @version 1.0
 */
class Utils {


    public static function is_cnpj ($param, $return = false){
        return !$return 
        ? strlen(preg_replace('/[^0-9]/', "", $param)) == 14
        : preg_replace('/[^0-9]/', "", $param);
    }

    public static function is_cpf ( $param, $return = false ){
        return !$return 
        ? strlen(preg_replace('/[^0-9]/', "", $param)) == 11
        : preg_replace('/[^0-9]/', "", $param);
    }

    /**
     * Retira caracteres especiais de uma string
     * @param string $param 
     * @return string
     */
    public static function sanitize ( $param ){
        return strtr(
            utf8_decode($param), 
            utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 
            'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'
            );
    }

    /**
     * Extrai informacoes do certificado digital
     * @param string $pathfile  caminho do certificado digital
     * @param type $passwd    senha do certificado digital
     * @param "json" | array $return_type  tipo de retorno do método
     * @return type
     */
    public static function digitalcert( $pathfile, $passwd, $return_type = []){
        // conteudo do certificado digital
        $pkcs12 = file_get_contents($pathfile); 

        // array temporario para callback
        $certs = []; 

        // leitura do certificado digital
        if(!openssl_pkcs12_read($pkcs12, $certs, $passwd)) return;

        $dados = openssl_x509_parse( openssl_x509_read($certs['cert']) );

        // informações mais importantes, caso queria saber de mais alguma coisa, retorne a variavel $dados
        $list = [
        "pais" => $dados['subject']['C'],
        "estado" => $dados['subject']['ST'],
        "municipio" => $dados['subject']['L'],
        "razao_social" => explode(':', $dados['subject']['CN'])[0],
        "cnpj" => explode(':', $dados['subject']['CN'])[1],
        "email" => $dados['extensions']['subjectAltName'],
        "data_criacao" => date( 'd/m/Y', $dados['validFrom_time_t']),
        "data_validade" => date( 'd/m/Y', $dados['validTo_time_t']),
        "certificadora" => $dados['issuer']['CN']
        ];

        // retorna um json
        if($return_type == "json") return json_encode($list);

        // retorna um array
        return $list;
    }
}
