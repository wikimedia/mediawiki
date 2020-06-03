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

namespace MediaWiki\Revision;

use Content;
use InvalidArgumentException;
use LogicException;
use ParserOptions;
use ParserOutput;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Title;
use User;
use Wikimedia\Assert\Assert;

/**
 * RenderedRevision represents the rendered representation of a revision. It acts as a lazy provider
 * of ParserOutput objects for the revision's individual slots, as well as a combined ParserOutput
 * of all slots.
 *
 * @since 1.32
 */
class RenderedRevision implements SlotRenderingProvider {

	/**
	 * @var Title
	 */
	private $title;

	/** @var RevisionRecord */
	private $revision;

	/**
	 * @var ParserOptions
	 */
	private $options;

	/**
	 * @var int Audience to check when accessing content.
	 */
	private $audience = RevisionRecord::FOR_PUBLIC;

	/**
	 * @var User|null The user to use for audience checks during content access.
	 */
	private $forUser = null;

	/**
	 * @var ParserOutput|null The combined ParserOutput for the revision,
	 *      initialized lazily by getRevisionParserOutput().
	 */
	private $revisionOutput = null;

	/**
	 * @var ParserOutput[] The ParserOutput for each slot,
	 *      initialized lazily by getSlotParserOutput().
	 */
	private $slotsOutput = [];

	/**
	 * @var callable Callback for combining slot output into revision output.
	 *      Signature: function ( RenderedRevision $this ): ParserOutput.
	 */
	private $combineOutput;

	/**
	 * @var LoggerInterface For profiling ParserOutput re-use.
	 */
	private $saveParseLogger;

	/**
	 * @note Application logic should not instantiate RenderedRevision instances directly,
	 * but should use a RevisionRenderer instead.
	 *
	 * @param Title $title
	 * @param RevisionRecord $revision The revision to render. The content for rendering will be
	 *        taken from this RevisionRecord. However, if the RevisionRecord is not complete
	 *        according isReadyForInsertion(), but a revision ID is known, the parser may load
	 *        the revision from the database if it needs revision meta data to handle magic
	 *        words like {{REVISIONUSER}}.
	 * @param ParserOptions $options
	 * @param callable $combineOutput Callback for combining slot output into revision output.
	 *        Signature: function ( RenderedRevision $this ): ParserOutput.
	 * @param int $audience Use RevisionRecord::FOR_PUBLIC, FOR_THIS_USER, or RAW.
	 * @param User|null $forUser Required if $audience is FOR_THIS_USER.
	 */
	public function __construct(
		Title $title,
		RevisionRecord $revision,
		ParserOptions $options,
		callable $combineOutput,
		$audience = RevisionRecord::FOR_PUBLIC,
		User $forUser = null
	) {
		$this->title = $title;
		$this->options = $options;

		$this->setRevisionInternal( $revision );

		$this->combineOutput = $combineOutput;
		$this->saveParseLogger = new NullLogger();

		if ( $audience === RevisionRecord::FOR_THIS_USER && !$forUser ) {
			throw new InvalidArgumentException(
				'User must be specified when setting audience to FOR_THIS_USER'
			);
		}

		$this->audience = $audience;
		$this->forUser = $forUser;
	}

	/**
	 * @param LoggerInterface $saveParseLogger
	 */
	public function setSaveParseLogger( LoggerInterface $saveParseLogger ) {
		$this->saveParseLogger = $saveParseLogger;
	}

	/**
	 * @return bool Whether the revision's content has been hidden from unprivileged users.
	 */
	public function isContentDeleted() {
		return $this->revision->isDeleted( RevisionRecord::DELETED_TEXT );
	}

	/**
	 * @return RevisionRecord
	 */
	public function getRevision() {
		return $this->revision;
	}

