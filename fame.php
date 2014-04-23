<?php

require_once 'config.php';

class Fame
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
			trigger_error('Database connection failed: '  . $this->_conn->connect_error, E_USER_ERROR);
		}
    }
	
	public function getAllUsers()
	{
		
		$sql = "SELECT * FROM highscore ORDER BY compare DESC";
		$rs = $this->_conn->query($sql);
		if($rs === false)
		{
			trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->_conn->error, E_USER_ERROR);
			return array();
		}
		$result = array();
		
		while($row = $rs->fetch_array())
		{
			$result[] = $row;
			
		}
		return $result;
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

echo "<h1>Hall of Fame</h1>";
$fame = Fame::getInstance();
$user = $fame->getAllUsers();
//var_dump($user);
foreach ($user as $u)
{
	echo "<a href='https://www.facebook.com/profile.php?id={$u['id']}' target=_blank><img src='http://graph.facebook.com/{$u['id']}/picture' /></a> ";
}