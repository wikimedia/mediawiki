<?php
declare( strict_types = 1 );
/**
 * Copyright (C) 2011-2022 Wikimedia Foundation and others.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace MediaWiki\Parser\Parsoid\Config;

use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Helper class used by MediaWiki to create Parsoid PageConfig objects.
 *
 * @since 1.39
 * @internal
 */
class PageConfigFactory extends \Wikimedia\Parsoid\Config\PageConfigFactory {
	private RevisionStore $revisionStore;
	private SlotRoleRegistry $slotRoleRegistry;
	private LanguageFactory $languageFactory;

	/**
	 * @param RevisionStore $revisionStore
	 * @param SlotRoleRegistry $slotRoleRegistry
	 * @param LanguageFactory $languageFactory
	 */
	public function __construct(
		RevisionStore $revisionStore,
		SlotRoleRegistry $slotRoleRegistry,
		LanguageFactory $languageFactory
	) {
		$this->revisionStore = $revisionStore;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->languageFactory = $languageFactory;
	}

	/**
	 * Create a new PageConfig.
	 *
	 * Note that Parsoid isn't supposed to use the user context by design; all
	 * user-specific processing is expected to be introduced as a post-parse
	 * transform. The $user parameter is therefore usually null, especially
	 * in background job parsing, although there are corner cases during
	 * extension processing where a non-null $user could affect the output.
	 *
	 * @param PageIdentity $pageId The page represented by the PageConfig.
	 * @param ?UserIdentity $user User who is doing rendering (for parsing options).
	 * @param int|RevisionRecord|null $revision Revision id or a revision record
	 * @param ?string $unused
	 * @param ?Bcp47Code $pageLanguageOverride
	 * @param bool $ensureAccessibleContent If true, ensures that we can get content
	 *   from the newly constructed pageConfig's RevisionRecord and throws a
	 *   RevisionAccessException if not.
	 * @return \Wikimedia\Parsoid\Config\PageConfig
	 * @throws RevisionAccessException
	 * @deprecated since 1.45; use ::createFromParserOptions() instead
	 */
	public function create(
		PageIdentity $pageId,
		?UserIdentity $user = null,
		$revision = null,
		?string $unused = null, /* Added to mollify CI with cross-repo uses */
		?Bcp47Code $pageLanguageOverride = null,
		bool $ensureAccessibleContent = false
	): \Wikimedia\Parsoid\Config\PageConfig {
		wfDeprecated( __METHOD__, '1.45' );
		if ( $unused !== null ) {
			wfDeprecated( __METHOD__ . ' with non-null 4th arg', '1.40' );
		}

		$parserOptions =
			$user
			? ParserOptions::newFromUser( $user )
			: ParserOptions::newFromAnon();

		return $this->createFromParserOptions(
			$parserOptions, $pageId, $revision,
			$pageLanguageOverride, $ensureAccessibleContent
		);
	}

