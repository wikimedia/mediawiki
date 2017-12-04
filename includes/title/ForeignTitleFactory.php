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

/**
 * A parser that translates page titles into ForeignTitle objects.
 */
interface ForeignTitleFactory {
	/**
	 * Creates a ForeignTitle object based on the page title, and optionally the
	 * namespace ID, of a page on a foreign wiki. These values could be, for
	 * example, the <title> and <ns> attributes found in an XML dump.
	 *
	 * @param string $title The page title
	 * @param int|null $ns The namespace ID, or null if this data is not available
	 * @return ForeignTitle
	 */
	public function createForeignTitle( $title, $ns = null );
}
