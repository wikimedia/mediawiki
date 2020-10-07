<?php
/**
 * Efficient concatenated text storage.
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

/**
 * Base class for general text storage via the "object" flag in old_flags, or
 * two-part external storage URLs. Used for represent efficient concatenated
 * storage, and migration-related pointer objects.
 */
interface HistoryBlob {
	/**
	 * Adds an item of text, returns a stub object which points to the item.
	 * You must call setLocation() on the stub object before storing it to the
	 * database
	 *
	 * @param string $text
	 *
	 * @return string The key for getItem()
	 */
	public function addItem( $text );

	/**
	 * Get item by key, or false if the key is not present
	 *
	 * @param string $key
	 *
	 * @return string|bool
	 */
	public function getItem( $key );

	/**
	 * Set the "default text"
	 * This concept is an odd property of the current DB schema, whereby each text item has a revision
	 * associated with it. The default text is the text of the associated revision. There may, however,
	 * be other revisions in the same object.
	 *
	 * Default text is not required for two-part external storage URLs.
	 *
	 * @param string $text
	 */
	public function setText( $text );

	/**
	 * Get default text. This is called from Revision::getRevisionText()
	 *
	 * @return string
	 */
	public function getText();
}
