<?php
require_once('Folder.class.php');
require_once('File.class.php');

$defautPath = "classements";
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
	<?php
	if ($currentFolder->hasLastFolder()) { ?>
		<a href="<?php echo $currentFolder->getUrlLastFolder(); ?>">Retour <?php echo $currentFolder->getLastFolder(); ?></a>
	<?php } ?>
	<?php
	$folders = $currentFolder->getFolders();
	if (sizeof($folders) > 0) { ?>
		<ul>
		<?php
		foreach ($folders as $key => $folder) { ?>
			<li><a href="?path=<?php echo $folder->getUrlPath(); ?>"><?php echo $folder->getName(); ?></a></li>
		<?php } ?>
		</ul>
	<?php } ?>
	<?php
	$files = $currentFolder->getFiles();
	if (sizeof($files) > 0) { ?>
		<ul>
		<?php
		foreach ($files as $key => $file) { ?>
			<li><a href="<?php echo $file->getPath(); ?>"><?php echo $file->getName(); ?></a></li>
		<?php } ?>
		</ul>
	<?php } ?>
</body>
</html>