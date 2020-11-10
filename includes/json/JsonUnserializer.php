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
 * @ingroup Json
 */

namespace MediaWiki\Json;

use FormatJson;
use InvalidArgumentException;

/**
 * Helper class to unserialize instances of JsonUnserializable.
 *
 * @package MediaWiki\Json
 */
class JsonUnserializer {

	/**
	 * Name of the property where the class information is stored.
	 * @internal
	 */
	public const TYPE_ANNOTATION = '_type_';

	/**
	 * Restore an instance of JsonUnserializable subclass from the JSON serialization.
	 *
	 * @param array|string|object $json
	 * @param string|null $expectedClass What class to expect in unserialization. If null, no expectation.
	 * @throws InvalidArgumentException if the passed $json can't be unserialized.
	 * @return JsonUnserializable
	 */
	public function unserialize( $json, string $expectedClass = null ) : JsonUnserializable {
		if ( is_string( $json ) ) {
			$json = FormatJson::decode( $json, true );
			if ( !$json ) {
				// TODO: in PHP 7.3, we can use JsonException
				throw new InvalidArgumentException( 'Bad JSON' );
			}
		}

		if ( is_object( $json ) ) {
			$json = (array)$json;
		}

		if ( !$this->canMakeNewFromValue( $json ) ) {
			throw new InvalidArgumentException( 'JSON did not have ' . self::TYPE_ANNOTATION );
		}

		$class = $json[self::TYPE_ANNOTATION];
		if ( !class_exists( $class ) || !is_subclass_of( $class, JsonUnserializable::class ) ) {
			throw new InvalidArgumentException( "Target class {$class} does not exist" );
		}

		$obj = $class::newFromJsonArray( $this, $json );

		// Check we haven't accidentally unserialized a godzilla if we were told we are not expecting it.
		if ( $expectedClass && !is_a( $obj, $expectedClass ) ) {
			$actualClass = get_class( $obj );
			throw new InvalidArgumentException( "Expected {$expectedClass}, got {$actualClass}" );
		}
		return $obj;
	}

	/**
	 * Helper to unserialize an array of JsonUnserializable instances or scalars.
	 * @param array $array
	 * @return array
	 */
	public function unserializeArray( array $array ) : array {
		$unserializedExtensionData = [];
		foreach ( $array as $key => $value ) {
			if ( $this->canMakeNewFromValue( $value ) ) {
				$unserializedExtensionData[$key] = $this->unserialize( $value );
			} else {
				$unserializedExtensionData[$key] = $value;
			}
		}
		return $unserializedExtensionData;
	}

	/**
	 * Is it likely possible to make a new instance from $json serialization?
	 * @param mixed $json
	 * @return bool
	 */
	private function canMakeNewFromValue( $json ) : bool {
		$classAnnotation = self::TYPE_ANNOTATION;
		return ( is_array( $json ) && array_key_exists( $classAnnotation, $json ) ) ||
			( is_object( $json ) && isset( $json->$classAnnotation ) );
	}
}
