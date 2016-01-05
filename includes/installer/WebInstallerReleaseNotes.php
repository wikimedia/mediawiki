<?php

class WebInstallerReleaseNotes extends WebInstallerDocument {

	/**
	 * @throws MWException
	 * @return string
	 */
	protected function getFileName() {
		global $wgVersion;

		if ( !preg_match( '/^(\d+)\.(\d+).*/i', $wgVersion, $result ) ) {
			throw new MWException( 'Variable $wgVersion has an invalid value.' );
		}

		return 'RELEASE-NOTES-' . $result[1] . '.' . $result[2];
	}

}

