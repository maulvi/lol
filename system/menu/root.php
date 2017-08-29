<div class="container">
<?php


if($_SESSION[email]) {

	$page = json_decode(file_get_contents("base/data/setting/page.json"),true);

	echo "
 		<div class=\"page-header\">
 			<h1>$page[title]</h1>
 		</div>
		<p class=\"lead\">$page[content]</p>";

} else {

	echo "
		<div class=\"jumbotron\">
			<h1>".config('site.title')."</h1>
			<p>
				".config('site.description')."
			</p>
		</div>
		";

}

?>
</div>
