<?php

namespace App\Core;

use App\Models\Online;
use App\Models\Views;

class Visits extends Models
{

    private string $ip;
    private string $session;

    private string $navegador;


    public function __construct() {}

    public function getIp()
    {

        return $_SERVER['REMOTE_ADDR'];
    }

    public function getSession()
    {
        return $_SESSION['visitante'] ?? null;
    }

    public function getNavegador()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function getTime()
    {
        return date('H:i:s');
    }

    public function views(): Visits
    {
        if (!isset($_SESSION['visitante'])) {
            $_SESSION['visitante'] = session_id();
        }
        $session = $this->getSession();

        $last = (new Views())->order("hora DESC")->findOne("session = :s", "s={$session}");

        $horaAgora = time();

        if (!$last) {
            $this->salvarVisita();
            return $this;
        }

        $ultimaHora = strtotime($last->hora);

        $mesmoDia = date('Y-m-d', $ultimaHora) === date('Y-m-d', $horaAgora);

        if ($mesmoDia && ($horaAgora - $ultimaHora >= 3600)) {

            $this->salvarVisita();
        }
        return $this;
    }

    private function salvarVisita()
    {
        $view['ip'] = $this->getIp();
        $view['navegador'] = $this->getNavegador();
        $view['session'] = $this->getSession();
        $view['hora'] = date('Y-m-d H:i:s');
        (new Views())->save($view);
    }


    public function updateOnline() : bool
    {
        $session = $this->getSession();
        $ip = $this->getIp();

        $online = (new Online())->findOne(
            "session = :s",
            "s=" . urlencode($session)
        );

        if ($online && $online->ip == $ip) {

            $dados = [];
            $dados['last_activity'] = date('Y-m-d H:i:s');
            $dados['session'] = $session;
            $dados['ip'] = $ip;

            $test = (new Online())->update(
                $dados,
                "session = :s",
                "s=" . urlencode($session)
            );
        } else {

            (new Online())->save([
                'session' => $session,
                'ip' => $ip,
                'last_activity' => date('Y-m-d H:i:s')
            ]);
        }

        return true;
    }

    public function countVisitas()
    {
        $views = (new Views())->find();

        return count($views);
    }
}
