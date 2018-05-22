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
use MediaWiki\Storage\RevisionRecord;
use ParserOptions;
use ParserOutput;
use Title;
use User;

/**
 * FIXME
 *
 * @since 1.32
 */
class RenderedRevision implements SlotParserOutputProvider {

	/** @var RevisionRecord */
	private $revision;

	/**
	 * @var ParserOptions
	 */
	private $options;

	/**
	 * @var SlotOutputCombiner
	 */
	private $outputCombiner;

	/**
	 * @var User
	 */
	private $forUser;

	/**
	 * @param RevisionRecord $revision
	 * @param ParserOptions $options
	 * @param SlotOutputCombiner $outputCombiner
	 * @param User $forUser
	 */
	public function __construct(
		RevisionRecord $revision,
		ParserOptions $options,
		SlotOutputCombiner $outputCombiner,
		User $forUser = null
	) {
		$this->revision = $revision;
		$this->options = $options;
		$this->outputCombiner = $outputCombiner;
		$this->forUser = $forUser;
	}

	/**
	 * @return ParserOutput
	 */
	public function getCanonicalParserOutput() {
		// TODO: cache this
		$output = $this->outputCombiner->combineSlotOutput( $this->revision, $this, $this->options );

		return $output;
	}

	/**
	 * @param string $role
	 * @param bool $generateHtml
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role, $generateHtml = true ) {
		// TODO: fancy caching. beware $generateHtml
		// XXX: handle SuppressedDataException here?
		return $this->getContent( $role )->getParserOutput(
			$this->getTitle(),
			$this->revision->getId(),
			$this->options,
			$generateHtml
		);
	}

	/**
	 * @param string $role
	 *
	 * @return Content
	 */
	private function getContent( $role ) {
		$slot = $this->revision->getSlot( $role );

		$audience = $this->forUser ? RevisionRecord::FOR_THIS_USER : RevisionRecord::FOR_PUBLIC;

		return $slot->getContent( $audience, $this->forUser );
	}

	/**
	 * return Title
	 */
	private function getTitle() {
		// TODO: cache
		return Title::newFromLinkTarget( $this->revision->getPageAsLinkTarget() );
	}

}
