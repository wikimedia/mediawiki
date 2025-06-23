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

use JsonException;
use JsonSerializable;
use Wikimedia\JsonCodec\JsonClassCodec;

/**
 * A JsonClassCodec for objects implementing the JsonSerializable interface.
 *
 * NOTE that this is for compatibility only and does NOT deserialize!
 *
 * @see JsonSerializable
 * @see JsonClassCodec
 * @since 1.43
 */
class JsonSerializableCodec implements JsonClassCodec {

	/** @inheritDoc */
	public function toJsonArray( $obj ): array {
		return $obj->jsonSerialize();
	}

	/** @return never */
	public function newFromJsonArray( string $className, array $json ): never {
		throw new JsonException( "Cannot deserialize: {$className}" );
	}

	/** @inheritDoc */
	public function jsonClassHintFor( string $className, string $keyName ) {
		return null;
	}

	public static function getInstance(): JsonSerializableCodec {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new JsonSerializableCodec();
		}
		return $instance;
	}
}
