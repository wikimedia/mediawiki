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

namespace MediaWiki\Edit;

use Content;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\User\UserIdentity;
use ParserOptions;
use ParserOutput;
use Title;

/**
 * Represents information returned by WikiPage::prepareContentForEdit()
 *
 * @since 1.30
 */
class PreparedEdit {

	/**
	 * Time this prepared edit was made. Unused.
	 *
	 * @var string
	 * @deprecated since 1.31
	 * @todo remove in 1.32
	 */
	public $timestamp;

	/**
	 * Revision ID
	 *
	 * @var int|null
	 * @deprecated since 1.31
	 * @todo make private in 1.32
	 */
	public $revid;

	/**
	 * Content after going through pre-save transform
	 *
	 * @var Content|null
	 * @deprecated since 1.31, use getTransformedContentSlots() instead
	 * @todo make this private in 1.32
	 */
	public $pstContent;

	/**
	 * Content format, unused
	 *
	 * @var string
	 * @deprecated since 1.31
	 * @todo remove in 1.32
	 */
	public $format;

	/**
	 * Parser options used to get parser output
	 *
	 * @var ParserOptions
	 * @deprecated since 1.31, use getParserOptions() instead
	 * @todo make this private in 1.32
	 */
	public $popts;

	/**
	 * Parser output
	 *
	 * @var ParserOutput|null
	 * @deprecated since 1.31, use getParserOutput() instead
	 * @todo make this private in 1.32
	 */
	public $output;

	/**
	 * Content that is being saved (before PST)
	 *
	 * @var Content
	 * @deprecated since 1.31
	 * @todo make this private in 1.32
	 */
	public $newContent;

	/**
	 * Always null.
	 *
	 * @var Content|null
	 * @deprecated since 1.31
	 * @todo remove in 1.32
	 */
	public $oldContent;

	/** @var  RevisionSlots */
	private $newContentSlots;

	/** @var  RevisionSlots */
	private $transformedContentSlots;

	/** @var UserIdentity  */
	private $user;

	/**
	 * @var string
	 */
	private $signature;

	/**
	 * @param RevisionSlots $newContentSlots
	 * @param RevisionSlots $transformedContentSlots Content of the new revision, after pre-safe
	 *        transform, including inherited slots.
	 * @param UserIdentity $user
	 * @param ParserOptions $popts
	 * @param ParserOutput $output
	 * @param RevisionRecord|null $newRevision
	 * @internal param RevisionSlots $newContent ContentSlots of the new revision, before pre-safe transform,
	 *        not including inherited slots.
	 */
	public function __contruct(
		RevisionSlots $newContentSlots,
		RevisionSlots $transformedContentSlots,
		UserIdentity $user,
		ParserOptions $popts,
		ParserOutput $output,
		RevisionRecord $baseRevision = null,
		RevisionRecord $newRevision = null
	) {
		$this->newContentSlots = $newContentSlots;
		$this->transformedContentSlots = $transformedContentSlots;
		$this->user = $user;
		$this->popts = $popts;
		$this->output = $output;

		$this->revid = $newRevision ? $newRevision->getId() : 0;

		// Backwards compatibility, remove in 1.32:
		$this->newContent = $newContentSlots->getContent( 'main' );
		$this->pstContent = $transformedContentSlots->getContent( 'main' );
		$this->oldContent = null;

		$this->signature = self::makeSignature( $newContentSlots, $user, $newRevision );
	}

	/**
	 * Returns the signature of this prepared edit.
	 *
	 * If the signature of a PreparedEdit matches the signature returned by makeSignature for
	 * a set of parameters, this indicates that the PreparedEdit can be used for that set
	 * of parameters.
	 *
	 * @return string
	 */
	public function getSignature() {
		return $this->signature;
	}

	/**
	 * Returns the signature for the given parameters.
	 * The signature determines whether a PreparedEdit can be re-sued for a given set of parameters.
	 *
	 * @param RevisionSlots $newContent
	 * @param UserIdentity $user
	 * @param RevisionRecord $baseRevision
	 * @param RevisionRecord|null $newRevision
	 * @return bool
	 */
	public static function makeSignature(
		Title $title,
		RevisionSlots $newContentSlots,
		UserIdentity $user,
		RevisionRecord $baseRevision = null,
		RevisionRecord $newRevision = null
	) {
		// NOTE: this fails to consider differences in content model, see T185793
		$sig = $title->getPrefixedDBkey();
		$sig .= '.';
		$sig .= $newContentSlots->computeSha1();
		$sig .= '.';
		$sig .= $user->getName();
		$sig .= '.';
		$sig .= $newRevision ? $newRevision->getId() : 0;

		return $sig;
	}

	/**
	 * @return RevisionSlots
	 */
	public function getTransformedContentSlots() {
		return $this->transformedContentSlots;
	}

	/**
	 * @return ParserOutput
	 */
	public function getParserOutput() {
		return $this->output;
	}

	/**
	 * @return ParserOptions
	 */
	public function getParserOptions() {
		return $this->popts;
	}



}
