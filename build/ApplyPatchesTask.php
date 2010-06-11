<?php
/* All code covered by the BSD license located at http://silverstripe.org/bsd-license/ */

/**
 * Task for applying patch files from a folder to a source trunk
 *
 * Assumes that the patches were created relative to the root folder of the application
 *
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 */
class ApplyPatchesTask extends Task {
    private $patchDir;

	public function setPatchDir($v) {
		$this->patchDir = $v;
	}

	public function main() {
		// go through each patch and apply it in the root of the app
		if (!is_dir($this->patchDir)) {
			throw new Exception("Invalid patch directory setting");
		}

		// read all the files in
		$patches = scandir($this->patchDir);

		
		foreach ($patches as $patch) {
			if ($patch == '.' || $patch == '..') {
				continue;
			}

			$file = $this->patchDir . '/' . $patch;

			if (is_file($file)) {
				$exec = "patch -p0 < $file";
				echo shell_exec($exec);
			}
		}
	}
}