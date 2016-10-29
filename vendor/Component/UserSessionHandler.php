<?php
namespace Component;

class UserSessionHandler
{

    public static $user;

    public function __construct()
    {
        $this->castUser();
    }

    public function castUser()
    {
        if (isset($_SESSION['user'])) {
            self::$user = new \Entity\Membre;
            foreach ($_SESSION['user'] as $key => $value) {
                self::$user->$key = $value;
            }
            return self::$user;
        }
    }

    public static function expireSession()
    {
        if (isset($_SESSION['session_start_time'])) {
            if ($_SESSION['session_start_time'] + 30 * 60 < time()) {
                session_unset();
                session_destroy();
                session_start();
                session_regenerate_id(true);
                $_SESSION["expired"] = "yes";
                header("Location:?controller=MembreController&action=loginDisplay"); // Redirect to Login Page
            } else {
                $_SESSION['session_start_time'] = time();
            }
        }
    }

    public static function getUser()
    {
        $me = new self;
        return $me::$user;
    }

    public function initializeSession($user)
    {
        $array = get_object_vars($user);
        foreach ($array as $key => $value) {
            $_SESSION['user'][$key] = $value;
        }

        PanierSessionHandler::initializeCart();
        self::startSession();
    }

//Session et panier

    public static function startSession()
    {
        if (!isset($_SESSION['session_start_time'])) {
            $_SESSION['session_start_time'] = time();
        }
    }
}

