<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Installer
 *
 * @author Art Baltai
 */

declare( strict_types = 1 );
namespace MediaWiki\Installer\Services;

use Generator;

/**
 * @since 1.35
 */
class InstallerExtensionSelector {

	/**
	 * @var string
	 */
	private $extensionDir;

	/**
	 * @var string[]|null
	 */
	private $extensionJsonPath;

	public function __construct( string $extensionDir ) {
		$this->extensionDir = $extensionDir;
	}

	private function getExtensionJsonPaths() : array {
		if ( $this->extensionJsonPath === null ) {
			$this->extensionJsonPath = [];
			if ( is_dir( $this->extensionDir ) ) {
				$dh = opendir( $this->extensionDir );
				while ( ( $extension = readdir( $dh ) ) !== false ) {
					if ( mb_substr( $extension, 0, 1 ) === '.' ) {
						continue;
					}
					$jsonPath = "{$this->extensionDir}/{$extension}/extension.json";
					if ( is_file( $jsonPath ) && is_readable( $jsonPath ) ) {
						$this->extensionJsonPath[$extension] = $jsonPath;
					}
				}
				closedir( $dh );

				uksort( $this->extensionJsonPath, 'strnatcasecmp' );
			} // @todo else:warning?
		}

		return $this->extensionJsonPath;
	}

	/**
	 * Returns a Generator for interating through all exntension.json files converterd
	 * into array. Key is string of exntension name and value is decoded exntension.json.
	 *
	 * @return Generator
	 */
	private function getExtensionJsons() : Generator {
		foreach ( $this->getExtensionJsonPaths() as $extension => $jsonPath ) {
			$json = file_get_contents( $jsonPath );
			$options = json_decode( $json, true );
			if ( !is_array( $options ) ) {
				continue;
			}

			yield $extension => $options;
		}
	}

	/**
	 * Returns a Generator for interating through extension's options
	 * filtered by certain type.
	 *
	 * @param string $type
	 * @return Generator
	 */
	public function getExtOptionsByType( string $type ) : Generator {
		foreach ( $this->getExtensionJsons() as $extensionName => $options ) {
			if ( $type === ( $options['type'] ?? null ) ) {
				yield $extensionName => $options;
			}
		}
	}
}
