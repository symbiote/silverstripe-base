<?php

/**
 * Copy a specified set of folders from one location to another
 * 
 * @author marcus
 *
 */
class CopyFoldersTask extends Task
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
			if (!recurse_copy($sourceItem, $this->targetDir . '/' . $item)) {
				throw new BuildException("Failed copying from $sourceItem to ".$this->targetDir);
			}
		}
	}
} 

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
    return true;
} 
?>