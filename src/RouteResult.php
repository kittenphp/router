<?php


namespace kitten\component\router;


class RouteResult
{
    /** @var array  */
    protected $parameters=[];
    /** @var RouteBridge[] */
    protected $bridges=[];
    /** @var RouteNode */
    protected $currentNode;

    public function __construct(array $parameters,array $bridges)
    {
        $this->parameters=$parameters;
        $this->bridges=$bridges;
    }

    /**
     * @return string
     */
    public function getRouteName(){
        return $this->parameters['_route'];
    }

    /**
     * @return array
     */
    public function getCallParameters(){
        $array=[];
        foreach ($this->parameters as $key=>$value){
            if ($key[0]!='_'){
                $array[$key]=$value;
            }
        }
        return $array;
    }

    /**
     * @return RouteNode
     */
    public function getRouteNode(){
        if (!isset($this->currentNode)){
            $name=$this->getRouteName();
            foreach ($this->bridges as $bridge){
                $node=$bridge->getRouteNode();
                if ($node->getName()==$name){
                    $this->currentNode= $node;
                    break;
                }
            }
        }
        return $this->currentNode;
    }
}