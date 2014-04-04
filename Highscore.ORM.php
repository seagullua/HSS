<?php
require_once 'config.php';

class HighscoreORM
{
	private static $_instance = null;
	
	private $_conn = null;
	
    private function __construct()
	{
		global $db;
		$this->_conn = new mysqli($db['server'], $db['user'], $db['password'],$db['database']);
		$this->_conn->select_db($db['database']);
		if ($this->_conn->connect_error) 
		{
			trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
		}
    }

	private function isInt($v)
	{
		return preg_match('/^\d+$/', $v);
	}
	
	public function getScoresForUsers($array)
	{
		$query = '';
		foreach($array as $val)
		{
			if($this->isInt($val))
			{
				if($query)
					$query += ",";
				$query += $val;
			}
		}
		
		$sql = "SELECT * FROM highscore WHERE `id` IN($query)";
		
		$rs = $this->_conn->query($sql);
		if($rs === false)
		{
			trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->_conn->error, E_USER_ERROR);
			return array();
		}
		return $rs->fetch_all(MYSQLI_ASSOC);
	}
	
	public function pushScoreForUser($id, $data, $compare)
	{
		if($this->isInt($id) && $this->isInt($compare))
		{
			$sql = "INSERT INTO highscore (id, data, compare) values($id, ?, $compare) on duplicate key update data=values(data), compare=values(compare)";
			$stmt = $this->_conn->prepare($sql);
			if($stmt === false) 
			{
				trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->_conn->error, E_USER_ERROR);
			}
			$stmt->bind_param('s',$data);
			$stmt->execute();
		}
	}
	
    private function __clone()
	{
    }

    public static function getInstance() 
	{
        
        if (null === self::$_instance) 
		{
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
};