	/**
	 * Create a new PageConfig.
	 *
	 * Note that Parsoid isn't supposed to use the user context by design; all
	 * user-specific processing is expected to be introduced as a post-parse
	 * transform. The $user parameter is therefore usually null, especially
	 * in background job parsing, although there are corner cases during
	 * extension processing where a non-null $user could affect the output.
	 *
	 * @param ParserOptions $parserOptions (See note above about user identity
	 *   in the parser options.)
	 * @param PageIdentity $pageId The page represented by the PageConfig.
	 * @param int|RevisionRecord|null $revision Revision id or a revision record
	 * @param ?Bcp47Code $pageLanguageOverride
	 * @param bool $ensureAccessibleContent If true, ensures that we can get content
	 *   from the newly constructed pageConfig's RevisionRecord and throws a
	 *   RevisionAccessException if not.
	 * @return \Wikimedia\Parsoid\Config\PageConfig
	 * @throws RevisionAccessException
	 */
	public function createFromParserOptions(
		ParserOptions $parserOptions,
		PageIdentity $pageId,
		$revision = null,
		?Bcp47Code $pageLanguageOverride = null,
		bool $ensureAccessibleContent = false
	): \Wikimedia\Parsoid\Config\PageConfig {
		$title = Title::newFromPageIdentity( $pageId );

		if ( $revision === null ) {
			// Fetch the 'latest' revision for the given title.
			// Note: This initial fetch of the page context revision is
			// *not* using Parser::fetchCurrentRevisionRecordOfTitle()
			// (which usually invokes Parser::statelessFetchRevisionRecord
			// and from there RevisionStore::getKnownCurrentRevision)
			// because we don't have a Parser object to give to that callback.
			// We could create one if needed for greater compatibility.
			$revisionRecord = $this->revisionStore->getKnownCurrentRevision(
				$title
			) ?: null;
			// Note that $revisionRecord could still be null here if no
			// page with that $title yet exists.
		} elseif ( !is_int( $revision ) ) {
			$revisionRecord = $revision;
		} else {
			if ( $revision === 0 ) {
				// The client may explicitly provide 0 as the revision ID to indicate that
				// the content doesn't belong to any saved revision, and provide wikitext
				// in some way. Calling code should handle this case and provide a (fake)
				// RevisionRecord based on the data in the request. If we get here, the
				// code processing the request didn't handle this case properly.
				throw new \UnexpectedValueException(
					"Got revision ID 0 indicating unsaved content. " .
					"Unsaved content must be provided as a RevisionRecord object."
				);
			}

			// Fetch the correct revision record by the supplied id.
			// This accesses the replica DB and may (or may not) fail over to
			// the primary DB if the revision isn't found.
			$revisionRecord = $this->revisionStore->getRevisionById( $revision );
			if ( $revisionRecord === null ) {
				// This revision really ought to exist.  Check the primary DB.
				// This *could* cause two requests to the primary DB if there
				// were pending writes, but this codepath should be very rare.
				// [T259855]
				$revisionRecord = $this->revisionStore->getRevisionById(
					$revision, IDBAccessObject::READ_LATEST
				);
				$success = ( $revisionRecord !== null ) ? 'success' : 'failure';
				LoggerFactory::getInstance( 'Parsoid' )->error(
					"Retried revision fetch after failure: {$success}", [
						'id' => $revision,
						'title' => $title->getPrefixedText(),
					]
				);
			}
			if ( $revisionRecord === null ) {
				throw new RevisionAccessException( "Can't find revision {$revision}" );
			}
		}

		// If we have a revision record, check that we are allowed to see it.
		// Mirrors the check from RevisionRecord::getContent
		if (
			$revisionRecord !== null &&
			!$revisionRecord->audienceCan(
				RevisionRecord::DELETED_TEXT, RevisionRecord::FOR_PUBLIC
			)
		) {
			throw new SuppressedDataException( 'Not an available content version.' );
		}

		// Parsoid parser options should always have useParsoid set
		$parserOptions->setUseParsoid();

		$slotRoleHandler = $this->slotRoleRegistry->getRoleHandler( SlotRecord::MAIN );

		if ( $pageLanguageOverride ) {
			$pageLanguage = $this->languageFactory->getLanguage( $pageLanguageOverride );
			$parserOptions->setTargetLanguage( $pageLanguage );
		} else {
			$pageLanguage = $title->getPageLanguage();
		}

		$pageConfig = new PageConfig(
			$parserOptions,
			$slotRoleHandler,
			$title,
			$revisionRecord,
			$pageLanguage,
			$pageLanguage->getDir()
		);

		if ( $ensureAccessibleContent ) {
			if ( $revisionRecord === null ) {
				// T234549
				throw new RevisionAccessException( 'The specified revision does not exist.' );
			}
			// Try to get the content so that we can fail early.  Otherwise,
			// a RevisionAccessException is thrown.  It's expensive, but the
			// result will be cached for later calls.
			$pageConfig->getRevisionContent()->getContent( SlotRecord::MAIN );
		}

		return $pageConfig;
	}

}
