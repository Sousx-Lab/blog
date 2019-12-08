<?php
http_response_code(404);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    404 Not Found</h2>
                <div class="error-details">
                    Désolé, une erreur s'est produite, page demandée non trouvée!
                </div>
                <div class="error-actions">
                    <a href="<?= $router->url('home')?>" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>     Acceuil </a>
                        <a href="<?=$router->url('login')?>" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-envelope"></span> Contact Support </a>
                </div>
            </div>
        </div>
    </div>
</div>