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

use InvalidArgumentException;
use MediaWiki\Storage\RevisionRecord;
use ParserOptions;
use ParserOutput;
use User;

/**
 * FIXME
 *
 * @since 1.32
 */
class RenderedRevision {

	/** @var RevisionRecord */
	private $revision;

	/**
	 * @var ParserOptions
	 */
	private $options;

	/**
	 * @var callable
	 */
	private $outputCombiner;

	/**
	 * @var SlotRoleRegistry
	 */
	private $roleRegistry;

	/**
	 * @var ParserOutput|null
	 */
	private $revisionOutput = null;

	/**
	 * @var ParserOutput[]
	 */
	private $slotsOutput = [];

	/**
	 * @param RevisionRecord $revision
	 * @param ParserOptions $options
	 * @param SlotRoleRegistry $roleRegistry
	 * @param callable $outputCombiner
	 * @param User $forUser
	 */
	public function __construct(
		RevisionRecord $revision,
		ParserOptions $options,
		SlotRoleRegistry $roleRegistry,
		callable $outputCombiner
	) {
		$this->revision = $revision;
		$this->options = $options;
		$this->roleRegistry = $roleRegistry;
		$this->outputCombiner = $outputCombiner;
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
	 * @return ParserOutput
	 */
	public function getRevisionParserOutput() {
		// FIXME: if uncached...
		$this->revisionOutput = call_user_func( $this->outputCombiner, $this );

		return $this->revisionOutput;
	}

	/**
	 * @param string $role
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role ) {
		$handler = $this->roleRegistry->getRoleHandler( $role );

		// FIXME: if uncached...
		$output = $handler->getSlotOutput(
			$this->revision,
			$this->options,
			SlotRoleHandler::FOR_COMBINED_OUTPUT
		);

		// FIXME: do this?...
		$output->setCacheTime( $this->getTimestampNow() );

		$this->slotsOutput[$role] = $output;
		return $this->slotsOutput[$role];
	}

	public function updateRevision( RevisionRecord $rev ) {
		if ( $rev->getId() === $this->revision->getId() ) {
			return;
		}

		if ( $this->revision->getId() ) {
			throw new InvalidArgumentException( 'RenderedRevision already has a revision with ID '
				. $this->revision->getId(), ', can\'t update to revision with ID ' . $rev->getId() );
		}

		$this->revision = $rev;

		// Prune any output that depends on the revision ID.
		if ( $this->revisionOutput ) {
			if ( $this->outputVariesOnRevisionMetaData( $this->revisionOutput, __METHOD__ ) ) {
				$this->revisionOutput = null;
			} else {
				// FIXME log
			}
		}

		if ( $this->slotsOutput ) {
			foreach ( $this->slotsOutput as $role => $output ) {
				if ( $this->outputVariesOnRevisionMetaData( $output, __METHOD__ ) ) {
					unset( $this->slotsOutput[$role] );
				} else {
					// FIXME log
				}
			}
		}

	}

	/**
	 * @param ParserOutput $out
	 * @param string $method
	 * @return bool
	 */
	private function outputVariesOnRevisionMetaData( ParserOutput $out, $method = __METHOD__ ) {
		if ( $out->getFlag( 'vary-revision' ) ) {
			// XXX: Just keep the output if the speculative revision ID was correct, like below?
			$this->saveParseLogger->info(
				"$method: Prepared output has vary-revision...\n"
			);
			return true;
		} elseif ( $out->getFlag( 'vary-revision-id' )
			&& $out->getSpeculativeRevIdUsed() !== $this->revision->getId()
		) {
			$this->saveParseLogger->info(
				"$method: Prepared output has vary-revision-id with wrong ID...\n"
			);
			return true;
		} elseif ( $out->getFlag( 'vary-user' )
			&& !$this->options['changed']
		) {
			// When Alice makes a null-edit on top of Bob's edit,
			// {{REVISIONUSER}} must resolve to "Bob", not "Alice", see T135261.
			// TODO: to avoid this, we should check for null-edits in makeCanonicalparserOptions,
			// and set setCurrentRevisionCallback to return the existing revision when appropriate.
			// See also the comment there [dk 2018-05]
			$this->saveParseLogger->info(
				"$method: Prepared output has vary-user and is null-edit...\n"
			);
			return true;
		} else {
			wfDebug( "$method: Keeping prepared output...\n" );
			return false;
		}
	}

}
