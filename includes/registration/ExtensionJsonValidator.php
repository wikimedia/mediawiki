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
 */

use Composer\Spdx\SpdxLicenses;
use JsonSchema\Validator;

/**
 * @since 1.29
 */
class ExtensionJsonValidator {

	/**
	 * @var callable
	 */
	private $missingDepCallback;

	/**
	 * @param callable $missingDepCallback
	 */
	public function __construct( callable $missingDepCallback ) {
		$this->missingDepCallback = $missingDepCallback;
	}

	/**
	 * @return bool
	 */
	public function checkDependencies() {
		if ( !class_exists( Validator::class ) ) {
			call_user_func( $this->missingDepCallback,
				'The JsonSchema library cannot be found, please install it through composer.'
			);
			return false;
		} elseif ( !class_exists( SpdxLicenses::class ) ) {
			call_user_func( $this->missingDepCallback,
				'The spdx-licenses library cannot be found, please install it through composer.'
			);
			return false;
		}

		return true;
	}

	/**
	 * @param string $path file to validate
	 * @return bool true if passes validation
	 * @throws ExtensionJsonValidationError on any failure
	 */
	public function validate( $path ) {
		$data = json_decode( file_get_contents( $path ) );
		if ( !is_object( $data ) ) {
			throw new ExtensionJsonValidationError( "$path is not valid JSON" );
		}

		if ( !isset( $data->manifest_version ) ) {
			throw new ExtensionJsonValidationError(
				"$path does not have manifest_version set." );
		}

		$version = $data->manifest_version;
		$schemaPath = __DIR__ . "/../../docs/extension.schema.v$version.json";

		// Not too old
		if ( $version < ExtensionRegistry::OLDEST_MANIFEST_VERSION ) {
			throw new ExtensionJsonValidationError(
				"$path is using a non-supported schema version"
			);
		} elseif ( $version > ExtensionRegistry::MANIFEST_VERSION ) {
			throw new ExtensionJsonValidationError(
				"$path is using a non-supported schema version"
			);
		}

		$licenseError = false;
		// Check if it's a string, if not, schema validation will display an error
		if ( isset( $data->{'license-name'} ) && is_string( $data->{'license-name'} ) ) {
			$licenses = new SpdxLicenses();
			$valid = $licenses->validate( $data->{'license-name'} );
			if ( !$valid ) {
				$licenseError = '[license-name] Invalid SPDX license identifier, '
					. 'see <https://spdx.org/licenses/>';
			}
		}

		$validator = new Validator;
		$validator->check( $data, (object)[ '$ref' => 'file://' . $schemaPath ] );
		if ( $validator->isValid() && !$licenseError ) {
			// All good.
			return true;
		} else {
			$out = "$path did pass validation.\n";
			foreach ( $validator->getErrors() as $error ) {
				$out .= "[{$error['property']}] {$error['message']}\n";
			}
			if ( $licenseError ) {
				$out .= "$licenseError\n";
			}
			throw new ExtensionJsonValidationError( $out );
		}
	}
}
