<?php

namespace App\Models;

use App\Core\Models;

use PDO;

class Online extends Models
{
    protected string $table = "online";


    public function countOnline()
{
    $time = 15;
    $result = (new Online())->find(
        "last_activity >= NOW() - INTERVAL {$time} MINUTE",
        "COUNT(*) as total"
    );
    return $result[0]->total ?? 0;
}

    public function cleanOnline()
    {
        return $this->delete(
            "last_activity < NOW() - INTERVAL 10 MINUTE"
        );
    }
  
}