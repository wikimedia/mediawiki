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
use InvalidArgumentException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\User\UserIdentity;
use ParserOptions;
use ParserOutput;
use Wikimedia\Assert\Assert;

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
	 * Parser output, combined for all slots.
	 *
	 * @var ParserOutput
	 * @deprecated since 1.31, use getCombinedParserOutput() instead
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

	/** @var RevisionSlots */
	private $newContentSlots;

	/** @var RevisionSlots */
	private $transformedContentSlots;

	/** @var ParserOutput[] */
	private $slotParserOutput;

	/**
	 * @var string
	 */
	private $signature;

	/**
	 * @param LinkTarget $title
	 * @param RevisionSlots $newContentSlots Content of the new revision, before pre-safe
	 *        transform, excluding inherited slots.
	 * @param RevisionSlots $transformedContentSlots Content of the new revision, after pre-safe
	 *        transform, including inherited slots.
	 * @param UserIdentity $user
	 * @param ParserOptions $popts
	 * @param ParserOutput[] $output Parser output for each slot, including inherited slots.
	 * @param int $revid Revision ID, if ParserOutput depends on it.
	 */
	public function __construct(
		LinkTarget $title,
		RevisionSlots $newContentSlots,
		RevisionSlots $transformedContentSlots,
		UserIdentity $user,
		ParserOptions $popts,
		array $slotParserOutput,
		ParserOutput $combinedOutput,
		$revid = 0
	) {
		Assert::parameter(
			$transformedContentSlots->hasSlot( 'main' ),
			'$transformedContentSlots',
			'must contain main slot.'
		);
		Assert::parameter(
			isset( $slotParserOutput['main'] ),
			'$slotParserOutput',
			'must contain main slot.'
		);
		Assert::parameterType( 'integer', $revid, '$revid' );

		$this->newContentSlots = $newContentSlots;
		$this->transformedContentSlots = $transformedContentSlots;
		$this->slotParserOutput = $slotParserOutput;

		$this->revid = $revid;
		$this->popts = $popts;

		// Backwards compatibility, remove in 1.32:
		$this->output = $combinedOutput;
		$this->newContent = $newContentSlots->hasSlot( 'main' )
			? $newContentSlots->getContent( 'main' )
			: null;
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
	 * The signature determines whether a PreparedEdit can be re-used for a given set of parameters.
	 *
	 * @param RevisionSlots $newContent
	 * @param UserIdentity $user
	 * @param int $revid Revision ID, if ParserOutput depends on it. 0 otherwise.
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
	 * Returns all role names of all slots in this prepared edit, including inherited slots.
	 *
	 * @return string[]
	 */
	public function getSlotRoles() {
		return $this->transformedContentSlots->getSlotRoles();
	}

	/**
	 * Returns the parser output for a given slot.
	 * All slot roles returned by getSlotRoles() should be supported. This includes inherited slots.
	 *
	 * @param string $role slot role name
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput( $role ) {
		if ( !isset( $this->slotParserOutput[$role] ) ) {
			throw new InvalidArgumentException( 'No parser output defined for ' . $role );
		}
		return $this->slotParserOutput[$role];
	}

	/**
	 * Returns the combined parser output for all slots.
	 *
	 * @return ParserOutput
	 */
	public function getCombinedParserOutput() {
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

}
