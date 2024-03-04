<?php

namespace MediaWiki\Installer;

use MediaWiki\Status\Status;

/**
 * @internal
 */
class SqliteConnectForm extends DatabaseConnectForm {

	public function getHtml() {
		return $this->getTextBox(
				'wgSQLiteDataDir',
				'config-sqlite-dir', [],
				$this->webInstaller->getHelpBox( 'config-sqlite-dir-help' )
			) .
			$this->getTextBox(
				'wgDBname',
				'config-db-name',
				[],
				$this->webInstaller->getHelpBox( 'config-sqlite-name-help' )
			);
	}

	/**
	 * @return Status
	 */
	public function submit() {
		$this->setVarsFromRequest( [ 'wgSQLiteDataDir', 'wgDBname' ] );

		# Try realpath() if the directory already exists
		$dir = SqliteInstaller::realpath( $this->getVar( 'wgSQLiteDataDir' ) );
		$result = SqliteInstaller::checkDataDir( $dir );
		if ( $result->isOK() ) {
			# Try expanding again in case we've just created it
			$dir = SqliteInstaller::realpath( $dir );
			$this->setVar( 'wgSQLiteDataDir', $dir );
		}
		# Table prefix is not used on SQLite, keep it empty
		$this->setVar( 'wgDBprefix', '' );

		return $result;
	}

}
