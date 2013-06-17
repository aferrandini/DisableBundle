<?php

namespace Ferrandini\Bundle\DisableBundle\Annotations;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Annotations\Annotation\Target;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 * @Service
 */
class Disable implements DisableInterface
{

    /**
     * @var int
     */
    public $statusCode = 503;

    /**
     * @var string
     */
    public $message = 'Service disabled';

    /**
     * @var string
     */
    public $until = "";

    /**
     * @var string
     */
    public $after = null;

    /**
     * @var mixed
     */
    public $redirect = null;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return \DateTime
     */
    public function getUntil()
    {
        if (!empty($this->until)) {
            return new \DateTime($this->until);
        }

        return null;
    }

    /**
     * @return \DateTime
     */
    public function getAfter()
    {
        if (!empty($this->after)) {
            return new \DateTime(($this->after));
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param RouterInterface $router
     * @return callable|null
     */
    public function getResponse(RouterInterface $router)
    {
        if ($this->disableByDateTime() || (empty($this->until) && empty($this->after))) {
            return $this->generateResponse($router);
        }

        return null;
    }

    /**
     * @return bool
     */
    private function disableByDateTime()
    {
        $now = new \DateTime();
        $until = $this->getUntil();
        $after = $this->getAfter();

        if (!empty($until) || !empty($after)) {
            if (
                $until instanceof \DateTime &&
                $after instanceof \DateTime &&
                ($now < $until || $now > $after)
            ) {
                return true;
            } elseif ($until instanceof \DateTime && $now < $until) {
                return true;
            } elseif ($after instanceof \DateTime && $now > $after) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param RouterInterface $router
     * @return callable
     */
    private function generateResponse(RouterInterface $router)
    {
        if (!empty($this->redirect)) {
            return $this->generateRedirectResponse($router);
        } else {
            return $this->generateNormalResponse();
        }
    }

    /**
     * @param RouterInterface $router
     * @return callable
     */
    private function generateRedirectResponse(RouterInterface $router)
    {
        $route = $router->generate($this->redirect);
        return function () use ($route) {
            return new RedirectResponse($route);
        };
    }

    /**
     * @return callable
     */
    private function generateNormalResponse()
    {
        $statusCode = $this->statusCode;
        $message = $this->message;

        return function () use ($statusCode, $message) {
            return new Response($message, $statusCode);
        };
    }

}
