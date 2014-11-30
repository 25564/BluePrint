<?php
namespace BluePrint\components\router;
class Route { 
	public $pattern;
	public $_config = array();
	private $_methods;
	private $_params;

	public function __construct($resource, array $config = array()){
		$this->_config = $config;
		$this->pattern = $resource;
		$this->_methods = isset($config['methods']) ? $config['methods'] : array('GET', 'POST', 'PUT', 'DELETE');
        $this->_params = isset($config['params']) ? $config['params'] : array();
	}

	public function setParams($newParams){
		$this->_params = $newParams;
	}

	public function addParam($key, $value){
		$this->_params[$key]= $value;
	}

	public function dispatch(){
        $action = explode('::', $this->_config['_controller']);
        $instance = new $action[0];
        call_user_func_array(array($instance, $action[1]), $this->_params);
    }

    public function getMethods(){
    	return $this->_methods;
    }

    public function getConfig(){
    	return $this->_config;
    }
}
?>