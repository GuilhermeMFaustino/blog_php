<?php 

namespace App\Core;

class Session
{
    public function __construct()
    {
        if(!session_id()){
            session_start();
        }
    }


    public function create(string $key, mixed $value): Session
    {
         $_SESSION[$key] = (is_array($value) ? (object) $value : $value);
         return $this;
    }

    public function load(): ?object
    {
        return (object) $_SESSION;
    }

    public function check(string $key): bool
    {
        if(isset($_SESSION[$key])){
            return true;
        }else{
            return false;
        }
    }    

    public function clean(string $key)
    {
        unset($_SESSION[$key]);
    }

    public function destroy()
    {
        session_destroy();
        return $this;
    }


}

