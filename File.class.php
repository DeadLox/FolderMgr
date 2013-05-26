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

	public function __construct($path) {
		$this->path = $path;
		$this->fullName = $this->getFileName();
		$this->getExtAndName();
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
}