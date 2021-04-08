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

use InvalidArgumentException;

/**
 * Deserializes things from JSON.
 *
 * @since 1.36
 * @package MediaWiki\Json
 */
interface JsonUnserializer {

	/**
	 * Restore an instance of simple type or JsonUnserializable subclass
	 * from the JSON serialization. It supports passing array/object to
	 * allow manual decoding of the JSON string if needed.
	 *
	 * @note JSON objects are unconditionally unserialized as PHP associative
	 * arrays and not as instances of \stdClass.
	 *
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param array|string|object $json
	 * @param string|null $expectedClass What class to expect in unserialization.
	 *   If null, no expectation. Must be a descendant of JsonUnserializable.
	 * @throws InvalidArgumentException if the passed $json can't be unserialized.
	 * @return mixed
	 */
	public function unserialize( $json, string $expectedClass = null );

	/**
	 * Helper to unserialize an array of JsonUnserializable instances or simple types.
	 * @param array $array
	 * @return array
	 */
	public function unserializeArray( array $array ) : array;
}
