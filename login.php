<?php

/*

Masalah error header information.
hapus aja header("HTTP/1.1 301 Moved Permanently");
ganti header("Location: "); mengunakan meta refres atau windows location. { cari gigoogle cari mengalihkan halaman }

*/

session_start();

include "system/function.php";


if($_SESSION[email]) {
	
	if($_GET[action] == "logout") {
		
		session_unset();
		session_destroy();
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://".config('site.domain')."");
		exit;
		
	}
	
} else if($_GET[code]) {

	$token = token("code",$_GET[code]);
			
	if($token[access_token]) {
			
		$user = file_get_contents("https://www.googleapis.com/oauth2/v3/userinfo?alt=json&access_token=$token[access_token]");
		$userinfo = json_decode($user, true);
		
		if($userinfo[email]) {
			
			if(file_exists("base/data/user/$userinfo[email].json")){
			
				$update = json_decode(file_get_contents("base/data/user/$userinfo[email].json"), true);
				$update[refresh_token] = $token[refresh_token];
				file_put_contents("base/data/user/$userinfo[email].json", json_encode($update,true));
			
			} else {
			
				$format = array(role => "member", email => $userinfo[email], name => $userinfo[name],picture => $userinfo[picture], access_token => $token[access_token], refresh_token => $token[refresh_token]);
				file_put_contents("base/data/user/$userinfo[email].json", json_encode($format));
			
			}
			
			$_SESSION[email] = $userinfo[email];
			
			if($_SESSION[referer]) {
		
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: $_SESSION[referer]");
				exit;
				
			} else {
				
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: http://".config('site.domain')."/menu.php?s=account");
				exit;
				
			}
			
		} else echo "gagal";
		
	}

} else {

	if($_GET[r]){
	
		$_SESSION[referer] = $_GET[r];
	
	}
	
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: https://accounts.google.com/o/oauth2/auth?access_type=offline&prompt=consent&response_type=code&client_id=".config('drive.client.id')."&redirect_uri=".config('drive.redirect.uris')."&scope=https%3a%2f%2fwww.googleapis.com%2fauth%2fuserinfo.profile+email+https%3a%2f%2fwww.googleapis.com%2fauth%2fdrive+https%3a%2f%2fwww.googleapis.com%2fauth%2fdrive.appdata+https%3a%2f%2fwww.googleapis.com%2fauth%2fdrive.file+https%3a%2f%2fwww.googleapis.com%2fauth%2fdrive.photos.readonly");
	exit;
	
}