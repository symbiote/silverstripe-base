<?php

/**
 * Create symlinks between a set of items and a specific target
 * 
 * Accepts an optional parameter to indicate whether to perform a copy on 
 * windows platforms
 * 
 * @author marcus
 *
 */
class SymlinkTask extends Task
{
	private $items = null;
	private $targetDir = null;
	private $copy = false;
	private $sourceDir = null;
	
	public function setItems($items) {
		$this->items = explode(',', $items);
	}
	
	public function setSourceDir($sourceDir) {
		$this->sourceDir = $sourceDir;
	}
	
	public function setTargetDir($targetDir) {
		$this->targetDir = $targetDir;
	}
	
	public function copy($copy) {
		$this->copy = $copy;
	}
	
	public function main() {
		if (!is_dir($this->targetDir)) {
			throw new BuildException("Invalid symlink target $this->targetDir");
		}
		
		foreach ($this->items as $item) {
			// if there's a source dir set, we're assuming everything is coming from that dir
			$sourceItem = $this->sourceDir ? $this->sourceDir . '/' . $item : $item;
			if (!symlink($sourceItem, $this->targetDir . '/' . $item)) {
				throw new BuildException("Failed creating symlink from $sourceItem to ".$this->targetDir);
			}
		}
	}
} 

?>