FolderMgr
=========

Manage Folder and File with PHP

HOW TO
======

require_once('Folder.class.php');
require_once('File.class.php');
$currentFolder = Folder::getCurrentFolder(<PATH_TO_YOUR_ROOT_FOLDER>);

GET FOLDERS
$folders = $currentFolder->getFolders();

GET FILES
$files = $currentFolder->getFiles();

GET FOLDER OR FILE NAME
$folder->getName();
$file->getName();

GET NAVIGATION URL TO FOLDER OR FILE
$folder->getUrlPath();
$file->getUrlPath();

GET URL TO FOLDER OR FILE
$folder->getPath();
$file->getPath();