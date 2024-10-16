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

use Wikimedia\JsonCodec\JsonClassCodec;

/**
 * A JsonClassCodec for objects implementing the JsonDeserializable interface.
 *
 * @see JsonDeserializer
 * @see JsonDeserializableTrait
 * @see JsonClassCodec
 * @since 1.43
 */
class JsonDeserializableCodec implements JsonClassCodec {
	private JsonDeserializer $codec;

	public function __construct( JsonDeserializer $codec ) {
		$this->codec = $codec;
	}

	/** @inheritDoc */
	public function toJsonArray( $obj ): array {
		$result = $obj->jsonSerialize();
		// Undo the work of JsonDeserializableTrait to avoid
		// redundant storage of TYPE_ANNOTATION
		unset( $result[JsonConstants::TYPE_ANNOTATION] );
		return $result;
	}

	/** @inheritDoc */
	public function newFromJsonArray( string $className, array $json ) {
		return $className::newFromJsonArray( $this->codec, $json );
	}

	/** @inheritDoc */
	public function jsonClassHintFor( string $className, string $keyName ) {
		return null;
	}
}
