<?php

if(isset($_POST[share])) {
			
	if($_POST[id]) {
				
		$id_ = str_replace(array('[',']','"'),'',json_encode($_POST[id], JSON_NUMERIC_CHECK));
		$cut = explode(',', $id_);

		echo "
			<div class=\"page-header\"><h1><span class=\"glyphicon glyphicon-share\" aria-hidden=\"true\"></span> Share Result</h1></div>";
			
		foreach ($cut as $id) {
						
			anyone($id,$users[access_token]);
			
			if($id) {
		
				$files = json_decode(file_get_contents("https://www.googleapis.com/drive/v3/files/$id?fields=fileExtension%2Cmd5Checksum%2CmimeType%2Cname%2Csize%2CthumbnailLink&access_token=$users[access_token]"), true);
				if(isset($_SESSION[email],$id,$files[name],$files[fileExtension],$files[size],$files[mimeType])) {
					activity($_SESSION[email],$id,$files[name],$files[fileExtension],$files[size],$files[mimeType]);
				} else {
					echo "<code>Error</code>";
				}
						
			} else {

				echo "
					<div class=\"file-row\" style=\"padding: 10px;margin-bottom:10px;color:#000\">
						".$copy[error][message]."
					</div>";

			}

		}
			
	} else echo "<div class=\"file-row\" style=\"padding: 10px;margin-bottom:10px;color:#000\">No file selected!</div>";
			
} else if(isset($_POST[delete])) {

	if($_POST[id]) {

		$id_ = str_replace(array('[',']','"'),'',json_encode($_POST[id], JSON_NUMERIC_CHECK));
		$cut = explode(',', $id_);

		foreach ($cut as $id) {

			delete($id,$users[access_token]);

		}

		echo "<meta http-equiv=\"refresh\" content=\"5;URL='/menu.php?s=file'\" />
			<div class=\"file-row\" style=\"padding: 10px;margin-bottom:10px;color:#000\">Successfully delete files. Please wait until you are redirected or <a href=\"/menu.php?s=file\">click here</a>.</div>";

	} else echo "<div class=\"file-row\" style=\"padding: 10px;margin-bottom:10px;color:#000\">No file selected!</div>";

} else {
	
	$list = menu_file($users[access_token],$_GET[next],urlencode($_GET[q]));

	echo "
		<div class=\"alert alert-info\" role=\"alert\">
			Daftar file yang ada di akun google drive kamu, file yang kamu download juga akan masuk sini.
		</div>";

	echo "
		<script type=\"text/javascript\" src=\"//code.jquery.com/jquery-2.0.2.js\"></script>
		<script type=\"text/javascript\">
		//<![CDATA[
			$(function(){
 				$(\"#checkAll\").click(function () {
     				$('input:checkbox').not(this).prop('checked', this.checked);
 				});
			});
		//]]>
		</script>";

	echo "
		<form method=\"get\" action=\"menu.php\">
			<div class=\"col-lg-6\" style=\"padding:0 0 15px\">
				<div class=\"input-group\">
					<input type=\"hidden\" name=\"s\" value=\"file\" class=\"form-control\" placeholder=\"Search for...\">
					<input type=\"text\" name=\"q\" class=\"form-control\" placeholder=\"Search for...\">
					<span class=\"input-group-btn\">
						<button class=\"btn btn-default\" type=\"submit\">Go!</button>
					</span>
				</div>
			</div>
		</form>";

	echo "<form method=\"post\">";

	if($list[files]) {
		
		echo "
			<table class=\"table table-hover\">
				<thead>
					<tr>
						<th><input type=\"checkbox\" id=\"checkAll\"></th>
						<th>File Name</th>
						<th>File Size</th>
					</tr>
				</thead>
			<tbody>";

		foreach($list[files] as $oo) {
					
			if($oo[fileExtension]) {
						
				echo "
					<tr>
						<th scope=\"row\" align=\"center\">
							<input name=\"id[]\" value=\"$oo[id]\" type=\"checkbox\" id=\"checkItem\">
						</th>
						<td>$oo[name]</td>
						<td>".formatBytes($oo[size])."</td>
					</tr>";
						
			}
					
		}

		echo "</tbody></table>";
				
		if($list[nextPageToken]) {

			if($_GET[q]) {

				$link = "menu.php?s=file&q=".urlencode($_GET[q]);

			} else {

				$link = "menu.php?s=file";
			}

			echo "<a href=\"$link&next=$list[nextPageToken]\" class=\"btn btn-success\" type=\"submit\" style=\"margin-bottom: 15px;width: 100%;\">
					Load More</a>";
					
		}

	} else echo "<div class=\"file-row\" style=\"padding: 10px;margin-bottom:10px;color:#000\">File tidak ada!</div>";
			
	echo "<button class=\"btn btn-primary\" type=\"submit\" name=\"share\">Share</button> <button class=\"btn btn-danger\" type=\"submit\" name=\"delete\">Delete</button></form>";

}