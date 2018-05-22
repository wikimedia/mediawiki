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
class RevisionRenderer {

	/**
	 * @var SlotRoleRegistry
	 */
	private $roleRegistry;

	/**
	 * @param RevisionRecord $rev
	 * @param ParserOptions $options
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

		return new RenderedRevision(
			$rev, $options, $this->roleRegistry, function ( RenderedRevision $rrev ) {
			return $this->combineSlotOutput( $rrev );
		}
		);
	}

	/**
	 * @param RenderedRevision $rrev
	 * @return ParserOutput
	 */
	private function combineSlotOutput( RenderedRevision $rrev ) {
		$output = new ParserOutput();
		$html = '';

		// TODO: put fancy layout logic here. Better, delegate to a RevisionRenderer service.
		$revision = $rrev->getRevision();

		foreach ( $revision->getSlots()->getSlots() as $slot ) {
			// $outputProvider is asked twice for this output. It really should cache!
			$slotOutput = $rrev->getSlotParserOutput( $slot->getRole() );

			$html .= $slotOutput->getRawText();

			$this->registerOutputMetaData( $slotOutput, $output );
			$this->registerTrackingMetaData( $slotOutput, $output );
		}

		// TODO: cache the result.
		$output->setText( $html );
		return $output;

	}

	/**
	 * Register header items and relevant directives from $output to $target.
	 *
	 * This should not copy tracking data, like links.
	 *
	 * @param ParserOutput $output
	 * @param ParserOutput $target
	 */
	private function registerOutputMetaData( ParserOutput $output, ParserOutput $target ) {
		foreach ( $output->getHeadItems() as $key => $value ) {
			$target->addHeadItem( $key, $value );
		}

		// TODO: etc etc...
	}

	private function registerTrackingMetaData( ParserOutput $slotOutput, ParserOutput $targetOutput ) {
		// FIXME
	}

	/**
	 * @param RevisionRecord $rev
	 * @return ParserOptions
	 */
	private function getCanonicalParserOptions( RevisionRecord $rev ) {
		// XXX: we really shouldn't need $rev here. But at the moment, we do.
		// FIXME
	}

	/**
	 * @param User $forUser
	 * @return ParserOptions
	 */
	private function getUserOptions( User $forUser ) {
		// FIXME
	}

}
