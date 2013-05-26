<?php

namespace Ferrandini\Bundle\DisableBundle\Annotations\Driver;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Ferrandini\Bundle\DisableBundle\Annotations\Disable;
use Symfony\Component\Routing\RouterInterface;

class AnnotationDriver
{
    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router|\Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param Reader $reader
     * @param Router $router
     */
    public function __construct(Reader $reader, RouterInterface $router)
    {
        // get annotations reader
        $this->reader = $reader;

        // get router for redirects
        $this->router = $router;
    }

    /**
     * This event will fire during any controller call
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            //return if no controller
            return;
        }

        // get controller object
        $object = new \ReflectionObject($controller[0]);
        foreach ($this->reader->getClassAnnotations($object) as $configuration) {
            if ($configuration instanceof Disable) {
                $newController = $configuration->getResponse($this->router);
                if (is_callable($newController)) {
                    $event->setController($newController);
                    return;
                }
            }
        }

        // get controller method
        $method = $object->getMethod($controller[1]);
        foreach ($this->reader->getMethodAnnotations($method) as $configuration) {
            if ($configuration instanceof Disable) {
                $newController = $configuration->getResponse($this->router);
                if (is_callable($newController)) {
                    $event->setController($newController);
                    return;
                }
            }
        }
    }
}