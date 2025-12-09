<?php

namespace App\Core;

use App\Source\Config;

class Helpers
{
    /**
     * Summary of valorFormat
     * @param mixed $valor
     * @return string
     */
    function valorFormat(?float $valor = null)
    {
        return number_format($valor ? $valor : 0, "2", ".", ",");
    }


    /**
     * Summary of localhost
     * @return bool
     */
    function localhost(): bool
    {
        $server = filter_input(INPUT_SERVER, 'SERVER_NAME');

        if ($server == 'localhost') {
            return true;
        }
        return false;
    }



    /**
     * Summary of tempo
     * @param string $data
     * @return string
     */
    function tempo(string $data)
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


        echo $hora . "</br>";

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
    function url(string $url): string
    {
        $srevidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $ambiente = ($srevidor == 'localhostc' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);
        return $ambiente . "/" . $url;
    }



    /**
     * Summary of slug
     * @param string $url
     * @return string
     */
    function slug(string $url): string
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
    function validaCPF($cpf)
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
    function validaEmail($email)
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
    function validaUrl(string $url): bool
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
}
