<?php

class Autoload
{

    public static function className($className)
    {
        $ex = explode('\\', $className);

//"si le module qu'on me demande est dans le Backoffice":
        if ($ex[0] == 'Backoffice' || $ex[0] == 'BackOffice') {
//"alors remonte dans le repertoire src"
            $path = __DIR__ . '/../src/' . implode('/', $ex) . '.php';
        } elseif ($ex[0] == 'Component') {
            $path = __DIR__ . '/' . implode('/', $ex) . '.php';
        } else {
            $path = __DIR__ . '/' . implode('/', $ex) . '.php';
        }
        //var_dump($path);
        require $path;
    }

}

spl_autoload_register(array('Autoload', 'className'));
