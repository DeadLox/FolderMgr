<?php
/**
 * Classe permettant de gérer un fichier
 * 
 * @DeadLox
 */
class File {
	private $path;
	private $name;
	private $fullName;
	private $extension;
	private $filesize;
	private $sizeUnit;
	private $createDate;
	private $editDate;

	public function __construct($path) {
		$this->path = $path;
		$this->fullName = $this->getFileName();
		$this->getExtAndName();
		$this->getSize();
		$this->createDate = filectime($this->path);
		$this->editDate = filemtime($this->path);
	}

	/**
	 * Récupère l'extension du fichier
	 */
	private function getExtAndName() {
		$pos = strpos($this->fullName, ".");
		if ($pos === false) {
			$this->name = $this->fullName;
			$this->extension = "Inconnue";
		} else {
			$this->name = substr($this->fullName, 0, $pos);
			$this->extension = substr($this->fullName, $pos+1, strlen($this->fullName));
		}
	}

	private function getFileName(){
		$pos = strrpos($this->path, "/");
		if ($pos === false) {
			return $this->path;
		} else {
			return substr($this->path, $pos+1, strlen($this->path));
		}
	}

	private function getSize(){
		$size = filesize($this->path);
		if ($size < 1024) {
			$this->sizeUnit = "Oc";
			$this->filesize = $size;
		} else if ($size < 1048576) {
			$this->sizeUnit = "Ko";
			$this->filesize = $size/1024;
		} else if ($size < 1073741824) {
			$this->sizeUnit = "Mo";
			$this->filesize = $size/1048576;
		} else {
			$this->sizeUnit = "Go";
			$this->filesize = $size/1073741824;
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
	public function getFullName(){
		return $this->fullName;
	}
	public function getFilesize(){
		return $this->filesize;
	}
	public function getSizeUnit(){
		return $this->sizeUnit;
	}
	public function getCreateDate(){
		return $this->createDate;
	}
	public function getEditDate(){
		return $this->editDate;
	}
	public function getExtension(){
		return $this->extension;
	}

	/* --- Magic Getters --- */
	public function __get($property){
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
}