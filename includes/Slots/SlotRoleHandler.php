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

use Html;
use MediaWiki\Storage\SlotRecord;
use MessageLocalizer;
use ParserOutput;

/**
 * FIXME
 *
 * @since 1.32
 */
class SlotRoleHandler {

	/**
	 * @var MessageLocalizer
	 */
	private $localizer;

	/**
	 * @var string
	 */
	private $role;

	/**
	 * @var string|null HTML
	 */
	private $headingHtml;

	/**
	 * @param $role
	 * @param MessageLocalizer $localizer
	 */
	public function __construct( $role, MessageLocalizer $localizer ) {
		$this->role = $role;
		$this->localizer = $localizer;
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

	// XXX: no getContentModel(), that's in SlotRoleRegistry!
	// This way, we only need a single handler for the main slot, not one per content model.

	/**
	 * Returns HTML representing the slot's content in the context of a revision's standard view.
	 *
	 * @param SlotRecord $record
	 * @param SlotParserOutputProvider $provider A provider for getting the slot's ParserOutput
	 * @param ParserOutput|null $target The ParserOutput the HTML returned by this method will
	 *        eventually be placed. If given, getOutput() should register any headers (scripts,
	 *        styles, directives, etc) needed by the HTML in $target. No HTML is placed in this
	 *        ParserOutput however.
	 *
	 * @return string HTML
	 */
	public function getRawHtml(
		SlotRecord $record,
		SlotParserOutputProvider $provider,
		ParserOutput $target = null
	) {
		$output = $provider->getSlotParserOutput( $this->role );
		$html = $output->getRawText();
		$html = $this->getHeading() . $html;

		if ( $target ) {
			$this->registerOutputMetaData( $output, $target );
		}

		$class = 'mw-slot-content-' . $this->role;
		$html = Html::rawElement( 'div', [ 'class' => $class ], $html );
		return $html;
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

	/**
	 * @return array an associative array of hints
	 */
	public function getOutputPlacementHints() {
		return [
			'area' => 'main',
			'placement' => 'append'
		];
	}

	private function getHeading() {
		if ( $this->role === 'main' ) {
			return '';
		}

		if ( $this->headingHtml ) {
			$msg = $this->localizer->msg( 'slot-content-header-' . $this->role );
			$this->headingHtml = $msg->parse();
		}

		return $this->headingHtml;
	}

}
