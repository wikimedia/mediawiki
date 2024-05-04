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

use JsonSerializable;

/**
 * Classes implementing this interface support round-trip JSON serialization/deserialization
 * using the JsonDeserializer utility.
 *
 * The resulting JSON must be annotated with class information for deserialization to work.
 * Use JsonDeserializableTrait in implementing classes which annotates the JSON automatically.
 *
 * @see JsonDeserializer
 * @see JsonDeserializableTrait
 * @since 1.36
 * @package MediaWiki\Json
 */
interface JsonDeserializable extends JsonSerializable {

	/**
	 * Creates a new instance of the class and initialized it from the $json array.
	 * @param JsonDeserializer $deserializer an instance of JsonDeserializer to use
	 *   for nested properties if they need special care.
	 * @param array $json
	 * @return JsonDeserializable
	 */
	public static function newFromJsonArray( JsonDeserializer $deserializer, array $json );
}

/** @deprecated class alias since 1.43 */
class_alias( JsonDeserializable::class, 'MediaWiki\\Json\\JsonUnserializable' );
