<?php
/**
 * Renders a diff for a single slot.
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
use Wikimedia\Assert\Assert;

/**
 * Renders a diff for a single slot (that is, a diff between two content objects).
 *
 * Callers should obtain this class by invoking ContentHandler::getSlotDiffRendererClass
 * on the content handler of the new content object (ie. the one shown on the right side
 * of the diff), or of the old one if the new one does not exist.
 *
 * The default implementation just does a text diff on the native text representation.
 * Content handler extensions can subclass this to provide a more appropriate diff method by
 * overriding ContentHandler::getSlotDiffRendererClass. Other extensions that want to interfere
 * with diff generation in some way can use the GetSlotDiffRenderer hook.
 *
 * @ingroup DifferenceEngine
 */
abstract class SlotDiffRenderer {

	/**
	 * Get a diff between two content objects. One of them might be null (meaning a slot was
	 * created or removed), but both cannot be. $newContent (or if it's null then $oldContent)
	 * must have the same content model that was used to obtain this diff renderer.
	 * @param Content|null $oldContent
	 * @param Content|null $newContent
	 * @return string
	 */
	abstract public function getDiff( Content $oldContent = null, Content $newContent = null );

	/**
	 * Add modules needed for correct styling/behavior of the diff.
	 * @param OutputPage $output
	 */
	public function addModules( OutputPage $output ) {
	}

	/**
	 * Return any extra keys to split the diff cache by.
	 * @return array
	 */
	public function getExtraCacheKeys() {
		return [];
	}

	/**
	 * Helper method to normalize the input of getDiff().
	 * Verifies that at least one of $oldContent and $newContent is not null, verifies that
	 * they are instances of one of the allowed classes (if provided), and replaces null with
	 * empty content.
	 * @param Content|null &$oldContent
	 * @param Content|null &$newContent
	 * @param string|array|null $allowedClasses
	 */
	protected function normalizeContents(
		Content &$oldContent = null, Content &$newContent = null, $allowedClasses = null
	) {
		if ( !$oldContent && !$newContent ) {
			throw new InvalidArgumentException( '$oldContent and $newContent cannot both be null' );
		}

		if ( $allowedClasses ) {
			if ( is_array( $allowedClasses ) ) {
				$allowedClasses = implode( '|', $allowedClasses );
			}
			Assert::parameterType( $allowedClasses . '|null', $oldContent, '$oldContent' );
			Assert::parameterType( $allowedClasses . '|null', $newContent, '$newContent' );
		}

		if ( !$oldContent ) {
			$oldContent = $newContent->getContentHandler()->makeEmptyContent();
		} elseif ( !$newContent ) {
			$newContent = $oldContent->getContentHandler()->makeEmptyContent();
		}
	}

}
