<?php
namespace Stauth\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class StauthProtectionListener
{
    /**
     * @var array
     */
    protected $whiteListedRoutes = [
        'stauth_authorization',
        'stauth_protection',
    ];

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param Session $session
     * @return $this
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     * @return $this
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * Handle an incoming request.
     *
     * @param GetResponseEvent $event
     * @return bool
     */
    public function onRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($this->getSession()->get('stauth-last-url') === null) {
            $this->getSession()->set('stauth-last-url', '/');
        }

        $route = $request->get('_route');
        if ( $route === null || in_array($route, $this->whiteListedRoutes, true))
            return true;

        if ( $this->getSession()->get('stauth-authorized'))
            return true;

        $this->getSession()->set('stauth-last-url', $request->getUri());

        $response = new RedirectResponse($this->getRouter()->generate('stauth_protection'));
        $event->setResponse($response);
        return false;
    }
}