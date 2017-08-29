<?php

if($_GET[action] == "delete") {
			
	if($_GET[id]) {
				
		
		$file = json_decode(file_get_contents("base/data/main/share/$_GET[id].json"), true);

		if($file[file][user_id] == $_SESSION[email] || $user[role] == "admin") {
	
			if($_GET[confirm] == "y") {
									
				$file[file][status] = "delete";
				file_put_contents("base/data/main/share/$_GET[id].json", json_encode($file));
				unlink("base/data/main/user/$_SESSION[email]/$_GET[id].json");
				echo "<meta http-equiv=\"refresh\" content=\"5;URL='http://".config('site.domain')."/menu.php?s=share'\" />
				<div class=\"file-row\" style=\"padding: 10px;margin-bottom: 1px;\">
				File deleted. Please wait until you are redirected or <a href=\"/menu.php?s=share\">click here</a>.</div>";

			} else {
							
				echo "
					<div class=\"alert alert-danger\" role=\"alert\">Are you sure you want to delete this file?</div>
					<a class=\"btn btn-default\" href=\"/menu.php?s=share\">Cancel</a> 
					<a class=\"btn btn-primary\" href=\"/menu.php?s=share&action=delete&id=$_GET[id]&confirm=y\">Delete</a>";
						
			}
					
		} else echo "<div style=\"padding: 50px 15px;text-align: center;background-color: #fff;\"><h2>Permission denied<h2></div>";
				
	}
			
} else if($_GET[action] == "edit") {
			
	if($_GET[id]) {
				
		$file = json_decode(file_get_contents("base/data/main/share/$_GET[id].json"), true);
		
		if($file[file][user_id] == $_SESSION[email] || $user[role] == "admin") {

			echo "
				<div class=\"page-header\">
					<h1>Edit</h1>
				</div>";

			if(isset($_POST[up])) {

				$file[file][title] = htmlspecialchars($_POST[title], ENT_QUOTES);
				$file[file][file_id] = htmlspecialchars($_POST[id], ENT_QUOTES);
				$file[file][poster] = htmlspecialchars($_POST[poster], ENT_QUOTES);
				file_put_contents("base/data/main/share/$_GET[id].json", json_encode($file,true));
				echo "sukses, <a href=\"/menu.php?s=share&action=edit&id=$_GET[id]\">click here</a>";

			} else {
					
				echo "
					<form method=\"POST\">
						<div class=\"form-group\">
						Title
						<input class=\"form-control\" name=\"title\"  value=\"".$file[file][title]."\" maxlength=\"120\" type=\"text\" required>
						</div>
						<div class=\"form-group\">
						File ID
						<input class=\"form-control\" name=\"id\"  value=\"".$file[file][file_id]."\" maxlength=\"120\" type=\"text\" required>
						</div>
						<div class=\"form-group\">
						Poster
						<input class=\"form-control\" name=\"poster\"  value=\"".$file[file][poster]."\" maxlength=\"120\" type=\"text\" placeholder=\"https://domain.com/img/gambar.png\">
						</div>
						<button class=\"btn btn-primary\" type=\"submit\" name=\"up\">Update</button> 
						<a class=\"btn btn-danger\" href=\"/menu.php?s=share&action=delete&id=$_GET[id]\">Delete</a>
						<a class=\"btn btn-default\" href=\"/menu.php?s=share\">Cancel</a>
					</form>";
			
			}
		
		} else echo "<div style=\"padding: 50px 15px;text-align: center;background-color: #fff;\"><h2>Permission denied<h2></div>";
				
	}
			
} else {
	
	echo "
		<div class=\"alert alert-info\" role=\"alert\">
			File yang kamu share akan ada disini.
		</div>";

	$file = glob("base/data/main/user/$_SESSION[email]/*.json");

	usort($file, function ($a, $b) {
		return filemtime($b) - filemtime($a);
	});
				
	if(isset($_GET[page])) {
		
		$noPage = $_GET[page];
	} else $noPage = 1;
				
	$limit = 10;
	$offset = ($noPage -1) * $limit;
	$total_items = count($file);
	$jumPage = ceil($total_items/$limit);

	$files_filter  = array_slice($file, $offset,$limit);

	if(!empty($files_filter)) {

		echo "
			<table class=\"table table-hover\">
				<thead>
					<tr>
						<th>File Name</th>
						<th>File Size</th>
						<th>Date</th>
						<th style=\"text-align:center;\">Action</th>
					</tr>
				</thead>
			<tbody>";

		foreach ($files_filter as $files) {
						
			$kill = explode("$_SESSION[email]/",$files);
			$content = file_get_contents("base/data/main/share/$kill[1]");
			$data = json_decode($content, true);
			
			if(!empty($data[file][title])) {
				
				$title = $data[file][title];
				$shareid = $data[file][share_id];
				
			} else {
			
				$title = "This item may violate our Terms of Service";
				
			}
				
			echo "
				<tr>
					<td><a href=\"/file/$shareid/code/true\">$title</a></td>
					<td>".formatBytes($data[file][size])."</td>
					<td>".$data[file][date]."</td>
					<td style=\"text-align:center;\">
						<button class=\"zec\" style=\"border: none;background: none;padding: 0;\" data-clipboard-demo=\"\" data-clipboard-action=\"copy\" data-clipboard-text=\"http://".config('site.domain')."/file/$shareid\"><span class=\"glyphicon glyphicon-link\" aria-hidden=\"true\"></span></button>
						<a style=\"color:#000\" href=\"/menu.php?s=share&action=edit&id=$shareid\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></a>
						<a style=\"color:#000\" href=\"/menu.php?s=share&action=delete&id=$shareid\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></a>
					</td>
				</tr>";
					
		}

		echo "</tbody></table>";
			
	} else {

		echo "<div class=\"file-row\" style=\"padding: 10px;margin-bottom: 1px;\">Not Found!</div>";

	}

		echo "<nav aria-label=\"Page navigation\"><ul class=\"pager\">";

		if ($noPage > 1) {
					
			if($noPage-1 == 1) {
						
				$apage=null;
					
			} else {
					
				$apage = "&page=".($noPage-1);
					
			}
					
			echo  "<li class=\"previous\"><a href=\"/menu.php?s=share$apage\"><span aria-hidden=\"true\">&larr;</span> Older</a></li>";
					
		} else {
					
			echo "<li class=\"previous disabled\"><a href=\"#\"><span aria-hidden=\"true\">&larr;</span> Older</a></li>";
					
		}

		if(!isset($_GET[page])) {
				
			$e = 1;
					
		} else {
					
			$e = $_GET[page];
					
		}

		echo "<span style=\"display: inline-block;padding: 5px;\">$e of $jumPage</span>";

		if ($noPage < $jumPage) { 

			echo "<li class=\"next\"><a href=\"/menu.php?s=share&page=".($noPage+1)."\">Newer <span aria-hidden=\"true\">&rarr;</span></a></li>";

		} else {

			echo "<li class=\"next disabled\"><a href=\"#\">Newer <span aria-hidden=\"true\">&rarr;</span></a></li>";

		}

		echo "</ul></div>";
				
}