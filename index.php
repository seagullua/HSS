<?php

require 'Highscore.ORM.php';
require 'Facebook.php';

function prolongStr($a, $length)
{
	while(strlen($a) < $length)
	{	
		$a = '0'.$a;	
	}
	return $a;
}

//Used to safely compare int64 on 32bit systems
function isLess($a, $b)
{
	$size = max(strlen($a), strlen($b));
	
	$a = prolongStr($a, $size);
	$b = prolongStr($b, $size);
	
	return $a < $b;
}

$request = isset($_REQUEST["r"]) ? $_REQUEST["r"] : '';

if($request == "get")
{
	$ids = explode(',', $_REQUEST['ids']);
	$orm = HighscoreORM::getInstance();
	
	$res = $orm->getScoresForUsers($ids);
	
	echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	echo '<r>';
	foreach($res as $d)
	{
		echo "<user><id>{$d['id']}</id><d>{$d['data']}</d></user>";
	}
	echo '</r>';
}
else if($request == "push")
{
	$id = $_REQUEST['id'];
	$token = $_REQUEST['token'];
	$data = $_REQUEST['data'];
	$compare = $_REQUEST['compare'];
	
	if(Facebook::isValidUser($id, $token))
	{
		echo "*";
		$orm = HighscoreORM::getInstance();
		$info = $orm->getScoresForUsers(array($id));

		$valid = true;
		if(count($info) > 0)
		{
			$info = $info[0];
			if(isLess((string)($compare), (string)($info['compare'])))
				$valid = false;
		}
		
		if($valid)
		{
			$orm->pushScoreForUser($id, $data, $compare);
			echo ".";
		}
	}
	echo "OK";
}