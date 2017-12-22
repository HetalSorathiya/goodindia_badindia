<?php

class GTL_Route {

    public function identifyRoute() {
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        //$registry = Zend_Registry::getInstance();
        // legacy router
        /* $route  = new Zend_Controller_Router_Route_Regex(
          '(.+\.php)',
          array(
          'module'     => 'default',
          'controller' => 'legacy',
          'action'     => 'index',
          ),
          array(
          1 => 'script'
          ),
          '%s'
          );

          $router->addRoute('legacy', $route);

         */

        // pages
        $pagesRoute = new Zend_Controller_Router_Route(
                        'page/:page_url/',
                        array(
                            'module' => 'default',
                            'controller' => 'page',
                            'action' => 'index'
                        )
        );
        $router->addRoute('page', $pagesRoute);

        //product details page
        $productRoute = new Zend_Controller_Router_Route(
                        'products/:id/:rpage/*',
                        array(
                            'module' => 'default',
                            'controller' => 'product',
                            'action' => 'details',
                            'rpage' => '1'
                        )
        );

        $router->addRoute('product-details', $productRoute);
    }

}
