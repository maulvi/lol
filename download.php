<?php

error_reporting(0);
session_start();

include "system/function.php";

if($_GET[id]) {

	$share = json_decode(file_get_contents("base/data/main/share/$_GET[id].json"), true);
	$users = json_decode(file_get_contents("base/data/user/".$share[file][user_id].".json"),true);
	
	if(isset($share[file][title])) {

		$title = $share[file][title]." - ".formatBytes($share[file][size]);

	} else {

		$title = "File not found :(";

	}

	include "header.php";

	echo "<div class=\"container\"><div class=\"content-wrap\">";

	if($share[file][poster]) {

		$poster = $share[file][poster];

	}

	if($_GET[code] == true) {

		include "system/menu/code.php";

	} 

	else {

		if(isset($_POST[submit])) {

			$ruse = token("refresh",$_SESSION[email]);
			$copy = copyfile($share[file][file_id],$ruse[access_token]);
			anyone($copy[id],$ruse[access_token]);
							
			if($copy[id]) {
								
				echo "
					<meta http-equiv=\"refresh\" content=\"5;URL='https://docs.google.com/uc?id=$copy[id]'\" />
					<div style=\"padding: 50px 15px;text-align: center;background-color: #fff;\">
						<h2>Thank you for downloading!</h2><br />
						If your download doesn't start automatically within a few seconds, <a href=\"https://docs.google.com/uc?id=$copy[id]\">click here</a>.
					</div>";
								
			} else {

				echo "<div style=\"padding: 50px 15px;text-align: center;background-color: #fff;\">";

				if($copy[error][message] == "The user's Drive storage quota has been exceeded.") {

					echo "Your Google Drive account was full, please delete some files before genereate link download.";

				} else if($copy[error][message] == "User rate limit exceeded.") {

					echo "Your Google Drive account was limited, please login with another Google account.<br>
							The account will be returned to normal after 24 hours.";

				} else if($copy[error][message] == "Forbidden") {

					echo "File cannot be opened :(";
					
				} else {

					echo $copy[error][message];

				}

				echo "</div>";
			
			} 

		}

		else if($share[file][status] == "publish") {
					
			/*echo "
				<div style=\"background-color: #000;display: block;\">
				<div style=\"max-height:400px;max-width:800px;margin-left: auto; margin-right: auto;\">
				";

			if($poster) {

				echo "
					<div style=\"text-align: center;\">
						<img src=\"$poster\" width=\"400px\">
					</div>";

			} else {

				echo "
					<div style=\"color: #fff;text-align: center;padding: 30px 15px;\">
						No preview available
					</div>";

			}

			echo "</div></div>";*/
			$banner = config('banner.1');
			
			if(!empty($banner)) { 

				echo "<div style=\"text-align: center;\">$banner</div>";

			}

			echo "
				<table class=\"table table-striped\">
					<thead><tr><h1>File Informations</h1></tr></thead>
					<tbody>
						<tr valign=\"top\">
							<td width=\"30%\">File Name</td>
								<td>
									".$share[file][title]."
								</td>
						</tr>
						<tr valign=\"top\">
							<td width=\"30%\">File Type</td>
								<td>
									".$share[file][mime]."
								</td>
						</tr>
						<tr valign=\"top\">
							<td width=\"30%\">File Extension</td>
								<td>
									".$share[file][format]."
								</td>
						</tr>
						<tr valign=\"top\">
							<td width=\"30%\">File Size</td>
								<td>
									".formatBytes($share[file][size])."
								</td>
						</tr>
						<tr valign=\"top\">
							<td width=\"30%\">File Owner</td>
								<td>
									$users[name]
								</td>
						</tr>

					</tbody>
				</table>";

			$banner = config('banner.2');
			
			if(!empty($banner)) { 

				echo "<div style=\"text-align: center;padding-bottom: 20px;\">$banner</div>";

			}


			if($_SESSION[email]) {

				echo "
					<div style=\"text-align: center;\">
						<form method=\"post\">
							<button class=\"btn btn-primary\" type=\"submit\" name=\"submit\">Download File</button>
							<button class=\"btn btn-default\" type=\"button\" onclick=\"location.href='http://".config('site.domain')."/file/$_GET[id]/code/true';\">#</button>
						</form>
					</div>";
						
			} else {

				echo "
					<div class=\"alert alert-danger\" role=\"alert\">
						You Need to login first before start download file in this web.
					</div>";

				echo "
					<div style=\"text-align: center;\">
						<form action=\"/login.php\" method=\"GET\">
							<input name=\"r\" value=\"http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]\" type=\"hidden\">
								<button class=\"btn btn-primary\" type=\"submit\">Download File</button>
								<button class=\"btn btn-default\" type=\"button\" onclick=\"location.href='http://".config('site.domain')."/file/$_GET[id]/code/true';\">#</button>
						</form>
					</div>";

			}
					
		} else echo "
					<div style=\"padding: 50px 15px;text-align: center;background-color: #fff;\">
						<h2>File not found :(</h2>
					</div>";

	}


	echo "</div></div>";
	
}

include "footer.php";