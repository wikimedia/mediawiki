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

trait JsonUnserializableTrait {

	public function jsonSerialize() {
		return $this->annotateJsonForDeserialization(
			$this->toJsonArray()
		);
	}

	/**
	 * Annotate the $json array with class metadata.
	 *
	 * @param array $json
	 * @return array
	 */
	private function annotateJsonForDeserialization( array $json ) : array {
		$json[JsonConstants::TYPE_ANNOTATION] = get_class( $this );
		return $json;
	}

	/**
	 * Prepare this object for JSON serialization.
	 * The returned array will be passed to self::newFromJsonArray
	 * upon JSON deserialization.
	 * @return array
	 */
	abstract protected function toJsonArray(): array;
}
