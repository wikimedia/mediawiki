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

/**
 * Serializes things to JSON.
 *
 * @stable to type
 * @since 1.36
 * @package MediaWiki\Json
 */
interface JsonSerializer {

	/**
	 * Encode $value as JSON with an intent to use JsonDeserializer::unserialize
	 * to decode it back.
	 *
	 * @param mixed|JsonDeserializable $value A value to encode. Can be any scalar,
	 * array, stdClass, JsonDeserializable or any combination of them.
	 * @throws JsonException if the value can not be serialized.
	 * @return string
	 */
	public function serialize( $value );

	// TODO: move more methods from FormatJson to here.
}
