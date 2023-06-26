<?php
/**
 * Thrown when incompatible types are compared.
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
 * @ingroup DifferenceEngine
 */

/**
 * Exception thrown when trying to render a diff between two content types
 * which cannot be compared (this is typically the case for all content types
 * unless both of them are some variant of TextContent). SlotDiffRenderer and
 * DifferenceEngine classes should generally throw this exception when handed
 * a content object they don't know how to diff against.
 *
 * @since 1.41
 */
class IncompatibleDiffTypesException extends LocalizedException {

	/**
	 * @param string $oldModel Content model of the "old" side of the diff
	 * @param string $newModel Content model of the "new" side of the diff
	 */
	public function __construct( $oldModel, $newModel ) {
		$oldName = ContentHandler::getLocalizedName( $oldModel );
		$newName = ContentHandler::getLocalizedName( $newModel );
		parent::__construct( wfMessage( 'diff-incompatible', $oldName, $newName ) );
	}

}
