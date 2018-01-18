<?php


namespace kitten\component\router;


use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use  Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RouteTracker
{
    /** @var RouteBridge[] */
    protected $bridges=[];
    /** @var RouteCollection */
    protected $routeCollection;

    /**
     * RouteTracker constructor.
     * @param RouteNode[] $routeNodes
     */
    public function __construct(array $routeNodes)
    {
        $routes = new RouteCollection();
        foreach ($routeNodes as $node){
            $bridge=new RouteBridge($node);
            $this->bridges[]=$bridge;
            $routes->add($bridge->getRouteNode()->getName(), $bridge);
        }
        $this->routeCollection=$routes;
    }

    /**
     * @param string $url
     * @param string $method
     * @return RouteResult|null
     * @throws \Exception
     */
    public function search(string $url,string $method='GET')
    {
        try {
            $routes = $this->routeCollection;
            $context = new RequestContext('', $method);
            $matcher = new UrlMatcher($routes, $context);
            $parameters = $matcher->match($url);
            $routeResult = new RouteResult($parameters,$this->bridges);
            return $routeResult;
        } catch (\Exception $exception) {
            if ($exception instanceof ResourceNotFoundException) {
                return null;
            } else {
                throw $exception;
            }
        }
    }

    /**
     * @param string $routeName
     * @param array $args
     * @return string
     */
    public function generateUrl(string $routeName,array $args=[]){
        $routes=$this->routeCollection;
        $context = new RequestContext('');
        $generator = new UrlGenerator($routes, $context);
        $url = $generator->generate($routeName, $args);
        return $url;
    }
}