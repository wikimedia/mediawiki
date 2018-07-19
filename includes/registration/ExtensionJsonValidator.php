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
use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;

/**
 * Validate extension.json files against their JSON schema.
 *
 * This is used for static validation from the command-line via
 * validateRegistrationFile.php, and the PHPUnit structure test suite
 * (ExtensionJsonValidationTest).
 *
 * The files are normally read by the ExtensionRegistry
 * and ExtensionProcessor classes.
 *
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
	 * @codeCoverageIgnore
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
		} elseif ( !class_exists( JsonParser::class ) ) {
			call_user_func( $this->missingDepCallback,
				'The JSON lint library cannot be found, please install it through composer.'
			);
		}

		return true;
	}

	/**
	 * @param string $path file to validate
	 * @return bool true if passes validation
	 * @throws ExtensionJsonValidationError on any failure
	 */
	public function validate( $path ) {
		$contents = file_get_contents( $path );
		$jsonParser = new JsonParser();
		try {
			$data = $jsonParser->parse( $contents, JsonParser::DETECT_KEY_CONFLICTS );
		} catch ( ParsingException $e ) {
			if ( $e instanceof \Seld\JsonLint\DuplicateKeyException ) {
				throw new ExtensionJsonValidationError( $e->getMessage() );
			}
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

		$extraErrors = [];
		// Check if it's a string, if not, schema validation will display an error
		if ( isset( $data->{'license-name'} ) && is_string( $data->{'license-name'} ) ) {
			$licenses = new SpdxLicenses();
			$valid = $licenses->validate( $data->{'license-name'} );
			if ( !$valid ) {
				$extraErrors[] = '[license-name] Invalid SPDX license identifier, '
					. 'see <https://spdx.org/licenses/>';
			}
		}
		if ( isset( $data->url ) && is_string( $data->url ) ) {
			$parsed = wfParseUrl( $data->url );
			$mwoUrl = false;
			if ( $parsed['host'] === 'www.mediawiki.org' ) {
				$mwoUrl = true;
			} elseif ( $parsed['host'] === 'mediawiki.org' ) {
				$mwoUrl = true;
				$extraErrors[] = '[url] Should use www.mediawiki.org domain';
			}

			if ( $mwoUrl && $parsed['scheme'] !== 'https' ) {
				$extraErrors[] = '[url] Should use HTTPS for www.mediawiki.org URLs';
			}
		}

		$validator = new Validator;
		$validator->check( $data, (object)[ '$ref' => 'file://' . $schemaPath ] );
		if ( $validator->isValid() && !$extraErrors ) {
			// All good.
			return true;
		} else {
			$out = "$path did not pass validation.\n";
			foreach ( $validator->getErrors() as $error ) {
				$out .= "[{$error['property']}] {$error['message']}\n";
			}
			if ( $extraErrors ) {
				$out .= implode( "\n", $extraErrors ) . "\n";
			}
			throw new ExtensionJsonValidationError( $out );
		}
	}
}
