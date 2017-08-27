<?php
/**
 * Interface for object that allow transient data to be associated with them.
 *
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

namespace MediaWiki\Storage;

use Content;
use OutOfBoundsException;
use User;

/**
 * Interface for object that allow transient data to be associated with them.
 *
 * Transient data can be associated with objects to allow any kind of derived value to be
 * cached locally on the respective object.
 *
 * FIXME: if we can't kill this, move it to a generic place (maybe a library). And make a trait.
 *
 * @since 1.31
 */
interface TransientDataAccess {

	/**
	 * Sets the value of a transient data field.
	 *
	 * Contract: After setTransientData( $key, $value ) was called, getTransientData( $key ) must
	 * return $value.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setTransientData( $key, $value );

	/**
	 * Returns the value of a transient data field.
	 *
	 * Contract: must return $value after setTransientData( $key, $value ) was called, and
	 * $default otherwise.
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getTransientData( $key, $default = null );

}