<?php
/**
 * Classe permettant de gérer un fichier
 * 
 * @DeadLox
 */
class File {
	private $path;
	private $name;

	public function __construct($path) {
		$this->path = $path;
		$this->name = $this->getFileName();
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
}