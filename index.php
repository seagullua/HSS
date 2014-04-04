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

function isLess($a, $b)
{
	$size = max(strlen($a), strlen($b));
	
	$a = prolongStr($a, $size);
	$b = prolongStr($b, $size);
	
	return $a < $b;
}

$request = $_REQUEST["r"];

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





//if(Facebook::isValidUser(1364926035, "CAAJp19HZBwBsBAN8L0ZBRNFTRYqSDDc3SdKZBAkQHFYavPh0XkMr0bUZCRpFhTSKYnQnmjrPUgBSuiqYZCkR1X2qAHWg77FJekRrGCtsbvQUd8XvezErS6uVxJOXE2U4nqCwgcQg4ufUmyOL71ITpTvJxKWB7ddGiBgMkoWQrCwDjXzWZCZCrNTN5qLBhqjNddftL7LC0iiCGR2ZAYVaW96x7bu7YepD05PPFbvHfLQmMAZDZD"))
//echo "OK";
//else
//	echo "F";