	/**
	 * @return ParserOptions
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * Sets a ParserOutput to be returned by getRevisionParserOutput().
	 *
	 * @note For internal use by RevisionRenderer only! This method may be modified
	 * or removed without notice per the deprecation policy.
	 *
	 * @internal
	 *
	 * @param ParserOutput $output
	 */
	public function setRevisionParserOutput( ParserOutput $output ) {
		$this->revisionOutput = $output;

		// If there is only one slot, we assume that the combined output is identical
		// with the main slot's output. This is intended to prevent a redundant re-parse of
		// the content in case getSlotParserOutput( SlotRecord::MAIN ) is called, for instance
		// from ContentHandler::getSecondaryDataUpdates.
		if ( $this->revision->getSlotRoles() === [ SlotRecord::MAIN ] ) {
			$this->slotsOutput[ SlotRecord::MAIN ] = $output;
		}
	}

	/**
	 * @param array $hints Hints given as an associative array. Known keys:
	 *      - 'generate-html' => bool: Whether the caller is interested in output HTML (as opposed
	 *        to just meta-data). Default is to generate HTML.
	 * @phan-param array{generate-html?:bool} $hints
	 *
	 * @return ParserOutput
	 */
	public function getRevisionParserOutput( array $hints = [] ) {
		$withHtml = $hints['generate-html'] ?? true;

		if ( !$this->revisionOutput
			|| ( $withHtml && !$this->revisionOutput->hasText() )
		) {
			$output = call_user_func( $this->combineOutput, $this, $hints );

			Assert::postcondition(
				$output instanceof ParserOutput,
				'Callback did not return a ParserOutput object!'
			);

			$this->revisionOutput = $output;
		}

		return $this->revisionOutput;
	}

	/**
	 * @param string $role
	 * @param array $hints Hints given as an associative array. Known keys:
	 *      - 'generate-html' => bool: Whether the caller is interested in output HTML (as opposed
	 *        to just meta-data). Default is to generate HTML.
	 * @phan-param array{generate-html?:bool} $hints
	 *
	 * @throws SuppressedDataException if the content is not accessible for the audience
	 *         specified in the constructor.
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role, array $hints = [] ) {
		$withHtml = $hints['generate-html'] ?? true;

		if ( !isset( $this->slotsOutput[ $role ] )
			|| ( $withHtml && !$this->slotsOutput[ $role ]->hasText() )
		) {
			$content = $this->revision->getContent( $role, $this->audience, $this->forUser );

			if ( !$content ) {
				throw new SuppressedDataException(
					'Access to the content has been suppressed for this audience'
				);
			} else {
				// XXX: allow SlotRoleHandler to control the ParserOutput?
				$output = $this->getSlotParserOutputUncached( $content, $withHtml );

				if ( $withHtml && !$output->hasText() ) {
					throw new LogicException(
						'HTML generation was requested, but '
						. get_class( $content )
						. '::getParserOutput() returns a ParserOutput with no text set.'
					);
				}

				// Detach watcher, to ensure option use is not recorded in the wrong ParserOutput.
				$this->options->registerWatcher( null );
			}

			$this->slotsOutput[ $role ] = $output;
		}

		return $this->slotsOutput[$role];
	}

	/**
	 * @note This method exist to make duplicate parses easier to see during profiling
	 * @param Content $content
	 * @param bool $withHtml
	 * @return ParserOutput
	 */
	private function getSlotParserOutputUncached( Content $content, $withHtml ) {
		return $content->getParserOutput(
			$this->title,
			$this->revision->getId(),
			$this->options,
			$withHtml
		);
	}

	/**
	 * Updates the RevisionRecord after the revision has been saved. This can be used to discard
	 * and cached ParserOutput so parser functions like {{REVISIONTIMESTAMP}} or {{REVISIONID}}
	 * are re-evaluated.
	 *
	 * @note There should be no need to call this for null-edits.
	 *
	 * @param RevisionRecord $rev
	 */
	public function updateRevision( RevisionRecord $rev ) {
		if ( $rev->getId() === $this->revision->getId() ) {
			return;
		}

		if ( $this->revision->getId() ) {
			throw new LogicException( 'RenderedRevision already has a revision with ID '
				. $this->revision->getId() . ', can\'t update to revision with ID ' . $rev->getId() );
		}

		if ( !$this->revision->getSlots()->hasSameContent( $rev->getSlots() ) ) {
			throw new LogicException( 'Cannot update to a revision with different content!' );
		}

		$this->setRevisionInternal( $rev );

		$this->pruneRevisionSensitiveOutput(
			$this->revision->getPageId(),
			$this->revision->getId(),
			$this->revision->getTimestamp()
		);
	}

