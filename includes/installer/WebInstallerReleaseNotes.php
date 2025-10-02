<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use LogicException;

class WebInstallerReleaseNotes extends WebInstallerDocument {

	/**
	 * @return string
	 */
	protected function getFileName() {
		if ( !preg_match( '/^(\d+)\.(\d+).*/i', MW_VERSION, $result ) ) {
			throw new LogicException( 'Constant MW_VERSION has an invalid value.' );
		}

		return 'RELEASE-NOTES-' . $result[1] . '.' . $result[2];
	}

}
