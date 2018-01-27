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
use MediaWiki\Linker\LinkTarget;
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
	 * Unused, always null since 1.31.
	 *
	 * @var null
	 * @deprecated since 1.31
	 * @todo remove in 1.32
	 */
	public $timestamp;

	/**
	 * The revision ID, if the ParserOutput depends on it.
	 *
	 * @note As of 1.31, the only known user is the FlaggedRevs extension.
	 *
	 * @var int
	 * @deprecated since 1.31, use getRevisionId() instead.
	 * @todo make private in 1.32
	 */
	public $revid;

	/**
	 * Content after going through pre-save transform
	 *
	 * @var Content|null
	 * @deprecated since 1.31, use getTransformedContentSlots() instead
	 * @todo remove in 1.32
	 */
	public $pstContent;

	/**
	 * Unused, always null since 1.31.
	 *
	 * @var null
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
	 * @todo remove in 1.32
	 */
	public $newContent;

	/**
	 * Unused, always null since 1.31.
	 *
	 * @var null
	 * @deprecated since 1.31
	 * @todo remove in 1.32
	 */
	public $oldContent;

	/** @var  RevisionSlots */
	private $newContentSlots;

	/** @var  RevisionSlots */
	private $transformedContentSlots;

	/**
	 * @var string
	 */
	private $signature;

	/**
	 * @param LinkTarget $title
	 * @param RevisionSlots $newContentSlots
	 * @param RevisionSlots $transformedContentSlots Content of the new revision, after pre-safe
	 *        transform, including inherited slots.
	 * @param UserIdentity $user
	 * @param ParserOptions $popts
	 * @param ParserOutput $output
	 * @param int $revid Revision ID, if ParserOutput depends on it.
	 */
	public function __construct(
		LinkTarget $title,
		RevisionSlots $newContentSlots,
		RevisionSlots $transformedContentSlots,
		UserIdentity $user,
		ParserOptions $popts,
		ParserOutput $output,
		$revid = 0
	) {
		$this->newContentSlots = $newContentSlots;
		$this->transformedContentSlots = $transformedContentSlots;
		$this->revid = $revid;
		$this->popts = $popts;
		$this->output = $output;

		// Backwards compatibility, remove in 1.32:
		$this->newContent = $newContentSlots->getContent( 'main' );
		$this->pstContent = $transformedContentSlots->getContent( 'main' );
		$this->oldContent = null;
		$this->format = null;

		$this->signature = self::makeSignature(
			$title,
			$newContentSlots,
			$user,
			$revid
		);
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
	 * @param int $revid Revision ID, if ParserOutput depends on it.
	 * @return string
	 */
	public static function makeSignature(
		LinkTarget $title,
		RevisionSlots $newContentSlots,
		UserIdentity $user,
		$revid = 0
	) {
		// TODO: $title should really be some kind of PageIdentity

		$sig = '';
		$sig .= $newContentSlots->computeSha1(); // NOTE: should consider content model, see T185793
		$sig .= '|';
		$sig .= $user->getName();
		$sig .= '|ns';
		$sig .= $title->getNamespace();
		$sig .= '|';
		$sig .= $title->getDBkey();

		if ( $revid ) {
			$sig .= '|r';
			$sig .= $revid;
		}

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

	/**
	 * The ID of the Revision the edit created, if known.
	 *
	 * This is typically only set if ParserOutput actually depends on the revision ID,
	 * that is if the vary-revision flag is set.
	 *
	 * @return int
	 */
	public function getRevisionId() {
		return $this->revid;
	}

}
