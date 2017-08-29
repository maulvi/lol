<?php

$user = json_decode(file_get_contents("base/data/user/$_SESSION[email].json"), true);

if(isset($_POST[account])) {

	$string = trim($_POST[name]," \t.");

	if(!empty($string)) {
		
		$name = htmlspecialchars(substr($string,0,20), ENT_QUOTES);

	} else {

		$name = "Bencong";

	}

	$user[name] = $name;
	file_put_contents("base/data/user/$_SESSION[email].json", json_encode($user,true));

	echo "
		<meta http-equiv=\"refresh\" content=\"5;URL='http://".config('site.domain')."/menu.php?s=account'\" />
		<div class=\"alert alert-info\" role=\"alert\">Update successfully. Please wait until you are redirected or <a href=\"http://".config('site.domain')."/menu.php?s=account\">click here</a>.
		</div>";


} else {
	echo "
		<form method=\"POST\">

			<div class=\"page-header\">
				<h1>Account</h1>
			</div>

			<div class=\"form-group\">
				<b>Name :</b>
				<input class=\"form-control\" name=\"name\" value=\"$user[name]\" type=\"text\">
			</div>

			<div class=\"form-group\">
				<b>Email :</b>
				<input class=\"form-control\" value=\"$_SESSION[email]\" type=\"text\" disabled>
			</div>

			<div class=\"form-group\">
				<b>Type :</b>
				<input class=\"form-control\" value=\"$user[role]\" type=\"text\" disabled>
			</div>

			<button class=\"btn btn-primary\" type=\"submit\" name=\"account\">Update</button>
		</form>
		";
}