<?php

namespace Bpeh\NestablePageBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Bpeh\NestablePageBundle\Controller\PageController;
use Bpeh\NestablePageBundle\Controller\PageMetaController;

class ControllerListener
{

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * controller must come in an array
         */
        if (!is_array($controller)) {
            return;
        }
        
        if ($controller[0] instanceof PageController || $controller[0] instanceof PageMetaController) {
            $controller[0]->init();
        }
    }
}
