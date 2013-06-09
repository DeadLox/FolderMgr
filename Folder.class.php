<?php
/**
 * Classe permettant la gestion d'un dossier
 * 
 * @DeadLox
 */
class Folder {
	private $path;
	private $absolutePath;
	private $name;
	private $lastFolder;
	private $folderList;
	private $fileList;
	private $messageList;
	private $createDate;
	private $editDate;
	private $blacklistFolder = array(".", "..", "@eaDir");
	private $blacklistFile = array("Thumbs.db");
	private static $blacklistExtension = array();
	private static $blacklistPath = array(".", "..");
	private static $blacklistFolderName = array("", ".", "..");

	private static $rootPath;

	public function __construct($path){
		$this->path = $path;
		$this->name = $this->getFolderName();
		$this->absolutePath = $this->findAbsolutePath();
		$this->lastFolder = $this->findLastFolder();
		$this->createDate = filectime($this->path);
		$this->editDate = filemtime($this->path);
	}

	// Permet d'ajouter des extensions de fichiers à la blacklist
	public static function addExtToBlackList($arrayToAdd){
		Folder::$blacklistExtension = array_merge(Folder::$blacklistExtension, $arrayToAdd);
	}

	public function createFolder($folderName){
		if (!in_array($folderName, Folder::$blacklistFolderName) && mkdir($this->path.'/'.$folderName)) {
			$messageList[] = "Le dossier ".$folderName." a bien été créé.";
		} else {
			$messageList[] = "Une erreur s'est produite lors de création du dossier.";
		}
		return $messageList;
	}

	public function renameFolder($newfolderName){
		if (!in_array($newfolderName, Folder::$blacklistFolderName) && rename($this->path, $this->absolutePath.''.$newfolderName)) {
			$this->path = $this->absolutePath.''.$newfolderName;
			$this->name = $newfolderName;
			$messageList[] = "Le dossier ".$newfolderName." a bien été renommé.";
		} else {
			$messageList[] = "Une erreur s'est produite lors du renommage du dossier.";
		}
		return $messageList;
	}

	// Supprime un répertoire et tout son contenu
	public function deleteFolder($path = ""){
		$path = ($path == "")? $this->path : $path;
        $directory = opendir($path);
        while($entry = @readdir($directory)){
        	$entryPath = $path.'/'.$entry;
            if(is_dir($entryPath) && !in_array($entry, array('.', '..'))){
            	if (file_exists($entryPath)) {
                	$this->deleteFolder($entryPath);  
            	}
            	rmdir($entryPath);
            }
            if (is_file($entryPath) && is_link($entryPath)) {
            	unlink($entryPath);
            }
        }
        closedir($directory);
	}

	// Supprime un array de fichiers
	public function deleteFiles($listFiles){
		if (sizeof($listFiles) > 0) {
			foreach ($listFiles as $fileName) {
				if (is_dir($this->path."/".$fileName)) {
					$this->deleteFolder($this->path."/".$fileName);
				} else {
					unlink($this->path."/".$fileName);
				}
			}
		}
	}

	private function findLastFolder(){
		$pos = strrpos($this->path, "/");
		if ($pos === false) {
			return Folder::$rootPath;
		} else {
			return substr($this->path, 0, $pos);
		}
	}

	public function hasLastFolder(){
		if ($this->path === Folder::$rootPath) {
			return false;
		} else {
			return true;
		}
	}

	public function listFolder(){
        if(is_dir($this->path)){
        	$this->folderList = array();
        	$this->fileList = array();
            $directory = opendir($this->path);
            while($entry = @readdir($directory)){
            	$entryPath = $this->path.'/'.$entry;
                if(is_dir($entryPath) && !in_array($entry, $this->blacklistFolder)){
                    $this->folderList[] = new Folder($entryPath);          
                }
                if(!is_dir($entryPath) && !in_array($entry, $this->blacklistFile)){
                	$file = new File($entryPath);
                	if (!in_array($file->getExtension(), Folder::$blacklistExtension)){
                    	$this->fileList[] = $file;
                    }       
                }
            }
            closedir($directory);
        }else{
            $this->messageList[] = $this->path.' n\'existe pas ou n\'est pas un dossier';
        }
	}

	private function getFolderName(){
		$pos = strrpos($this->path, "/");
		if ($pos === false) {
			return $this->path;
		} else {
			return substr($this->path, $pos+1, strlen($this->path));
		}
	}

	// Tri des fichiers par un champ
	public function getFoldersByField($field, $asc = true, $cmp = true) {
		$cmp = $cmp ? 'strcmp' : 'intcmp';
		$comparator = new ObjectComparator(array(
		  $field => $cmp,
		), $asc);
		$array = $comparator->sort($this->folderList);
		return $array;
	}

	// Tri des fichiers par un champ
	public function getFilesByField($field, $asc = true, $cmp = true) {
		$cmp = $cmp ? 'strcmp' : 'intcmp';
		$comparator = new ObjectComparator(array(
		  $field => $cmp,
		), $asc);
		$array = $comparator->sort($this->fileList);
		return $array;
	}

	/**
	 * Methode statique permettant de récupérer le dossier courant
	 */
	public static function getCurrentFolder($defautPath = "") {
		Folder::$rootPath = $defautPath;
		if (isset($_GET) && !empty($_GET)) {
			extract($_GET);
			if (Folder::checkPath($path)) {
				$path = Folder::$rootPath;
			}
			$currentFolder = new Folder($path);
			$currentFolder->listFolder();
		} else {
			$currentFolder = new Folder($defautPath);
			$currentFolder->listFolder();
		}
		return $currentFolder;
	}

	public function findAbsolutePath(){
		$pos = strrpos($this->path, "/");
		if ($pos === false) {
			return $this->path;
		} else {
			return substr($this->path, 0, $pos+1);
		}
	}

	/**
	 * Permet de vérifier si le chemin ne contient pas des chemins non souhaités
	 */
	public static function checkPath($path) {
		if (in_array($path, Folder::$blacklistPath)) {
			return true;
		} else {
			return false;
		}
	}

	public function getPath(){
		return $this->path;
	}
	public function getUrlPath() {
		return urlencode($this->path);
	}
	public function getAbsolutePath(){
		return $this->absolutePath;
	}
	public function getName(){
		return $this->name;
	}
	public function getLastFolder() {
		return $this->lastFolder;
	}
	public function getUrlLastFolder(){
		return "?path=".urlencode($this->lastFolder);
	}
	public function getFolders(){
		return $this->folderList;
	}
	public function getFiles(){
		return $this->fileList;
	}
	public function getCreateDate(){
		return $this->createDate;
	}
	public function getEditDate(){
		return $this->editDate;
	}

	/* --- Magic Getters --- */
	public function __get($property){
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
}