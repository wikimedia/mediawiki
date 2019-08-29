<?php
/**
 * Renders a slot diff by doing a text diff on the native representation.
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
 * Produces a warning message about not being able to render a slot diff.
 *
 * @since 1.34
 *
 * @ingroup DifferenceEngine
 */
class UnsupportedSlotDiffRenderer extends SlotDiffRenderer {

	/**
	 * @var MessageLocalizer
	 */
	private $localizer;

	/**
	 * UnsupportedSlotDiffRenderer constructor.
	 *
	 * @param MessageLocalizer $localizer
	 */
	public function __construct( MessageLocalizer $localizer ) {
		$this->localizer = $localizer;
	}

	/** @inheritDoc */
	public function getDiff( Content $oldContent = null, Content $newContent = null ) {
		$this->normalizeContents( $oldContent, $newContent );

		$oldModel = $oldContent->getModel();
		$newModel = $newContent->getModel();

		if ( $oldModel !== $newModel ) {
			$msg = $this->localizer->msg( 'unsupported-content-diff2', $oldModel, $newModel );
		} else {
			$msg = $this->localizer->msg( 'unsupported-content-diff', $oldModel );
		}

		return Html::rawElement(
			'tr',
			[],
			Html::rawElement(
				'td',
				[ 'colspan' => 4, 'class' => 'error' ],
				$msg->parse()
			)
		);
	}

}
