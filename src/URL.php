<?php

namespace App;

use Exception;


class URL  {

    public static function getInt(string $name, ?int $default = null): ?int
    {
        if(!isset($_GET[$name])) return $default;
            if($_GET === '0') return 0;
            if(!filter_var($_GET[$name], FILTER_VALIDATE_INT))
            {
                 throw new Exception("Le paramètre '$name' dans l'url n'est pas un entier");
            }
            return (int)$_GET[$name];
        
    }

    public static function getPositiveInt(string $name, ?int $default = null): ?int
    {
        $param = self::getInt($name, $default);
        if($param !== null && $param <= 0){
            throw new Exception("Le paramètre '$name' dans l'url n'est pas un entier positif");
        }
        return $param;
    }

    public static function getSortDir(string $route, string $sort, string $title): string
    {
        $dir = 'asc';
        $get = $_GET['dir'] ?? null;
            if($get === 'asc'){
                $dir = 'desc';
                $title .= ' ^';
            }elseif($get === 'desc'){
                 $dir = 'asc';
                 $title .= ' v';
            }
        return <<<HTML
        <a href="$route?sort={$sort}&dir={$dir}" data-toggle="tooltip" title="{$title}">$title</a>
        HTML;
    }

    public static function getProtocol(): string
    {
        $protocol = null;
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off"){
            $protocol = "https://";
        }else{
            $protocol = "http://";
        }
        return $protocol;
    }

}