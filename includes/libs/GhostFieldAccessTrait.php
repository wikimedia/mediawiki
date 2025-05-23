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

namespace Wikimedia\Reflection;

/**
 * Trait for accessing "ghost fields".
 *
 * Ghost fields are fields that have been created in an object instance
 * by PHP's unserialize() mechanism, though they no longer exist
 * in the current version of the corresponding class.
 *
 * Accessing non-public ghost fields offers some challenges due to
 * how they are handled by PHP internally.
 *
 * @see https://www.php.net/manual/en/language.types.array.php#language.types.array.casting
 * @since 1.36
 * @deprecated since 1.44
 */
trait GhostFieldAccessTrait {

	/**
	 * Get the value of the ghost field named $name,
	 * or null if the field does not exist.
	 *
	 * @param string $name
	 * @param class-string ...$aliases
	 * @return mixed|null
	 */
	private function getGhostFieldValue( string $name, string ...$aliases ) {
		if ( isset( $this->$name ) ) {
			return $this->$name;
		}

		$data = (array)$this;

		// Protected variables have a '*' prepended to the variable name.
		// These prepended values have null bytes on either side.
		$protectedName = "\x00*\x00{$name}";
		if ( isset( $data[$protectedName] ) ) {
			return $data[$protectedName];
		}

		// Private variables have the class name prepended to the variable name.
		// These prepended values have null bytes on either side.
		$thisClass = get_class( $this );
		$privateName = "\x00{$thisClass}\x00{$name}";
		if ( isset( $data[$privateName] ) ) {
			return $data[$privateName];
		}

		// Check old aliased class names as well.
		foreach ( $aliases as $thisClass ) {
			$privateName = "\x00{$thisClass}\x00{$name}";
			if ( isset( $data[$privateName] ) ) {
				return $data[$privateName];
			}
		}

		return null;
	}

	/**
	 * In PHP <= 8.1, private fields of a class are not properly restored
	 * when the class is aliased to a new name, since the private fields are
	 * prefixed with the *old* name of the class.  This method can be used
	 * to restore them if present after deserialization.
	 *
	 * @param string $name
	 * @param class-string ...$aliases
	 */
	private function restoreAliasedGhostField( string $name, string ...$aliases ): void {
		$data = (array)$this;
		foreach ( $aliases as $thisClass ) {
			$privateName = "\x00{$thisClass}\x00{$name}";
			if ( isset( $data[$privateName] ) ) {
				$this->$name = $data[$privateName];
				unset( $data[$privateName] );
				return;
			}
		}
	}
}
