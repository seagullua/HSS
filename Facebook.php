<?php



class Facebook
{
	public static function isValidUser($user_id, $token)
	{
		$c = curl_init("https://graph.facebook.com/me?access_token=$token");

		// necessary so CURL doesn't dump the results on your page
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		$result = curl_exec($c);
		curl_close ($c);

		$facebookUser = json_decode($result, true);
		
		if(isset($facebookUser['id']) && $facebookUser['id'] == $user_id)
			return true;
		return false;
	
	}
}