	/**
	 * Prune any output that depends on the revision ID.
	 *
	 * @param int|bool $actualPageId The actual page id, to check the used speculative page ID
	 *        against; false, to not purge on vary-page-id; true, to purge on vary-page-id
	 *        unconditionally.
	 * @param int|bool $actualRevId The actual rev id, to check the used speculative rev ID
	 *        against,; false, to not purge on vary-revision-id; true, to purge on
	 *        vary-revision-id unconditionally.
	 * @param string|bool $actualRevTimestamp The actual rev timestamp, to check against the
	 *        parser output revision timestamp; false, to not purge on vary-revision-timestamp;
	 *        true, to purge on vary-revision-timestamp unconditionally.
	 */
	private function pruneRevisionSensitiveOutput(
		$actualPageId,
		$actualRevId,
		$actualRevTimestamp
	) {
		if ( $this->revisionOutput ) {
			if ( $this->outputVariesOnRevisionMetaData(
				$this->revisionOutput,
				$actualPageId,
				$actualRevId,
				$actualRevTimestamp
			) ) {
				$this->revisionOutput = null;
			}
		} else {
			$this->saveParseLogger->debug( __METHOD__ . ": no prepared revision output" );
		}

		foreach ( $this->slotsOutput as $role => $output ) {
			if ( $this->outputVariesOnRevisionMetaData(
				$output,
				$actualPageId,
				$actualRevId,
				$actualRevTimestamp
			) ) {
				unset( $this->slotsOutput[$role] );
			}
		}
	}

	/**
	 * @param RevisionRecord $revision
	 */
	private function setRevisionInternal( RevisionRecord $revision ) {
		$this->revision = $revision;

		// Force the parser to use  $this->revision to resolve magic words like {{REVISIONUSER}}
		// if the revision is either known to be complete, or it doesn't have a revision ID set.
		// If it's incomplete and we have a revision ID, the parser can do better by loading
		// the revision from the database if needed to handle a magic word.
		//
		// The following considerations inform the logic described above:
		//
		// 1) If we have a saved revision already loaded, we want the parser to use it, instead of
		// loading it again.
		//
		// 2) If the revision is a fake that wraps some kind of synthetic content, such as an
		// error message from Article, it should be used directly and things like {{REVISIONUSER}}
		// should not expected to work, since there may not even be an actual revision to
		// refer to.
		//
		// 3) If the revision is a fake constructed around a Title, a Content object, and
		// a revision ID, to provide backwards compatibility to code that has access to those
		// but not to a complete RevisionRecord for rendering, then we want the Parser to
		// load the actual revision from the database when it encounters a magic word like
		// {{REVISIONUSER}}, but we don't want to load that revision ahead of time just in case.
		//
		// 4) Previewing an edit to a template should use the submitted unsaved
		// MutableRevisionRecord for self-transclusions in the template's documentation (see T7278).
		// That revision would be complete except for the ID field.
		//
		// 5) Pre-save transform would provide a RevisionRecord that has all meta-data but is
		// incomplete due to not yet having content set. However, since it doesn't have a revision
		// ID either, the below code would still force it to be used, allowing
		// {{subst::REVISIONUSER}} to function as expected.

		if ( $this->revision->isReadyForInsertion() || !$this->revision->getId() ) {
			$title = $this->title;
			$oldCallback = $this->options->getCurrentRevisionRecordCallback();
			$this->options->setCurrentRevisionRecordCallback(
				function ( Title $parserTitle, $parser = null ) use ( $title, $oldCallback ) {
					if ( $title->equals( $parserTitle ) ) {
						return $this->revision;
					} else {
						return call_user_func( $oldCallback, $parserTitle, $parser );
					}
				}
			);
		}
	}

