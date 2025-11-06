<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;

/**
 * @internal For use by the installer
 */
class SqliteUtils {
	/**
	 * Check if the data directory is writable or can be created
	 * @param string $dir Path to the data directory
	 * @return Status Return fatal Status if $dir un-writable or no permission to create a directory
	 */
	public function checkDataDir( $dir ): Status {
		if ( is_dir( $dir ) ) {
			if ( !is_readable( $dir ) || !is_writable( $dir ) ) {
				return Status::newFatal( 'config-sqlite-dir-unwritable', $dir );
			}
		} elseif ( !is_writable( dirname( $dir ) ) ) {
			// Check the parent directory if $dir not exists
			$webserverGroup = $this->maybeGetWebserverPrimaryGroup();
			if ( $webserverGroup !== null ) {
				return Status::newFatal(
					'config-sqlite-parent-unwritable-group',
					$dir, dirname( $dir ), basename( $dir ),
					$webserverGroup
				);
			}

			return Status::newFatal(
				'config-sqlite-parent-unwritable-nogroup',
				$dir, dirname( $dir ), basename( $dir )
			);
		}
		return Status::newGood();
	}

	/**
	 * On POSIX systems return the primary group of the webserver we're running under.
	 * On other systems just returns null.
	 *
	 * @return string|null
	 */
	private function maybeGetWebserverPrimaryGroup() {
		if ( !function_exists( 'posix_getegid' ) || !function_exists( 'posix_getpwuid' ) ) {
			# I don't know this, this isn't UNIX.
			return null;
		}

		# posix_getegid() *not* getmygid() because we want the group of the webserver,
		# not whoever owns the current script.
		$gid = posix_getegid();
		return posix_getpwuid( $gid )['name'] ?? null;
	}

}
