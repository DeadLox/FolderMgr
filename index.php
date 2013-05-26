<?php
require_once('Folder.class.php');
require_once('File.class.php');

$defautPath = "First level";
$currentFolder = Folder::getCurrentFolder($defautPath);
// echo '<pre>';
// var_dump($currentFolder);
// echo '</pre>';
?>
<!DOCTYPE HTML>
<html lang="fr-FR">
<head>
	<meta charset="UTF-8">
	<title>FileMgr</title>
</head>
<body>
	<h1><?php echo $currentFolder->getName(); ?></h2>
	<?php
	if ($currentFolder->hasLastFolder()) { ?>
		Retour <a href="<?php echo $currentFolder->getUrlLastFolder(); ?>"><?php echo $currentFolder->getLastFolder(); ?></a>
	<?php } ?>
	<?php
	$folders = $currentFolder->getFolders();
	if (sizeof($folders) > 0) { ?>
		<ul>
		<?php
		foreach ($folders as $key => $folder) { ?>
			<li><a href="?path=<?php echo $folder->getUrlPath(); ?>">[FOLDER] <?php echo $folder->getName(); ?></a></li>
		<?php } ?>
		</ul>
	<?php } ?>
	<?php
	$files = $currentFolder->getFiles();
	if (sizeof($files) > 0) { ?>
		<ul>
		<?php
		foreach ($files as $key => $file) { ?>
			<li><a href="<?php echo $file->getPath(); ?>">[FILE] <?php echo $file->getName().' '.$file->getFilesize().' '.$file->getSizeUnit(); ?></a></li>
		<?php } ?>
		</ul>
	<?php } ?>
</body>
</html>