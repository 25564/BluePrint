<?php
namespace BluePrint\core;
	
class Dispatcher {
	protected static $events = array();
    protected static $filters = array();

    public function run($name, array $params = array()) {
        $output = '';
        // Run pre-filters
        if (!empty(self::$filters[$name]['before'])) {
            $this->filter(self::$filters[$name]['before'], $params, $output);
        }
        // Run requested method
        $output = $this->execute($this->get($name), $params);
        // Run post-filters
        if (!empty(self::$filters[$name]['after'])) {
            $this->filter(self::$filters[$name]['after'], $params, $output);
        }
        return $output;
    }

	public function set($name, $callback) {
        self::$events[$name] = $callback;
    }

    public function get($name) {
        return isset(self::$events[$name]) ? self::$events[$name] : null;
    }

    public function has($name) {
        return isset(self::$events[$name]);
    }

    public function clear($name = null) {
        if ($name !== null) {
            unset(self::$events[$name]);
            unset(self::$filters[$name]);
        }
        else {
            self::$events = array();
            self::$filters = array();
        }
    }

    public function hook($name, $type, $callback) {
        self::$filters[$name][$type][] = $callback;
    }

    public static function filter($filters, &$params, &$output) {
        $args = array(&$params, &$output);
        foreach ($filters as $callback) {
            $continue = self::execute($callback, $args);
            if ($continue === false) break;
        }
    }

    public static function execute($callback, array &$params = array()) {
        if (is_callable($callback)) {
            return is_array($callback) ?
                self::invokeMethod($callback, $params) :
                self::callFunction($callback, $params);
        }
        else {
            throw new \Exception('Invalid callback specified.');
        }
    }

    public static function callFunction($func, array &$params = array()) {
        switch (count($params)) {
            case 0:
                return $func();
            case 1:
                return $func($params[0]);
            case 2:
                return $func($params[0], $params[1]);
            case 3:
                return $func($params[0], $params[1], $params[2]);
            case 4:
                return $func($params[0], $params[1], $params[2], $params[3]);
            case 5:
                return $func($params[0], $params[1], $params[2], $params[3], $params[4]);
            default:
                return call_user_func_array($func, $params);
        }
    }

    public static function invokeMethod($func, array &$params = array()) {
        list($class, $method) = $func;
		$instance = is_object($class);//Is Static
		if (!empty(self::$filters[$method]['before'])) {
            self::filter(self::$filters[$method]['before'], $params, $output);
        }
        switch (count($params)) {
            case 0:
                return ($instance) ?
                    $class->$method() :
                    $class::$method();
            case 1:
                return ($instance) ?
                    $class->$method($params[0]) :
                    $class::$method($params[0]);
            case 2:
                return ($instance) ?
                    $class->$method($params[0], $params[1]) :
                    $class::$method($params[0], $params[1]);
            case 3:
                return ($instance) ?
                    $class->$method($params[0], $params[1], $params[2]) :
                    $class::$method($params[0], $params[1], $params[2]);
            case 4:
                return ($instance) ?
                    $class->$method($params[0], $params[1], $params[2], $params[3]) :
                    $class::$method($params[0], $params[1], $params[2], $params[3]);
            case 5:
                return ($instance) ?
                    $class->$method($params[0], $params[1], $params[2], $params[3], $params[4]) :
                    $class::$method($params[0], $params[1], $params[2], $params[3], $params[4]);
            default:
                return call_user_func_array($func, $params);
        }
        if (!empty(self::$filters[$method]['after'])) {
            $this->filter(self::$filters[$method]['after'], $params, $output);
        }
    }

	public function reset() {
        $this->events = array();
        self::$filters = array();
    }
}
?>