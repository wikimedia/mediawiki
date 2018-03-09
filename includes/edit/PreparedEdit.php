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
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\User\UserIdentity;
use ParserOptions;
use ParserOutput;
use Title;
use Wikimedia\Assert\Assert;

/**
 * Represents information returned by PageUpdater::prepareEdit()
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
	 * Taken from the $modifiers['revid'] field for backward compatibility.
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
	 * Parser output, combined for all slots.
	 *
	 * @var ParserOutput
	 * @deprecated since 1.31, use getParserOutput() instead
	 * @todo make private in 1.32
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

	/**
	 * @var Title
	 * @todo should be some kind of PageIdentity
	 */
	private $title;

	/** @var RevisionSlots */
	private $transformedContentSlots;

	/**
	 * @var string
	 */
	private $signature;

	/**
	 * @var object[] Per-slot cache, values are anonymous objects with two fields:
	 *      - output: the ParserOutput
	 *      - hasHtml: whether the ParserOutput contains HTML.
	 */
	private $slotParserOutput = [];

	/**
	 * @note This constructor should not be called directly by application logic.
	 * Instances of PreparedEdit should only be constructed by PageUpdater::prepareEdit.
	 * The parameter list is subject to change without notice.
	 * @internal
	 *
	 * @param Title $title
	 * @param RevisionSlots $newContentSlots Content of the new revision, including inherited slots.
	 *        The new slots are typically given before PST. This allows getSignature() to be
	 *        compared against the result of makeSignature() with a set of pre-PST slots later.
	 * @param RevisionSlots $transformedContentSlots Content of the new revision, after pre-safe
	 *        transform, including inherited slots.
	 * @param UserIdentity $pstUser The user identity used for PST. May be null if no PST was applied.
	 * @param ParserOptions $popts
	 * @param ParserOutput $output The combined output to be placed in the ParserCache, for
	 *        use in standard page views.
	 * @param int $revId The revision ID, or 0 if the revision has not yet been saved.
	 */
	public function __construct(
		$signature,
		Title $title,
		RevisionSlots $transformedContentSlots,
		UserIdentity $pstUser = null,
		ParserOptions $popts,
		ParserOutput $output,
		$revId
	) {
		// TODO: $title should really be some kind of PageIdentity

		Assert::parameter(
			$transformedContentSlots->hasSlot( 'main' ),
			'$transformedContentSlots',
			'must contain main slot.'
		);

		$this->title = $title;
		$this->transformedContentSlots = $transformedContentSlots;

		$this->revid = $revId;
		$this->popts = $popts;
		$this->output = $output;

		// Backwards compatibility, remove in 1.32:
		$this->newContent = $newContentSlots->hasSlot( 'main' )
			? $newContentSlots->getContent( 'main' )
			: null;
		$this->pstContent = $transformedContentSlots->getContent( 'main' );
		$this->oldContent = null;
		$this->format = null;

		$this->signature = self::makeSignature(
			$title,
			$newContentSlots,
			$pstUser,
			$revId
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
	 * The signature determines whether a PreparedEdit can be re-used for a given set of parameters.
	 *
	 * @param LinkTarget $title
	 * @param RevisionSlots $newContentSlots Content of the new revision, including inherited slots.
	 *        The new slots are typically given before PST. This allows the signature returned
	 *        to be compared to the vlaue of getSignature() of an existing PreparedEdit.
	 * @param UserIdentity $pstUser The user identity used for PST. May be null if no PST was applied.
	 * @param int $revId The revision ID, or 0 if the revision has not yet been saved.
	 *
	 * @return string
	 */
	public static function makeSignature(
		LinkTarget $title,
		RevisionSlots $newContentSlots,
		UserIdentity $pstUser = null,
		$revId
	) {
		$sig = '';
		$sig .= $newContentSlots->computeSha1(); // NOTE: should consider content model, see T185793
		$sig .= '|ns';
		$sig .= $title->getNamespace();
		$sig .= '|';
		$sig .= $title->getDBkey();

		if ( $pstUser ) {
			$sig .= '|u:';
			$sig .= $pstUser->getName();
		}

		if ( $revId ) {
			$sig .= '|r';
			$sig .= $revId;
		}

		return $sig;
	}

	/**
	 * Returns the content of the given slot (with PST applied).
	 *
	 * @return Content
	 */
	public function getContent( $role ) {
		// FIXME: audience check!
		return $this->transformedContentSlots->getContent( $role );
	}

	/**
	 * Returns all role names of all slots in this prepared edit, including inherited slots.
	 *
	 * @return string[]
	 */
	public function getSlotRoles() {
		return $this->transformedContentSlots->getSlotRoles();
	}

	/**
	 * Returns the combined output to be placed in the ParserCache, for
	 * use in standard page views.
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput() {
		return $this->output;
	}

	/**
	 * Returns the ParserOptions used to generate the ParserOutput obtainable from getOutput(),
	 * for constructing a ParserCacheKey.
	 *
	 * @return ParserOptions
	 */
	public function getParserOptions() {
		return $this->popts;
	}

	/**
	 * The ID of the Revision the edit created, if known.
	 *
	 * This is set when the PreparedEdit was constructed for a revision that was already saved.
	 * This is not the typical case, but may be triggered when doEditUpdates finds one of
	 * the vary-* flags set in the ParserOutput, indicating that the output depends on
	 * the revision ID.
	 *
	 * @return int
	 */
	public function getRevisionId() {
		return $this->revid;
	}

	/**
	 * The page this PreparedEdit was created for.
	 *
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $role
	 *
	 * @throws RevisionAccessException if $role is not known slot role, see getSlotRoles().
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role, $generateHtml ) {
		if ( isset( $this->slotParserOutput[$role] ) ) {
			$entry = $this->slotParserOutput[$role];
			if ( $entry->hasHtml || !$generateHtml ) {
				return $entry->output;
			}
		}

		$content = $this->getContent( $role );

		$output = $content->getParserOutput( $this->getTitle(),
			$this->getRevisionId(),
			$this->getParserOptions(),
			$generateHtml
		);

		$this->slotParserOutput[$role] = (object)[
			'output' => $output,
			'hasHtml' => $generateHtml
		];

		return $output;
	}

}
