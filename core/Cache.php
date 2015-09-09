<?php

/*
 * Copyright (C) 2015 wkeller
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Cache {

    /**
     * Tempo padrão de cache
     *
     * @var string
     */
    private static $tempo = '1440 minutes';

    /**
     * Local onde o cache será salvo
     *
     * Definido pelo construtor
     *
     * @var string
     */
    private $pasta = 'cache';

    /**
     * Construtor
     *
     * Inicializa a classe e permite a definição de onde os arquivos
     * serão salvos. Se o parâmetro $pasta for ignorado o local dos
     * arquivos temporários do sistema operacional será usado
     *
     * @param string $pasta Local para salvar os arquivos de cache (opcional)
     * @return void
     */
    function __construct() {
        
    }

    /**
     * Gera o local do arquivo de cache baseado na chave informada
     *
     * @param string $chave Uma chave para identificar o arquivo
     * @return string Local do arquivo de cache
     */
    private function localArquivo($chave) {

        return SYS_PATH . $this->pasta . DIRECTORY_SEPARATOR . sha1($chave) . '.tmp';
    }

    private function comprimeDados($conteudo) {

        return str_replace(array("\r\n", "\r", "\n", "\t", "    ", "   ", "  "), '', $conteudo);
    }

    /**
     * Cria um arquivo de cache
     *
     * @param string $chave Uma chave para identificar o arquivo
     * @param string $conteudo Conteúdo do arquivo de cache
     * @return boolean Se o arquivo foi criado
     */
    private function criaArquivo($chave, $conteudo) {

        // Gera o nome do arquivo
        $arquivo = $this->localArquivo($chave);

        $dados = $this->comprimeDados($conteudo);

        // Cria o arquivo com o conteúdo
        return file_put_contents($arquivo, $dados) OR trigger_error('Não foi possível criar o arquivo de cache', E_USER_ERROR);
    }

    /**
     * Salva um valor no cache
     *
     * @param string $chave Uma chave para identificar o valor cacheado
     * @param mixed $conteudo Conteúdo/variável a ser salvo(a) no cache
     * @param string $tempo Quanto tempo até o cache expirar (opcional)
     * @return boolean Se o cache foi salvo
     */
    public function put($chave, $conteudo, $tempo = null) {

        $tempo = strtotime(!is_null($tempo) ? $tempo : self::$tempo);

        $conteudo = serialize(array(
            'expira' => $tempo,
            'conteudo' => $conteudo));

        return $this->criaArquivo($chave, $conteudo);
    }

    /**
     * Recupera um valor do cache
     *
     * @param string $chave Uma chave para identificar o valor em cache
     * @return mixed Se o cache foi encontrado retorna o seu valor.
     * Caso contrário retorna NULL
     */
    final public function get($chave) {

        $arquivo = $this->localArquivo($chave);

        if (file_exists($arquivo) && is_readable($arquivo)) {

            $data = @preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", file_get_contents($arquivo));

            $cache = unserialize($data);

            if ($cache['expira'] > time()) {

                return $cache['conteudo'];
            } else {
                unlink($arquivo);
            }
        }
        return false;
    }

}
