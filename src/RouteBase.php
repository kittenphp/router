<?php


namespace kitten\component\router;


abstract class RouteBase
{
    /** @var array  */
    protected $middleware = [];
    /** @var string  */
    protected $pattern='';
    /** @var array  */
    protected $patternWhere=[];
    /** @var RouteGroup[] */
    protected $fatherGroups;

    /**
     * RouteBase constructor.
     * @param string $pattern
     * @param array $groups
     */
    public function __construct(string $pattern='',array $groups=[])
    {
        $this->pattern=$pattern;
        $this->fatherGroups=$groups;
    }

    /**
     * @return array
     */
    public function getPatternWhere()
    {
        return $this->patternWhere;
    }

    /**
     * @return RouteGroup[]
     */
    public function getFatherGroups()
    {
        return $this->fatherGroups;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @return array
     */
    public function getMiddleware():array
    {
        return $this->middleware;
    }

    /**
     * @param $middleware
     * @return $this
     */
    public function middleware($middleware){
        if (is_callable($middleware)){
            $this->middleware[]=$middleware;
        }elseif (is_array($middleware)){
            $this->middleware=array_merge($this->middleware,$middleware);
        }else{
            $this->middleware[]=$middleware;
        }
        return $this;
    }

    /**
     * @param string[] $patternWhere
     * @return $this
     */
    public function where(array $patternWhere){
        $this->patternWhere=$patternWhere;
        return $this;
    }
}