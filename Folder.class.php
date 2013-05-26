<?php
/**
 * Classe permettant la gestion d'un dossier
 * 
 * @DeadLox
 */
// use File.class.php AS File;
class Folder {
	private $path;
	private $name;
	private $lastFolder;
	private $folderList;
	private $fileList;
	private $messageList;
	private $blacklistFolder = array(".", "..", "@eachDir");
	private $blacklistFile = array("thumbs.db");
	private static $blacklistPath = array(".", "..");

	private static $rootPath;

	public function __construct($path){
		$this->path = $path;
		$this->name = $this->getFolderName();
		$this->lastFolder = $this->findLastFolder();
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
            $directory = opendir($this->path);
            while($entry = @readdir($directory)){
            	$entryPath = $this->path.'/'.$entry;
                if(is_dir($entryPath) && !in_array($entry, $this->blacklistFolder)){
                    $this->folderList[] = new Folder($entryPath);          
                }
                if(!is_dir($entryPath) && !in_array($entry, $this->blacklistFile)){
                    $this->fileList[] = new File($entryPath);          
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
}