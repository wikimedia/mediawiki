<?php
/**
 * This file is part of MediaWiki.
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

namespace MediaWiki\Slots;

use MediaWiki\Storage\RevisionRecord;
use ParserOptions;
use ParserOutput;
use User;

/**
 * FIXME
 *
 * @since 1.32
 */
class RevisionRenderer implements SlotOutputCombiner {

	/**
	 * @var SlotRoleRegistry
	 */
	private $roleRegistry;

	/**
	 * @param RevisionRecord $rev
	 * @param ParserOptions $options
	 * @param User $forUser
	 * @return RenderedRevision
	 */
	public function getRenderedRevision(
		RevisionRecord $rev,
		ParserOptions $options = null,
		User $forUser = null
	) {
		if ( !$options ) {
			if ( $forUser ) {
				$options = $this->getUserOptions( $forUser );
			} else {
				$options = $this->getCanonicalParserOptions( $rev );
			}
		}

		return new RenderedRevision( $rev, $options, $this, $forUser );
	}

	/**
	 * @param RevisionRecord $rev
	 * @param SlotParserOutputProvider $outputProvider
	 * @param ParserOptions $options
	 * @return ParserOutput
	 */
	public function combineSlotOutput(
		RevisionRecord $rev,
		SlotParserOutputProvider $outputProvider,
		ParserOptions $options
	) {
		$output = new ParserOutput();
		$html = '';

		// TODO: put fancy layout logic here. Better, delegate to a RevisionRenderer service.

		foreach ( $rev->getSlots()->getSlots() as $slot ) {
			$handler = $this->roleRegistry->getRoleHandler(
				$slot->getRole(),
				$rev->getPageAsLinkTarget()
			);

			// $outputProvider is asked twice for this output. It really should cache!
			$html .= $handler->getRawHtml( $slot, $outputProvider, $output );

			$slotOutput = $outputProvider->getSlotParserOutput( $slot->getRole() );
			$this->registerTrackingMetaData( $slotOutput, $output );
		}

		// TODO: cache the result.
		$output->setText( $html );
		return $output;

	}

	private function registerTrackingMetaData( ParserOutput $slotOutput, ParserOutput $targetOutput ) {
		// FIXME
	}

	private function getCanonicalParserOptions( RevisionRecord $rev ) {
		// XXX: we really shouldn't need $rev here. But at the moment, we do.
		// FIXME
	}

	private function getUserOptions( User $forUser ) {
		// FIXME
	}

}
