<?php
namespace BluePrint\util;

//Collection allows for data to be accessed using Array and Object Notation

class Collection implements \ArrayAccess, \Iterator, \Countable {
	private $_data,
			$_default;
	public function __construct(array $Data = array(), $Default = ""){
		$this->_data = $Data;
		$this->_default = $Default;
	}

	public function __get($key){
		if(isset($this->_data[$key])){
			return $this->_data[$key];
		} else {
			return ($this->_default != "") ? $this->_default : null;
		}
    }

    public function __set($key, $value){
    	$this->_data[$key] = $value;
    }

    public function __isset($key) {
        return isset($this->_data[$key]);
    }

    public function __unset($key) {
        unset($this->_data[$key]);
    }

    public function count() {
        count($this->_data);
    }

    public function get() {
        return $this->_data;
    }

    public function set(array $data) {
        $this->_data = $data;
    }

    public function clear() {
        $this->_data = array();
    }

    //Array Access
    public function offsetSet($key, $value) {
        if (is_null($key)) {
            $this->_data[] = $value;
        } else {
            $this->_data[$key] = $value;
        }
    }

    public function offsetExists($key) {
        return isset($this->_data[$key]);
    }

    public function offsetUnset($key) {
        unset($this->_data[$key]);
    }

    public function offsetGet($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    //Iterator Count
    private $_position = 0;
    function rewind() {
        $this->_position = 0;
    }

    function current() {
        return $this->_data[$this->_position];
    }

    function key() {
        return $this->_position;
    }

    function next() {
        ++$this->_position;
    }

    function valid() {
        return isset($this->_data[$this->_position]);
    }
}
?>