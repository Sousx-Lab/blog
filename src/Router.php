<?php
namespace App;

use AltoRouter;
use App\Security\ForbiddenException;

class Router{

    /**
     * @var string Path views folder
     */
    private $viewPath;

    /**
     * @var AltoRouters
     */
    private $router;


    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
        
    }

    public function get(string $url, $view, ?string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    public function post(string $url, $view, ?string $name = null): self
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    public function postGet(string $url, $view, ?string $name = null): self
    {
        $this->router->map('POST|GET', $url, $view, $name);
        return $this;
    }

    public function url(string $name, array $params = [])
    {
        return $this->router->generate($name, $params);
    }

    public function layouts(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        $pos = (strpos($uri, "/admin"));
        if($pos !== false && $pos === 0){
            $layouts = 'admin/layouts/default.php';
        }else {
            $layouts = 'layouts/default.php';
        }
        return $layouts;
        
    }
    

    public function run():self
    {
        $match = $this->router->match();
        $view = $match['target'] ?: '404';
        $params = $match['params'];
        $router = $this;
        try{
            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
            $content = ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . $this->layouts();  
        }catch(ForbiddenException $e){
            header('Location: ' . $this->url('login') . '?forbidden=1');
            exit();
        }
        return $this;
    }
}