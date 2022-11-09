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
	 * @return string|false False if no such file exists, otherwise returns
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

	/**
	 * Obtains the full paths of COPYING or LICENSE files if they exist.
	 *
	 * @since 1.35
	 * @param string $extDir Path to the extensions root directory
	 * @return string[] Returns an array of zero or more paths.
	 */
	public static function getLicenseFileNames( string $extDir ): array {
		if ( !$extDir ) {
			return [];
		}

		$licenseFiles = [];
		foreach ( scandir( $extDir ) as $file ) {
			$fullPath = $extDir . DIRECTORY_SEPARATOR . $file;
			// Allow files like GPL-COPYING and MIT-LICENSE
			if ( preg_match( '/^([\w\.-]+)?(COPYING|LICENSE)(\.txt)?$/', $file ) &&
				is_readable( $fullPath ) &&
				is_file( $fullPath )
			) {
				$licenseFiles[] = $fullPath;
			}
		}

		return $licenseFiles;
	}
}
