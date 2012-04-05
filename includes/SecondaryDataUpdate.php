<?php
/**
 * See docs/deferred.txt
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
 * Abstract base class for update jobs that do something with some secondary
 * data extracted from article.
 */
abstract class SecondaryDataUpdate {

	/**
	 * Constructor
	 */
    public function __construct( ) {
        # noop
	}

	/**
	 * Perform update.
	 */
	public abstract function doUpdate();

    /**
     * Conveniance method, calls doUpdate() on every element in the array.
     *
     * @static
     * @param $updates array
     */
    public static function runUpdates( $updates ) {
        if ( empty( $updates ) ) return; # nothing to do

        foreach ( $updates as $update ) {
            $update->doUpdate();
        }
    }

}
