<?php

// Home page
$app->get('/', "Controller\HomeController::indexAction")->bind('home');
