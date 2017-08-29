<?php

function get_zip_files() {

	static $_zip = array();
	
	if (empty($_zip)) {
		
		$_zip = glob('base/backup/*.zip');
	
	}

	return $_zip;
}

function get_backup_files() {
	
	$files = get_zip_files();
	
	if (!empty($files)) {
		
		krsort($files);
		
		foreach ($files as $file) {
			
			$arr = explode('_', $file);
			$replaced = substr($arr[0], 0, strrpos($arr[0], '/')) . '/';
			$name = str_replace($replaced, '', $file);
			$url = $file;
			echo "
				<tr><td><a href=\"$url\">$name</a></td></tr>";
		
		}

	} else {
		
		echo "<tr><td>No available backup!</td></tr>";
    }

}

function backup($source, $destination) {
	
	if (!extension_loaded('zip') || !file_exists($source)) {
		
		return false;
	
	}

	$zip = new ZipArchive();
	
	if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
	
		return false;
	
	}

	$source = str_replace('\\', '/', realpath($source));

	if (is_dir($source) === true) {
	
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

		foreach ($files as $file) {
			
			$file = str_replace('\\', '/', $file);

			if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
				
				continue;
				$file = realpath($file);

			if (is_dir($file) === true) {
				
				$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
			
			} else if (is_file($file) === true) {
				
				$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
			
			}

		}

	} else if (is_file($source) === true) {
		
		$zip->addFromString(basename($source), file_get_contents($source));
	
	}
	
	return $zip->close();

}

if($_GET[create] == "true") {
	
	backup("base/data/","base/backup/".date('d-M-Y H:i:s').".zip");
	echo "<script language=\"JavaScript\">location.href=\"/menu.php?s=backup\";</script>";
}

echo "<table class=\"table table-hover\"><thead><tr><td><h1>Backup</h1></td></tr></thead>";
echo get_backup_files();
echo "</table><a class=\"btn btn-primary\" href=\"/menu.php?s=backup&create=true\">Create</a>";