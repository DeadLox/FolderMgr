<?php
require_once('ObjectComparator.class.php');
require_once('Folder.class.php');
require_once('File.class.php');

$defautPath = "First level";
Folder::addExtToBlackList(array('txt'));
$currentFolder = Folder::getCurrentFolder($defautPath);

if (isset($_POST) && !empty($_POST)) {
	extract($_POST);
	if (isset($create)) {
		$messages = $currentFolder->createFolder($folderName);
	} else if (isset($rename)) {
		$messages = $currentFolder->renameFolder($folderName);
	} else if (isset($delete)) {
		$currentFolder->deleteFolder();
		header('location: '.$currentFolder->getUrlLastFolder());
	}
	$currentFolder->listFolder();
}
?>
<!DOCTYPE HTML>
<html lang="fr-FR">
<head>
	<meta charset="UTF-8">
	<title>FileMgr</title>
</head>
<body>
	<h1><?php echo $currentFolder->getName(); ?></h1>
	<?php
	if (isset($messages)) { ?>
		<?php foreach ($messages as $key => $message) { ?>
			<div><?php echo $message; ?></div>
	<?php } } ?>
	<form method="POST" action="">
		<input type="hidden" name="action" value="create"/>
		<input type="text" value="" name="folderName"/>
		<input type="submit" name="create" value="Créer"/>
		<input type="submit" name="rename" value="Renommer"/>
		<input type="submit" name="delete" value="Supprimer"/>
	</form>
	<?php
	if ($currentFolder->hasLastFolder()) { ?>
		Retour <a href="<?php echo $currentFolder->getUrlLastFolder(); ?>"><?php echo $currentFolder->getLastFolder(); ?></a>
	<?php } ?>
	<?php
	$folders = $currentFolder->getFoldersByField("name", true);
	if (sizeof($folders) > 0) { ?>
		<ul>
		<?php
		foreach ($folders as $key => $folder) { ?>
			<li><a href="?path=<?php echo $folder->getUrlPath(); ?>">[FOLDER] <?php echo $folder->getName(); ?></a></li>
		<?php } ?>
		</ul>
	<?php } ?>
	<?php
	$files = $currentFolder->getFilesByField("editDate", true, false);
	if (sizeof($files) > 0) { ?>
		<ul>
		<?php
		foreach ($files as $key => $file) { ?>
			<li><a href="<?php echo $file->getPath(); ?>">[FILE] <?php echo $file->getFullName(); ?></a></li>
		<?php } ?>
		</ul>
	<?php } ?>
</body>
</html>