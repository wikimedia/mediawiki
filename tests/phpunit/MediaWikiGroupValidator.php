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
 * @ingroup Testing
 */

/**
 * Trait that provides methods to check if group annotations are valid.
 */
trait MediaWikiGroupValidator {

	/**
	 * @return bool
	 * @throws ReflectionException
	 * @since 1.34
	 */
	public function isTestInDatabaseGroup() {
		// If the test class says it belongs to the Database group, it needs the database.
		// NOTE: This ONLY checks for the group in the class level doc comment.
		$rc = new ReflectionClass( $this );
		return (bool)preg_match( '/@group +Database/im', $rc->getDocComment() );
	}
}
