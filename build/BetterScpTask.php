<?php

/**
 * Description of BetterScpTask
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class BetterScpTask extends ScpTask {
	/**
	 * Override to not die if there's no module installed
	 *
	 * @return boolean
	 */
	public function init() {
		return true;
	}
}
