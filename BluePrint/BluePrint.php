<?php
class BluePrint {	
	private static $engine;
   
    // Don't allow object instantiation
    private function __construct() {}
    private function __destruct() {}
    private function __clone() {}

    public static function __callStatic($name, $params) {
    	static $initialized = false;
        if (!$initialized) {
            require_once __DIR__.'/autoload.php';
            self::$engine = new \BluePrint\Engine();
            $initialized = true;
        }
        return \BluePrint\core\Dispatcher::invokeMethod(array(self::$engine, $name), $params);
    }

    public static function Plugin($name){
    	return self::$engine->loader->plugins[$name];
    }

    public static function app() {
        return self::$engine;
    }


}
?>