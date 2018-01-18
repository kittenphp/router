<?php


namespace kitten\component\router;

class RouteCollector
{
    /** @var RouteNode[] */
    protected $routeNodes=[];


    /** @var RouteGroup[] */
    protected $routeGroups=[];

    /**
     * @param string $pattern
     * @param callable $callable
     * @return RouteGroup
     */
    public function group(string $pattern,callable $callable)
    {
        $group=new RouteGroup($pattern,$this->routeGroups);
        array_push($this->routeGroups,$group);
        $callable($this);
        array_pop($this->routeGroups);
        return $group;
    }

    /**
     * @param array $methods
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    protected function map(array $methods,string $pattern, $callable)
    {
        $node=new RouteNode($pattern,$callable,$methods,$this->routeGroups);
        $name='_route_'.count($this->routeNodes);
        $node->setName($name);
        $this->routeNodes[]=$node;
        return $node;
    }

    /**
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    public function get(string $pattern, $callable)
    {
        return $this->map(['GET'], $pattern, $callable);
    }

    /**
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    public function post(string $pattern, $callable)
    {
        return $this->map(['POST'], $pattern, $callable);
    }

    /**
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    public function put(string $pattern, $callable)
    {
        return $this->map(['PUT'], $pattern, $callable);
    }

    /**
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    public function patch(string $pattern, $callable)
    {
        return $this->map(['PATCH'], $pattern, $callable);
    }

    /**
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    public function delete(string $pattern, $callable)
    {
        return $this->map(['DELETE'], $pattern, $callable);
    }

    /**
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    public function options(string $pattern, $callable)
    {
        return $this->map(['OPTIONS'], $pattern, $callable);
    }

    /**
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    public function any(string $pattern, $callable)
    {
        return $this->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $pattern, $callable);
    }

    /**
     * @param string[] $methods
     * @param string $pattern
     * @param $callable
     * @return RouteNode
     */
    public function match(array $methods,string $pattern, $callable){
        return $this->map($methods, $pattern, $callable);
    }

    /**
     * @return RouteNode[]
     */
    public function getRouteNodes()
    {
        return $this->routeNodes;
    }
}