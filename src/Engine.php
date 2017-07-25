<?php


namespace AdServer\Engine;


use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Router;

class Engine
{
    static protected $router;
    static protected $container;
    static protected $resolver;

    static public function run(
        ContainerInterface $container,
        Router $router,
        ControllerResolverInterface $resolver
    )
    {
        self::$router = $router;
        self::$container = $container;
        self::$resolver = $resolver;
        $request = Request::createFromGlobals();
        $response = self::matchRequest($request);
        $response->send();
    }

    static public function getContainer() : ContainerInterface
    {
        return self::$container;
    }

    static public function getRouter() : Router
    {
        return self::$router;
    }

    static public function getResolver() : ControllerResolverInterface
    {
        return self::$resolver;
    }

    static public function matchRequest(Request $request): Response
    {
        $request->attributes->add(self::$router->matchRequest($request));
        return self::$resolver->getController($request)($request);
    }
}