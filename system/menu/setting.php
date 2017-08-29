<?php

if($user[role] == "admin") {
		
	$setting = json_decode(file_get_contents("base/data/setting/config.json"), true);
	$page = json_decode(file_get_contents("base/data/setting/page.json"),true);
			
	if(isset($_POST[drive])) {
					
		$setting['drive.client.id'] = htmlspecialchars($_POST[dci], ENT_QUOTES);
		$setting['drive.client.secret'] = htmlspecialchars($_POST[dcs], ENT_QUOTES);
		$setting['drive.redirect.uris'] = htmlspecialchars($_POST[dru], ENT_QUOTES);
				
		file_put_contents("base/data/setting/config.json", json_encode($setting,true));
							
		echo "
			<meta http-equiv=\"refresh\" content=\"5;URL='http://".config('site.domain')."/menu.php?s=setting&a=oauth'\" />
			<div class=\"alert alert-info\" role=\"alert\">Update successfully. Please wait until you are redirected or <a href=\"http://".config('site.domain')."/menu.php?s=setting&a=oauth\">click here</a>.
			</div>";

					
	} else if(isset($_POST[site])) {
					
		$setting['site.domain'] = htmlspecialchars($_POST[domain], ENT_QUOTES);
		$setting['site.title'] = htmlspecialchars($_POST[title], ENT_QUOTES);
		$setting['site.description'] = htmlspecialchars($_POST[description], ENT_QUOTES);
		$setting['site.tag'] = htmlspecialchars($_POST[tag], ENT_QUOTES);
		$setting['site.copyright'] = htmlspecialchars($_POST[copyright], ENT_QUOTES);
		$setting['google.webmaster.id'] = htmlspecialchars($_POST[gwi], ENT_QUOTES);
		$setting['google.analytics.id'] = htmlspecialchars($_POST[gai], ENT_QUOTES);

		file_put_contents("base/data/setting/config.json", json_encode($setting,true));

		echo "
			<meta http-equiv=\"refresh\" content=\"5;URL='http://".config('site.domain')."/menu.php?s=setting&a=site'\" />
			<div class=\"alert alert-info\" role=\"alert\">
			Update successfully. Please wait until you are redirected or <a href=\"http://".config('site.domain')."/menu.php?s=setting&a=site\">click here</a>.
			</div>";
					
	} else if(isset($_POST[banner])) {
					
		$setting['banner.1'] = $_POST[b1];
		$setting['banner.2'] = $_POST[b2];

		file_put_contents("base/data/setting/config.json", json_encode($setting,true));

		echo "
			<meta http-equiv=\"refresh\" content=\"5;URL='http://".config('site.domain')."/menu.php?s=setting&a=ads&t=banner'\" />
			<div class=\"alert alert-info\" role=\"alert\">
			Update successfully. Please wait until you are redirected or <a href=\"http://".config('site.domain')."/menu.php?s=setting&a=ads&t=banner\">click here</a>.
			</div>";
					
	} else if(isset($_POST[headtag])) {
					
		$setting['head.tag'] = $_POST[ht];

		file_put_contents("base/data/setting/config.json", json_encode($setting,true));

		echo "
			<meta http-equiv=\"refresh\" content=\"5;URL='http://".config('site.domain')."/menu.php?s=setting&a=ads&t=headtag'\" />
			<div class=\"alert alert-info\" role=\"alert\">
			Update successfully. Please wait until you are redirected or <a href=\"http://".config('site.domain')."/menu.php?s=setting&a=ads&t=headtag\">click here</a>.
			</div>";
					
	} else if(isset($_POST[page])) {

		$page[title] = htmlspecialchars($_POST[title], ENT_QUOTES);
		$page[content] = $_POST[content];

		file_put_contents("base/data/setting/page.json", json_encode($page,true));

		echo "
			<meta http-equiv=\"refresh\" content=\"5;URL='http://".config('site.domain')."/menu.php?s=setting&a=page'\" />
			<div class=\"alert alert-info\" role=\"alert\">
			Update successfully. Please wait until you are redirected or <a href=\"http://".config('site.domain')."/menu.php?s=setting&a=page\">click here</a>.
			</div>";

	}

	if($_GET[a] == "site") {
			
		echo "
			<div class=\"page-header\">
				<h1>Site Setting</h1>
			</div>

			<form method=\"POST\">
					
				<div class=\"form-group\">
					site.domain
					<input class=\"form-control\" name=\"domain\"  value=\"".$setting['site.domain']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					site.title
					<input class=\"form-control\" name=\"title\"  value=\"".$setting['site.title']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					site.tag
					<input class=\"form-control\" name=\"tag\"  value=\"".$setting['site.tag']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					site.description
					<input class=\"form-control\" name=\"description\"  value=\"".$setting['site.description']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					site.copyright
					<input class=\"form-control\" name=\"copyright\"  value=\"".$setting['site.copyright']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					google.webmaster.id
					<input class=\"form-control\" name=\"gwi\"  value=\"".$setting['google.webmaster.id']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					google.analytics.id
					<input class=\"form-control\" name=\"gai\"  value=\"".$setting['google.analytics.id']."\" type=\"text\">
				</div>

				<button class=\"btn btn-primary\" type=\"submit\" name=\"site\">Update</button>
			</form>";

	} else if($_GET[a] == "oauth") {

		echo "
			<div class=\"page-header\">
				<h1>Google OAuth</h1>
			</div>
			
			<form method=\"POST\">

				<div class=\"form-group\">
					drive.client.id
					<input class=\"form-control\" name=\"dci\"  value=\"".$setting['drive.client.id']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					drive.client.secret
					<input class=\"form-control\" name=\"dcs\"  value=\"".$setting['drive.client.secret']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					drive.redirect.uris
					<input class=\"form-control\" name=\"dru\"  value=\"".$setting['drive.redirect.uris']."\" type=\"text\">
				</div>

				<button class=\"btn btn-primary\" type=\"submit\" name=\"drive\">Update</button>

			</form>";

	} else if($_GET[a] == "ads" && $_GET[t] == "banner") {

		echo "
			<div class=\"page-header\">
				<h1>Banner</h1>
			</div>

			<form method=\"POST\">

				<div class=\"form-group\">
					banner.1
					<textarea rows=\"4\" cols=\"5\" class=\"form-control\" name=\"b1\">".$setting['banner.1']."</textarea>
				</div>

				<div class=\"form-group\">
					banner.2
					<textarea rows=\"4\" cols=\"5\" class=\"form-control\" name=\"b2\">".$setting['banner.2']."</textarea>
				</div>

				<button class=\"btn btn-primary\" type=\"submit\" name=\"banner\">Update</button>

			</form>";
		
	} else if($_GET[a] == "ads" && $_GET[t] == "headtag") {

		echo "
			<div class=\"page-header\">
				<h1>Head Tag</h1>
			</div>

			<form method=\"POST\">

				<div class=\"form-group\">
					head.tag
					<textarea rows=\"4\" cols=\"5\" class=\"form-control\" name=\"ht\">".$setting['head.tag']."</textarea>
				</div>

				<button class=\"btn btn-primary\" type=\"submit\" name=\"headtag\">Update</button>

			</form>";
		
	} else if($_GET[a] == "page") {

		echo "
			<div class=\"page-header\">
				<h1>Index Page</h1>
			</div>

			<form method=\"POST\">

				<div class=\"form-group\">
					title
					<input class=\"form-control\" name=\"title\"  value=\"".$page['title']."\" type=\"text\">
				</div>

				<div class=\"form-group\">
					content
					<textarea rows=\"4\" cols=\"5\" class=\"form-control\" name=\"content\">".$page['content']."</textarea>
				</div>

				<button class=\"btn btn-primary\" type=\"submit\" name=\"page\">Update</button>

			</form>";
		
	}
							

}