	/**
	 * @param ParserOutput $out
	 * @param int|bool $actualPageId The actual page id, to check the used speculative page ID
	 *        against; false, to not purge on vary-page-id; true, to purge on vary-page-id
	 *        unconditionally.
	 * @param int|bool $actualRevId The actual rev id, to check the used speculative rev ID
	 *        against,; false, to not purge on vary-revision-id; true, to purge on
	 *        vary-revision-id unconditionally.
	 * @param string|bool $actualRevTimestamp The actual rev timestamp, to check against the
	 *        parser output revision timestamp; false, to not purge on vary-revision-timestamp;
	 *        true, to purge on vary-revision-timestamp unconditionally.
	 * @return bool
	 */
	private function outputVariesOnRevisionMetaData(
		ParserOutput $out,
		$actualPageId,
		$actualRevId,
		$actualRevTimestamp
	) {
		$logger = $this->saveParseLogger;
		$varyMsg = __METHOD__ . ": cannot use prepared output for '{title}'";
		$context = [ 'title' => $this->title->getPrefixedText() ];

		if ( $out->getFlag( 'vary-revision' ) ) {
			// If {{PAGEID}} resolved to 0, then that word need to resolve to the actual page ID
			$logger->info( "$varyMsg (vary-revision)", $context );
			return true;
		} elseif (
			$out->getFlag( 'vary-revision-id' )
			&& $actualRevId !== false
			&& ( $actualRevId === true || $out->getSpeculativeRevIdUsed() !== $actualRevId )
		) {
			$logger->info( "$varyMsg (vary-revision-id and wrong ID)", $context );
			return true;
		} elseif (
			$out->getFlag( 'vary-revision-timestamp' )
			&& $actualRevTimestamp !== false
			&& ( $actualRevTimestamp === true ||
				$out->getRevisionTimestampUsed() !== $actualRevTimestamp )
		) {
			$logger->info( "$varyMsg (vary-revision-timestamp and wrong timestamp)", $context );
			return true;
		} elseif (
			$out->getFlag( 'vary-page-id' )
			&& $actualPageId !== false
			&& ( $actualPageId === true || $out->getSpeculativePageIdUsed() !== $actualPageId )
		) {
			$logger->info( "$varyMsg (vary-page-id and wrong ID)", $context );
			return true;
		} elseif ( $out->getFlag( 'vary-revision-exists' ) ) {
			// If {{REVISIONID}} resolved to '', it now needs to resolve to '-'.
			// Note that edit stashing always uses '-', which can be used for both
			// edit filter checks and canonical parser cache.
			$logger->info( "$varyMsg (vary-revision-exists)", $context );
			return true;
		} elseif (
			$out->getFlag( 'vary-revision-sha1' ) &&
			$out->getRevisionUsedSha1Base36() !== $this->revision->getSha1()
		) {
			// If a self-transclusion used the proposed page text, it must match the final
			// page content after PST transformations and automatically merged edit conflicts
			$logger->info( "$varyMsg (vary-revision-sha1 with wrong SHA-1)", $context );
			return true;
		}

		// NOTE: In the original fix for T135261, the output was discarded if 'vary-user' was
		// set for a null-edit. The reason was that the original rendering in that case was
		// targeting the user making the null-edit, not the user who made the original edit,
		// causing {{REVISIONUSER}} to return the wrong name.
		// This case is now expected to be handled by the code in RevisionRenderer that
		// constructs the ParserOptions: For a null-edit, setCurrentRevisionRecordCallback is
		// called with the old, existing revision.
		$logger->debug( __METHOD__ . ": reusing prepared output for '{title}'", $context );
		return false;
	}
}
