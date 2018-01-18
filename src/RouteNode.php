<?php


namespace kitten\component\router;


class RouteNode extends RouteBase
{
    /** @var string[] */
    protected $methods = [];
    /** @var string  */
    protected $name='';
    /** @var bool  */
    private $freeze = false;
    /** @var mixed  */
    protected $callable;

    /**
     * RouteNode constructor.
     * @param string $pattern
     * @param mixed $callable
     * @param array $methods
     * @param array $groups
     */
    public function __construct(string $pattern = '',$callable,array $methods=['GET'], array $groups = [])
    {
        parent::__construct($pattern, $groups);
        $this->methods=$methods;
        $this->callable=$callable;
    }

    public function freeze()
    {
        if ($this->freeze) {
            return;
        }
        $groupMiddleware = [];
        $pattern='';
        foreach ($this->getFatherGroups() as $group) {
            $groupMiddleware = array_merge($group->getMiddleware(), $groupMiddleware);
            $pattern=$pattern.$group->getPattern();
        }
        $this->middleware = array_merge($this->middleware, $groupMiddleware);
        $this->pattern=$pattern.$this->pattern;
        $this->freeze=true;
    }

    /**
     * @return mixed
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @param $callable
     */
    public function setCallable($callable)
    {
        $this->callable = $callable;
    }
    /**
     * @return string[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }
}