<?php
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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\User\UserIdentity;
use ParserOptions;
use Title;
use Wikimedia\Parsoid\Config\Api\PageConfig as ApiPageConfig;
use WikitextContent;

/**
 * Helper class used by MediaWiki to create Parsoid PageConfig objects.
 *
 * @since 1.39
 */
class PageConfigFactory extends \Wikimedia\Parsoid\Config\PageConfigFactory {

	/** @var RevisionStore */
	private $revisionStore;

	/** @var SlotRoleRegistry */
	private $slotRoleRegistry;

	/**
	 * @param RevisionStore $revisionStore
	 * @param SlotRoleRegistry $slotRoleRegistry
	 */
	public function __construct(
		RevisionStore $revisionStore,
		SlotRoleRegistry $slotRoleRegistry
	) {
		$this->revisionStore = $revisionStore;
		$this->slotRoleRegistry = $slotRoleRegistry;
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
	 * @param ?string $pagelanguageOverride
	 * @param ?array $parsoidSettings Used to enable the debug API if requested
	 * @return \Wikimedia\Parsoid\Config\PageConfig
	 */
	public function create(
		PageIdentity $pageId,
		?UserIdentity $user = null,
		$revision = null,
		?string $unused = null, /* Added to mollify CI with cross-repo uses */
		?string $pagelanguageOverride = null,
		?array $parsoidSettings = null
	): \Wikimedia\Parsoid\Config\PageConfig {
		$title = Title::castFromPageIdentity( $pageId );
		'@phan-var Title $title';

		if ( !empty( $parsoidSettings['debugApi'] ) ) {
			if ( $revision === null ) {
				throw new \InvalidArgumentException(
					"Revision not provided. Cannot lookup revision via debug API." );
			}

			$content = $revision->getContent( SlotRecord::MAIN );
			if ( $content instanceof WikitextContent ) {
				$wtContent = $content->getText();
				return ApiPageConfig::fromSettings( $parsoidSettings, [
					"title" => $title->getPrefixedText(),
					"pageContent" => $wtContent,
					"pageLanguage" => $pagelanguageOverride,
					"revid" => $revision->getId(),
					"loadData" => true,
				] );
			} else {
				throw new \UnexpectedValueException(
					"Non-wikitext content models not supported by debug API" );
			}
		}

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
					$revision, RevisionStore::READ_LATEST
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
			throw new RevisionAccessException( 'Not an available content version.' );
		}

		$parserOptions =
			$user
			? ParserOptions::newFromUser( $user )
			: ParserOptions::newFromAnon();

		// Turn off some options since Parsoid/JS currently doesn't
		// do anything with this. As we proceed with closer integration,
		// we can figure out if there is any value to these limit reports.
		$parserOptions->setOption( 'enableLimitReport', false );

		$slotRoleHandler = $this->slotRoleRegistry->getRoleHandler( SlotRecord::MAIN );
		return new PageConfig(
			$parserOptions,
			$slotRoleHandler,
			$title,
			$revisionRecord,
			$pagelanguageOverride
		);
	}

}
