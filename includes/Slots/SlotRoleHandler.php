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

use Content;
use Html;
use MediaWiki\Storage\RevisionRecord;
use MessageLocalizer;
use ParserOptions;
use ParserOutput;
use Title;

/**
 * FIXME
 *
 * @since 1.32
 */
class SlotRoleHandler {

	const FOR_COMBINED_OUTPUT = 'combined';
	const FOR_SOLO_OUTPUT = 'solo';
	const FOR_UPDATES_ONLY = 'no';

	/**
	 * @var MessageLocalizer
	 */
	private $localizer;

	/**
	 * @var string
	 */
	private $role;

	/**
	 * @var array
	 */
	private $placement = [
		'area' => 'main',
		'placement' => 'append'
	];

	private $defaultModel;

	/**
	 * @param $role
	 * @param $defaultModel
	 * @param MessageLocalizer $localizer
	 */
	public function __construct( $role, $defaultModel, MessageLocalizer $localizer ) {
		$this->role = $role;
		$this->localizer = $localizer;
		$this->defaultModel = $defaultModel;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultModel() {
		return $this->defaultModel;
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

	// XXX: no getContentModel(), that's in SlotRoleRegistry!
	// This way, we only need a single handler for the main slot, not one per content model.

	public function getSlotOutput( RevisionRecord $rev, ParserOptions $options, $mode = self::FOR_COMBINED_OUTPUT ) {
		$title = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );
		$audience = $options->getUser() ? RevisionRecord::FOR_THIS_USER : RevisionRecord::FOR_PUBLIC;
		$content = $rev->getContent( $this->role, $audience, $options->getUser() );
		$output = $content->getParserOutput(
			$title,
			$rev->getId(),
			$options,
			$mode !== self::FOR_UPDATES_ONLY
		);

		if ( $mode === self::FOR_COMBINED_OUTPUT ) {
			$this->prepareForCombinedOutput( $content, $output );
		}

		return $output;
	}

	protected function prepareForCombinedOutput(
		Content $content,
		ParserOutput $output
	) {
		$html = $output->getRawText();
		$html = $this->getSlotHeading()->parse() . $html;

		$class = 'mw-slot-content-' . $this->role;
		$html = Html::rawElement( 'div', [ 'class' => $class ], $html );

		$output->setText( $html );
	}

	/**
	 * @return array an associative array of hints
	 */
	public function getOutputPlacementHints() {
		return $this->placement;
	}

	/**
	 * @return \Message
	 */
	public function getSlotHeading() {
		return $this->localizer->msg( 'slot-content-header-' . $this->role );
	}

	public function supportsRedirects() {
		return false; // override for main slot!
	}

	public function getArticleCountMethod() {
		return 'none'; // override for main slot!
	}

}
