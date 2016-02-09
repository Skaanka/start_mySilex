<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController {

    /**
     * Home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) {
        $myVar = $app['dao.name']->findAll();
        return $app['twig']->render('index.html.twig', array('myVar' => $myVar));
    }

}
