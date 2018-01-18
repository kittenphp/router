<?php


namespace kitten\component\router;


use Symfony\Component\Routing\Route;

class RouteBridge extends Route
{
    /** @var RouteNode  */
    protected $routeNode;

    /**
     * RouteBridge constructor.
     * @param RouteNode $node
     */
    public function __construct(RouteNode $node)
    {
        $node->freeze();
        $path=$node->getPattern();
        $defaults=array();
        $requirements=$node->getPatternWhere();
        $options=array();
        $host='';
        $schemes=array();
        $methods=$node->getMethods();
        $condition='';
        parent::__construct($path, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
        $this->routeNode=$node;
    }

    /**
     * @return RouteNode
     */
    public function getRouteNode():RouteNode
    {
        return $this->routeNode;
    }
}