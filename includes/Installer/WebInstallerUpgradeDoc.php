<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

class WebInstallerUpgradeDoc extends WebInstallerDocument {

	/**
	 * @return string
	 */
	protected function getFileName() {
		return 'UPGRADE';
	}

}
