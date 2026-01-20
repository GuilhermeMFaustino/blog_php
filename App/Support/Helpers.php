<?php

namespace App\Support;

use Source\Config;

class Helpers
{

    public static function redirect(?string $url = null)
    {
        header('HTTP/1.1 302 Found');
        $local = ($url ? self::url($url) : self::url());
        header("Location: {$local} ");
        exit();
    }


    /**
     * Summary of valorFormat
     * @param mixed $valor
     * @return string
     */
    public static function valorFormat(?float $valor = null)
    {
        return number_format($valor ? $valor : 0, "2", ".", ",");
    }


    /**
     * Summary of localhost
     * @return bool
     */
    public static function localhost(): bool
    {
        $server = filter_input(INPUT_SERVER, 'SERVER_NAME');
        //var_dump($server);

        if ($server == SERVIDOR_DESENVOLVIMENTO) {
            return true;
        }
        return false;
    }



    /**
     * Summary of tempo
     * @param string $data
     * @return string
     */
    public static function tempo(string $data)
    {
        // data atual
        $dateDia = time();
        $tempo = strtotime($data);

        // diferença em segundos
        $dif = $dateDia - $tempo;

        // conversões
        $segundos = $dif;
        $minutos = $dif / 60;
        $hora     = $dif / 3600;
        $dia     = $dif / 86400;
        $semana  = $dif / 604800;
        $mes      = $dif / 2419200;
        $ano      = $dif / 29030400;

        if ($segundos < 60) {
            return "agora";
        } elseif ($minutos < 60) {
            return $minutos < 2 ? "há 1 minuto" : "há " . round($minutos) . " minutos";
        } elseif ($hora < 24) {
            return round($hora) == 1 ? "há 1 hora" : "há " . round($hora) . " horas";
        } elseif ($dia < 7) {
            return round($dia) == 1 ? "há 1 dia" : "há " . round($dia) . " dias";
        } elseif ($semana < 4) {
            return round($semana) == 1 ? "há 1 semana" : "há " . round($semana) . " semanas";
        } elseif ($mes < 12) {
            return round($mes) == 1 ? "há 1 mês" : "há " . round($mes) . " meses";
        } else {
            return round($ano) == 1 ? "há 1 ano" : "há " . round($ano) . " anos";
        }
    }

    /**
     * Summary of url
     * @param string $url
     * @return string
     */


    public static function url(?string $url = null): string
    {
        $servidor = $_SERVER['SERVER_NAME'];

        if ($servidor == SERVIDOR_DESENVOLVIMENTO) {
            $base = URL_DESENVOLVIMENTO;
        } else {
            $base = URL_PRODUCAO;
        }
        return $base . "/" . ltrim($url, "/");
    }



    /**
     * Summary of slug
     * @param string $url
     * @return string
     */
    public static function slug(string $url): string
    {

        filter_var(mb_strtolower($url), FILTER_SANITIZE_SPECIAL_CHARS);
        $formats = 'ÁÀÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßáàâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ@$%&*()_-=+={}[]/?`!;:.,\\\'<>°ºª';
        $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyrr                                 ';

        $urlnova = strtr($url, $formats, $replace);
        // tudo minúsculo
        $urlnova = strtolower($urlnova);
        $urlnova = strip_tags(trim($url));
        // converter espaços em traço
        $urlnova = preg_replace('/\s+/', '-', $urlnova);
        // remover caracteres inválidos
        $urlnova = preg_replace('/[^a-z0-9\-]/', '', $urlnova);
        // substituir múltiplos traços
        $urlnova = preg_replace('/-+/', '-', $urlnova);
        //tira os espacos em branco.
        $urlnova = trim($urlnova, '-');

        return $urlnova;
    }



    /**
     * Summary of validaCPF
     * @param mixed $cpf
     * @return bool
     */
    public static function validaCPF($cpf)
    {

        $cpf = preg_replace('/[^0-9]/', "", $cpf);
        $digitoA = 0;
        $digitoB = 0;

        for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
            $digitoA += $cpf[$i] * $x;
        }
        for ($a = 0, $b = 11; $a <= 9; $a++, $b--) {
            // echo $cpf[$a]." x ".$b ."= "."<br>";
            if (str_repeat($a, 11) == $cpf) {
                return false;
                // return;
            }

            $digitoB += $cpf[$a] * $b;
        }
        $digito1 = ($digitoA * 10) % 11;
        $segundo = ($digitoB * 10) % 11;
        if ($digito1 != $cpf[9] || $segundo != $cpf[10]) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Summary of validaEmail
     * @param mixed $email
     * @return bool
     */
    public static function validaEmail($email)
    {
        $emailValido =  filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($emailValido == false) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Summary of validaUrl
     * @param string $url
     * @return bool
     */
    public static function validaUrl(string $url): bool
    {
        //$url = filter_var($url, FILTER_VALIDATE_URL);

        if (!str_contains($url, "http://")) {
            return false;
        }

        if (str_contains($url, 'http://') || str_contains($url, 'https://')) {
            return true;
        }

        return false;
    }


    /**
     * Summary of saudacoes
     * @return string
     */
    public static function saudacoes(): string
    {

        $hora = date("H");

        switch ($hora) {

            case ($hora > 0 and $hora <= 5):
                $saudacoes = "Boa Madrugada.";
                break;

            case ($hora >= 6 and $hora <= 12):
                $saudacoes = "Bom dia";
                break;

            case ($hora >= 12 and $hora < 19):
                $saudacoes = "Boa Tarde";
                break;

            case ($hora > 18 and $hora < 0):
                $saudacoes = "Boa Noite";
                break;
        }
        return $saudacoes;
    }



    /**
     * Summary of dataAtual
     * @return string
     */
    public static function dataAtual(): string
    {
        $dia = date("w");
        $diadaSemana = date("d");
        $mes = date("m");
        $ano = date("Y");

        $diasSemana = [

            "0" => "domingo",
            "1" => "segunda",
            "2" => "terca",
            "3" => "quarta",
            "4" => "quinta",
            "5" => "sexta",
            "6" => "sabado"
        ];

        $meses = [

            "0" => "janeiro",
            "1" => "fevereiro",
            "2" => "marco",
            "3" => "abril",
            "4" => "maio",
            "5" => "junho",
            "6" => "julho",
            "7" => "agosto",
            "8" => "setembro",
            "9" => "outubro",
            "10" => "novembro",
            "11" => "dezembro"
        ];


        $mesAno = [
            "1" => "janeiro",
            "2" => "fevereiro",
            "3" => "marco",
            "4" => "abril",
            "5" => "maio",
            "6" => "junho",
            "7" => "julho",
            "8" => "agosto",
            "9" => "setembro",
            "10" => "outubro",
            "11" => "novembro",
            "12" => "Dezembro"
        ];

        foreach ($diasSemana as $d => $b) {
            foreach ($mesAno as $meses => $m) {
                if ($d == $dia && $meses == $mes) {
                    $dataformatada =  ($diasSemana[$d] == "sabado" ? "Sabado" : $diasSemana[$d] . "-feira") . ", " . $diadaSemana . " de " . $m . " de " . $ano;
                }
            }
        }
        return $dataformatada;
    }



    public static function strLmWords(string $texto, $limit = 80): string
    {
        $pos = strip_tags($texto);
        $posLimpo = strlen($pos);
        if ($posLimpo <= $limit) {
            return $texto;
        } else {
            $textCortado = substr($texto, 0, $limit);
            $novoTexto = strrpos($textCortado, ' ');
            $textoLimit = substr($textCortado, 0, $novoTexto);
            return $textoLimit . "...";
        }
    }

}
