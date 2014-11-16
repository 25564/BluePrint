<?php
namespace BluePrint;
use BluePrint\core\Loader;
use BluePrint\core\Dispatcher;

class Engine {
	protected $vars;
    protected $loader;
    protected $dispatcher;

    public function __construct() {
        $this->vars = array();
        $this->loader = new Loader();
        $this->dispatcher = new Dispatcher();
        $this->init();
    }

    public function __call($name, $params) {
        $callback = $this->dispatcher->get($name);
        if (is_callable($callback)) {
            return $this->dispatcher->run($name, $params);
        }
        $shared = (!empty($params)) ? (bool)$params[0] : true;
        return $this->loader->load($name, $shared);
    }

	public function init() {
        static $initialized = false;
        $self = $this;
        if ($initialized) {
            $this->vars = array();
            $this->loader->reset();
            $this->dispatcher->reset();
        }

        // Register framework methods
        $methods = array(//Standard Methods
            'start'
        );
        foreach ($methods as $name) {
            $this->dispatcher->set($name, array($this, '_'.$name));
        }
        // Default configuration settings
        $this->set("BluePrint.Config.handle_errors", true);
    	
        $initialized = true;
    }

    public function handleErrors($enabled)
    {
        if ($enabled) {
            set_error_handler(array($this, 'handleError'));
            set_exception_handler(array($this, 'handleException'));
        }
        else {
            restore_error_handler();
            restore_exception_handler();
        }
    }

    public function map($name, $callback) {
        if (method_exists($this, $name)) {
            throw new \Exception('Cannot override an existing framework method.');
        }
        $this->dispatcher->set($name, $callback);
    }

    public function register($name, $class, array $params = array(), $callback = null) {
        if (method_exists($this, $name)) {
            throw new \Exception('Cannot override an existing framework method.');
        }
        $this->loader->register($name, $class, $params, $callback);
    }

    //Hook Methods

    public function before($name, $callback) {
        $this->dispatcher->hook($name, 'before', $callback);
    }

    public function after($name, $callback) {
        $this->dispatcher->hook($name, 'after', $callback);
    }


    //Variable Storage

    public function get($key = null) {
        if ($key === null){
        	return $this->vars;
        }
        return isset($this->vars[$key]) ? $this->vars[$key] : null;
    }

    public function set($key, $value = null) {
        if (is_array($key) || is_object($key)) {
            foreach ($key as $k => $v) {
                $this->vars[$k] = $v;
            }
        }
        else {
            $this->vars[$key] = $value;
        }
    }

    public function has($key) {
        return isset($this->vars[$key]);
    }

    public function clear($key = null) {
        if (is_null($key)) {
            $this->vars = array();
        }
        else {
            unset($this->vars[$key]);
        }
    }




    /**********************************
     		Start Standard Methods
    **********************************/
	public function _start() {
        $dispatched = false;
        // Enable output buffering
        ob_start();
        // Enable error handling
        $this->handleErrors($this->get('BluePrint.Config.handle_errors'));
    }
}
?>