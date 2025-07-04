<?php

namespace MediaWiki\Installer;

use MediaWiki\Installer\Task\SqliteUtils;
use MediaWiki\Status\Status;

/**
 * @internal
 */
class SqliteConnectForm extends DatabaseConnectForm {

	/** @inheritDoc */
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
		$dir = $this->realpath( $this->getVar( 'wgSQLiteDataDir' ) );
		$result = $this->getSqliteUtils()->checkDataDir( $dir );
		if ( $result->isOK() ) {
			# Try expanding again in case we've just created it
			$dir = $this->realpath( $dir );
			$this->setVar( 'wgSQLiteDataDir', $dir );
		}
		# Table prefix is not used on SQLite, keep it empty
		$this->setVar( 'wgDBprefix', '' );

		return $result;
	}

	/**
	 * Safe wrapper for PHP's realpath() that fails gracefully if it's unable to canonicalize the path.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	private function realpath( $path ) {
		return realpath( $path ) ?: $path;
	}

	private function getSqliteUtils(): SqliteUtils {
		return new SqliteUtils;
	}

}
