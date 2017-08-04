<?php

namespace src\plugins\webSocket\Application;

use src\plugins\webSocket\Connection;

/**
 * WebSocket Server Application
 * 
 * @author Nico Kaiser <nico@kaiser.me>
 */
abstract class Application
{
    protected static $instances = array();
    
    /**
     * Singleton 
     */
    protected function __construct() { }

    final private function __clone() { }
    
    final public static function getInstance()
    {
        $calledClassName = get_called_class();
        if (!isset(self::$instances[$calledClassName])) {
            self::$instances[$calledClassName] = new $calledClassName();
        }

        return self::$instances[$calledClassName];
    }

    abstract public function onConnect(Connection $connection);

	abstract public function onDisconnect(Connection $connection);

	abstract public function onData($data,Connection  $client);

	// Common methods:
	
	protected function _decodeData($data)
	{
		$decodedData = json_decode($data, true);
		if($decodedData === null)
		{
			return false;
		}
		
		if(isset($decodedData['action'], $decodedData['data']) === false)
		{
			return false;
		}
		
		return $decodedData;
	}
	
	protected function _encodeData($action, $data)
	{
		if(empty($action))
		{
			return false;
		}
		
		$payload = array(
			'action' => $action,
			'data' => $data
		);
		
		return json_encode($payload);
	}
}