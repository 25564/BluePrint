<?php
namespace BluePrint\components;

class DB implements \Countable {
	private $_table = false,
			$_PDO = false,
			$_query = "",
			$_results,
			$_skipAmount = 0,
			$_returnArray = false;

	public function __construct($TableName, $CustomDB = false){
		$this->_table = $TableName;
		if($CustomDB == false){
			$this->ConnectDB(\BluePrint::get('PDO.host'), \BluePrint::get('PDO.db'), \BluePrint::get('PDO.username'), \BluePrint::get('PDO.password'));
		}
		return $this;
	}

	public function returnArray(){
		$this->_returnArray = true;
		return $this;
	}

	public function ConnectDB($host, $db, $username, $password){
		try {
			$this->_PDO = new \PDO("mysql:host={$host};dbname={$db}", $username, $password);
		} catch(PDOException $e){
			die($e->getMessage());	
		}	
	}

	public function query($sql, $type = "get", $params = array()){
		if($this->_query = $this->_PDO->prepare($sql)){
			$x = 1;
			if(!empty($params)){
				foreach($params as $param){
					$this->_query->bindValue($x, $param);	
					$x++;
				}
			}
			if($this->_query->execute()){
				switch($type){
					case "get":
						if($this->_returnArray == false){
							$this->_results = $this->_query->fetchAll(\PDO::FETCH_OBJ);	
						} else {
							$this->_results = $this->_query->fetchAll();	
						}
					break;
					case "count":
						$this->_results = $this->_query->rowCount();	
					break;
					case "update":
						$this->_results = true;	
					break;
					case "delete":
						$this->_results = true;	
					break;
					case "insert":
						$this->_results = true;	
					break;
					default:
						$this->_results = true;	
					break;

				}
				if(!$type){
					$this->_results = $this->_query->fetchAll(\PDO::FETCH_OBJ);	
				} elseif($type == "count") {
					$this->_results = $this->_query->rowCount();
				} 
				$this->_query = "";			
			}	
		}
		return $this->_results;
	}

	public function insert(array $insertData){
		$data = array("columns"=>array(), "values"=>array());
		foreach($insertData as $column => $value){
			$data["columns"][] = "`" . $column . "`";
			$data["values"][] = "'" . $value . "'";
		}
		return $this->query("INSERT INTO `" . $this->_table . "` (" . implode(",", $data["columns"]) . ") VALUES (" . implode(",", $data["values"]) . ")", "insert");
	}

	public function update(array $updateData){
		$this->WhereParams($key, $operator, $value);
		if($this->hasQuery){
			$update = array();
			foreach($updateData as $column => $value){
				$update[] = $column."='".$value."'";
			}
			return $this->query("UPDATE ".$this->_table." SET ".implode(",", $update)." ".$this->_query, "update");
		} else {
			return false;
		}
	}

	public function delete($key = false, $operator = false, $value = false){
		$this->WhereParams($key, $operator, $value);
		if($this->hasQuery){
			$update = array();
			foreach($data as $column => $value){
				$update[] = $column."='".$value."'";
			}
			return $this->query("UPDATE ".$this->_table." SET ".implode(",", $update)." ".$this->_query, "delete");
		} else {
			return false;
		}
	}

	public function skip($amount){
		$this->_skipAmount = $amount;
	}

	public function get($key = false, $operator = false, $value = false, $amount = false){
		$this->WhereParams($key, $operator, $value);
		if(!$amount){
			$sql = "SELECT * FROM ".$this->_table." ".$this->_query;
		} else {
			$sql = "SELECT * FROM ".$this->_table." ".$this->_query." LIMIT " . $this->_skipAmount . ",".$amount;
		}
		return $this->query($sql, "get");
	}

	public function exists($key = false, $operator = false, $value = false){
		$this->WhereParams($key, $operator, $value);
		$result = $this->count($key, $operator, $value);
		return ($result > 0) ? true : false;
	}

	public function count($key = false, $operator = false, $value = false){
		$this->WhereParams($key, $operator, $value);
		$sql = "SELECT * FROM ".$this->_table." ".$this->_query;
		return $this->query($sql, "count");
	}

	public function where($key, $operator, $value = null){
		if(!$value){
			$value = $operator;
			$operator = "=";
		}
		$this->_query = !$this->hasQuery() ? "WHERE `$key` {$operator} '$value'" : $this->_query .= " AND `$key` {$operator} '$value'";
		return $this;
	}

	private function QueryReady(){
		if($this->_table != false && $this_PDO != false){
			return true;
		}
		return false;
	}

	private function WhereParams($key, $operator, $value){
		if($key != false && $operator != false){
			if($value == false){
				$this->where($key, $operator);
			} else {
				$this->where($key, $operator, $value);
			}
		}
	}

	private function hasQuery() { 
		return (isset($this->_query) && $this->_query != "") ? true : false;
	}
}
?>
