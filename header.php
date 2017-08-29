<!DOCTYPE html>
<html>
<head>
<?php 

if(!empty($title)) {
	
	echo "<title>$title</title>";
	
} else {
	
	echo "<title>404</title>";
}


echo "
<meta name=\"viewport\" content=\"width=device-width,initial-scale=1\" />
<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" integrity=\"sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u\" crossorigin=\"anonymous\">
<style>.content-wrap{margin:0 auto;padding:2em 15px 5em;max-width:1000px}.page-header{margin:0 0 20px 0;}img {max-width: 100%;height: auto;}</style>
".head_content();

if($_GET[id]) {

	$headtag = config('head.tag');
	if(!empty($headtag)) {

		echo $headtag;

	}
	echo "<meta name=\"robots\" content=\"noindex\" />";

}

echo "
	</head>
	<body>
		<nav class=\"navbar navbar-inverse navbar-static-top\">
			<div class=\"container\">
			<div class=\"navbar-header\">
				<button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-expanded=\"false\" aria-controls=\"navbar\">
					<span class=\"sr-only\">Toggle navigation</span>
					<span class=\"icon-bar\"></span>
					<span class=\"icon-bar\"></span>
					<span class=\"icon-bar\"></span>
				</button>
				<a class=\"navbar-brand\" href=\"http://".config('site.domain')."/\">".config('site.tag')."</a>
			</div>
			<div id=\"navbar\" class=\"navbar-collapse collapse\">";
		
if($_SESSION[email]) {

	$file = file_get_contents("base/data/user/$_SESSION[email].json");
	$user = json_decode($file, true);

	echo "<ul class=\"nav navbar-nav\">";
			
	if($user[role] == "admin") {
				
		echo "
			<li class=\"dropdown\">
				<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-cog\" aria-hidden=\"true\"></span> Setting <span class=\"caret\"></span></a>
				<ul class=\"dropdown-menu\">
					<li><a href=\"/menu.php?s=setting&a=site\">Site</a></li>
					<li><a href=\"/menu.php?s=setting&a=oauth\">OAuth 2.0</a></li>
					<li role=\"separator\" class=\"divider\"></li>
					<li class=\"dropdown-header\">Page</li>
					<li><a href=\"/menu.php?s=setting&a=page\">Index</a></li>
					<li role=\"separator\" class=\"divider\"></li>
					<li class=\"dropdown-header\">Advertisement</li>
					<li><a href=\"/menu.php?s=setting&a=ads&t=banner\">Banner</a></li>
					<li><a href=\"/menu.php?s=setting&a=ads&t=headtag\">Head Tag</a></li>
				</ul>
			</li>

			<li>
				<a href=\"/menu.php?s=backup\"><span class=\"glyphicon glyphicon-floppy-save\" aria-hidden=\"true\"></span> Backup</a>
			</li>";
				
	}
			
	echo "
		<li>
			<a href=\"/menu.php?s=share\"><span class=\"glyphicon glyphicon-link\" aria-hidden=\"true\"></span> Share</a>
		</li>
		<li>
			<a href=\"/menu.php?s=file\"><span class=\"glyphicon glyphicon-file\" aria-hidden=\"true\"></span> File</a>
		</li>
		<li>
			<a href=\"/menu.php?s=account\"><span class=\"glyphicon glyphicon-user\" aria-hidden=\"true\"></span> Account</a>
		</li>
		<li>
			<a href=\"/login.php?action=logout\"><span class=\"glyphicon glyphicon-log-out\" aria-hidden=\"true\"></span> Logout</a>
		</li>";
			
} else {

	echo "
		<ul class=\"nav navbar-nav navbar-right\">
		<li>
			<a href=\"/login.php\"><span class=\"glyphicon glyphicon-log-in\" aria-hidden=\"true\"></span> Login</a>
		</li>";

}

echo "</ul></div></div></nav>";

?>