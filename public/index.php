<?php
require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));
$whoops = new \Whoops\Run;
$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if(isset($_GET['page']) && $_GET['page'] === '1')
{
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if(!empty($query)){
        $uri = $uri . '?' . $query;
    }
    http_response_code(301);
    header('Location: ' . $uri);
    exit();
}
$viewsPath = (dirname(__DIR__) . '/views');
$router = new App\Router($viewsPath);
$router->get('/', 'post/index', 'home')
       ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
       ->postGet('/blog/[*:slug]-[i:id]', 'post/show', 'Articles')
       
       /* Login / Logout*/
       ->postGet('/login', 'auth/login', 'login')
       ->post('/logout', 'auth/logout', 'logout')

       /* Administration: */

       /* Artciles */
       ->get('/admin', 'admin/post/index', 'admin_posts')
       ->postGet('/admin/post/[i:id]', 'admin/post/edit', 'edit_post')
       ->post('/admin/post/[i:id]/delete', 'admin/post/delete', 'delete_post')
       ->postGet('/admin/post/new', 'admin/post/new', 'new_post')

       /* Catégories */
       ->get('/admin/category', 'admin/category/index', 'admin_categorys')
       ->postGet('/admin/category/[i:id]', 'admin/category/edit', 'edit_category')
       ->post('/admin/category/[i:id]/delete', 'admin/category/delete', 'delete_category')
       ->postGet('/admin/category/new', 'admin/category/new', 'new_category')
       ->run();
    


   /*$page = (int)($_GET['page'] ?? 1) ? : 1;
if(!filter_var($page, FILTER_VALIDATE_INT)){
    header('Location: /?page='.floor($page));
    exit();         
}*/
/*if($currentPage > $pages){
    header('Location: ' ?page= . $pages);
    exit();
}*/ 
?>


