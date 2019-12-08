<!DOCTYPE html>
<html lang="fr" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? " Le Blog " ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="d-flex flex-column h-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a href="<?= $router->url('home')?>" class="navbar-brand">Mon site</a>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
    <a href="<?= $router->url('home') ?>" class="nav-link">Home</a> 
    <a href="<?= $router->url('admin_posts') ?>" class="nav-link">Admin</a> 
    </ul>
  </div>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <form class="form-inline ml-auto" method="GET">
      <div class="md-form my-0">
        <input class="form-control" type="text" name="q" placeholder="Search" aria-label="Search" value="<?= htmlentities($_GET['q'] ?? null) ?>">
      </div>
      <button href="#!" class="btn btn-primary btn-md my-0 ml-sm-2" type="submit">Search</button>
    </form>
</nav>
    </nav>
    <div class="container mt-4">
        <?= $content ?>
    </div>
 </body>
<footer class="bg-light py-4 footer mt-auto">
    <div class="container">
        <?php if(defined('DEBUG_TIME')):?>
            Page générée en <?= round(1000 * (microtime(true) - DEBUG_TIME))?>ms
        <?php endif; ?>
    </div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>$(function (){$('[data-toggle="tooltip"]').tooltip()})</script>
</footer>
</html>