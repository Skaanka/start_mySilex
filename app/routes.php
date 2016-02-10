<?php

// Home page
$app->get('/', "StartMySilex\Controller\HomeController::indexAction")->bind('home');
