<?
$folders = array();
$files = array();
$blacklist = array(".", "..", "images", "php", "html", "css");

$imagesPath = "images";
$cssFile = "style.css";
$headerFile = "header.html";

$defaultImagesPath = "http://cynicmusic.com/assets/fileviewer";
$defaultCssFile = "http://cynicmusic.com/assets/fileviewer/style.css";

if (!file_exists($cssFile)) $cssFile = $defaultCssFile;
if (!file_exists($imagesPath)) $imagesPath = $defaultImagesPath;

$defaultHeader = <<<EOD
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="$cssFile">
		<title>::The Cynic Project | cynicmusic.com::</title>
		<meta name="robots" content="noindex, nofollow">
    </head>
EOD;

function printHeader() {
	global $headerFile, $defaultHeader;
	if (file_exists($headerFile)) {
		print file_get_contents($headerFile);
	} else {
		print $defaultHeader;
	}
}

function formatBytes($size, $precision = 2) {
	if ($size == 0) return "0KB";
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'G', 'T');
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

function template($file, $picture) {
	global $imagesPath;

	if (!is_dir($file)) {
		$fileSize = formatBytes(filesize($file));
	}

$entry = <<<EOD
<div class="fileentry">
	<a href="$file">
		<img src="$imagesPath/$picture">$file</a>
		<span class="filesize">$fileSize</span>
</div>
EOD;

return $entry;
}

function printFolders() {
	global $folders;
	foreach ($folders as $folder) print $folder;
}

function printFiles() {
	global $files;
	foreach ($files as $file) print $file;
}

function buildFilesAndFolders() {
	global $files, $folders, $blacklist;

	$handle=opendir(".");
	while ($file = readdir($handle)) {
		$ext = pathinfo($file)['extension'];
		
		foreach ($blacklist as $b) {
			if ($file == $b || $ext == $b) {
				continue(2);
			}
		}

		$image = "blank.png";

		if (is_dir($file)) { 
			$image = "folder.png";
			array_push($folders, template($file, $image));
			continue;
		}

		else {
			if 		($ext == "mp3") { $image = "mp3.png"; }
			elseif 	($ext == "zip")	{ $image = "zip.png"; }
			elseif 	($ext == "rar")	{ $image = "zip.png"; }
			elseif 	($ext == "wav")	{ $image = "wav.png"; }
			elseif 	($ext == "txt")	{ $image = "txt.png"; }
			elseif 	($ext == "doc")	{ $image = "txt.png"; }
			elseif 	($ext == "gif")	{ $image = "pic.png"; }
			elseif 	($ext == "jpg")	{ $image = "pic.png"; }
		}

		array_push($files, template($file, $image));
     }
     closedir($handle);
}

?>
	<body>
		<?
			printHeader();
			buildFilesAndFolders();
			printFolders();
			printFiles();
		?>
	</body>
</html>