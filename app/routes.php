<?php

// Home page
$app->get('/', "Controller\NameController::NameAction")->bind('link');
