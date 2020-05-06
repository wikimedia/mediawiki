<?php

namespace MediaWiki;

/**
 * @since 1.35
 */
class ExtensionInfo {

	/**
	 * Obtains the full path of a AUTHORS or CREDITS file if one exists.
	 *
	 * @param string $dir Path to the root directory
	 *
	 * @since 1.35
	 *
	 * @return bool|string False if no such file exists, otherwise returns
	 * a path to it.
	 */
	public static function getAuthorsFileName( $dir ) {
		if ( !$dir ) {
			return false;
		}

		foreach ( scandir( $dir ) as $file ) {
			$fullPath = $dir . DIRECTORY_SEPARATOR . $file;
			if ( preg_match( '/^(AUTHORS|CREDITS)(\.txt|\.wiki|\.mediawiki)?$/', $file ) &&
				is_readable( $fullPath ) &&
				is_file( $fullPath )
			) {
				return $fullPath;
			}
		}

		return false;
	}
}
