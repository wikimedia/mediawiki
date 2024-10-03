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

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;

/**
 * MediaWiki service for getting rendered page content.
 *
 * This is very similar to ParserOutputAccess and only exists as a
 * separate class as an interim solution and should be removed soon.
 *
 * It is different from ParserOutputAccess in two aspects:
 * - it forces Parsoid to be used when possible
 * - it supports on-the-fly parsing through parseUncacheable()
 *
 * @since 1.39
 * @unstable
 * @deprecated since 1.43
 */
class ParsoidOutputAccess {
	private ParsoidParserFactory $parsoidParserFactory;
	private PageLookup $pageLookup;
	private RevisionLookup $revisionLookup;
	private ParserOutputAccess $parserOutputAccess;
	private SiteConfig $siteConfig;
	private IContentHandlerFactory $contentHandlerFactory;

	/**
	 * @param ParsoidParserFactory $parsoidParserFactory
	 * @param ParserOutputAccess $parserOutputAccess
	 * @param PageLookup $pageLookup
	 * @param RevisionLookup $revisionLookup
	 * @param SiteConfig $siteConfig
	 * @param IContentHandlerFactory $contentHandlerFactory
	 */
	public function __construct(
		ParsoidParserFactory $parsoidParserFactory,
		ParserOutputAccess $parserOutputAccess,
		PageLookup $pageLookup,
		RevisionLookup $revisionLookup,
		SiteConfig $siteConfig,
		IContentHandlerFactory $contentHandlerFactory
	) {
		$this->parsoidParserFactory = $parsoidParserFactory;
		$this->parserOutputAccess = $parserOutputAccess;
		$this->pageLookup = $pageLookup;
		$this->revisionLookup = $revisionLookup;
		$this->siteConfig = $siteConfig;
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	/**
	 * @param PageIdentity $page
	 * @param ParserOptions $parserOpts
	 * @param RevisionRecord|int|null $revision
	 * @param int $options See the OPT_XXX constants
	 * @param bool $lenientRevHandling
	 *
	 * @return Status<ParserOutput>
	 * @deprecated since 1.43
	 */
	public function getParserOutput(
		PageIdentity $page,
		ParserOptions $parserOpts,
		$revision = null,
		int $options = 0,
		bool $lenientRevHandling = false
	): Status {
		wfDeprecated( __METHOD__, '1.43' );
		[ $page, $revision, $uncacheable ] = $this->resolveRevision( $page, $revision, $lenientRevHandling );

		try {
			if ( $uncacheable ) {
				$options |= ParserOutputAccess::OPT_NO_UPDATE_CACHE;
			}

			$this->adjustParserOptions( $revision, $parserOpts );
			$status = $this->parserOutputAccess->getParserOutput(
				$page, $parserOpts, $revision, $options
			);
		} catch ( ClientError $e ) {
			$status = Status::newFatal( 'parsoid-client-error', $e->getMessage() );
		} catch ( ResourceLimitExceededException $e ) {
			$status = Status::newFatal( 'parsoid-resource-limit-exceeded', $e->getMessage() );
		}
		return $status;
	}

	/**
	 * @param PageIdentity $page
	 * @param ParserOptions $parserOpts
	 * @param RevisionRecord|int|null $revision
	 * @param bool $lenientRevHandling
	 *
	 * @return ?ParserOutput
	 * @deprecated since 1.43
	 */
	public function getCachedParserOutput(
		PageIdentity $page,
		ParserOptions $parserOpts,
		$revision = null,
		bool $lenientRevHandling = false
	): ?ParserOutput {
		wfDeprecated( __METHOD__, '1.43' );
		[ $page, $revision, $ignored ] = $this->resolveRevision( $page, $revision, $lenientRevHandling );

		$this->adjustParserOptions( $revision, $parserOpts );
		return $this->parserOutputAccess->getCachedParserOutput( $page, $parserOpts, $revision );
	}

	/**
	 * This is to be called only for parsing posted wikitext that is actually
	 * not part of any real revision.
	 *
	 * @param PageIdentity $page
	 * @param ParserOptions $parserOpts
	 * @param RevisionRecord|int|null $revision
	 * @param bool $lenientRevHandling
	 *
	 * @return Status
	 * @deprecated since 1.43
	 */
	public function parseUncacheable(
		PageIdentity $page,
		ParserOptions $parserOpts,
		$revision,
		bool $lenientRevHandling = false
	): Status {
		wfDeprecated( __METHOD__, '1.43' );
		// NOTE: If we have a RevisionRecord already, just use it, there is no need to resolve $page to
		//       a PageRecord (and it may not be possible if the page doesn't exist).
		if ( !$revision instanceof RevisionRecord ) {
			[ $page, $revision, $ignored ] = $this->resolveRevision( $page, $revision, $lenientRevHandling );
		}

		// Enforce caller expectation
		$revId = $revision->getId();
		if ( $revId !== 0 && $revId !== null ) {
			return Status::newFatal( 'parsoid-revision-access',
				"parseUncacheable should not be called for a real revision" );
		}

		try {
			// Since we aren't caching this output, there is no need to
			// call setUseParsoid() here.
			$parser = $this->parsoidParserFactory->create();
			$parserOutput = $this->parsoidParserFactory->create()->parseFakeRevision(
				$revision, $page, $parserOpts );
			$parserOutput->updateCacheExpiry( 0 ); // Ensure this isn't accidentally cached
			// set up (fake) render id and other properties
			$globalIdGenerator = MediaWikiServices::getInstance()->getGlobalIdGenerator();
			$parserOutput->setRenderId( $globalIdGenerator->newUUIDv1() );
			$parserOutput->setCacheRevisionId( $revision->getId() );
			$parserOutput->setRevisionTimestamp( $revision->getTimestamp() );
			$parserOutput->setCacheTime( wfTimestampNow() );

			$status = Status::newGood( $parserOutput );
		} catch ( RevisionAccessException $e ) {
			return Status::newFatal( 'parsoid-revision-access', $e->getMessage() );
		} catch ( ClientError $e ) {
			$status = Status::newFatal( 'parsoid-client-error', $e->getMessage() );
		} catch ( ResourceLimitExceededException $e ) {
			$status = Status::newFatal( 'parsoid-resource-limit-exceeded', $e->getMessage() );
		}
		return $status;
	}

	/**
	 * @param PageIdentity $page
	 * @param RevisionRecord|int|null $revision
	 * @param bool $lenientRevHandling
	 *
	 * @return array [ PageRecord $page, RevisionRecord $revision ]
	 */
	private function resolveRevision( PageIdentity $page, $revision, bool $lenientRevHandling = false ): array {
		$uncacheable = false;
		if ( !$page instanceof PageRecord ) {
			$name = "$page";
			$page = $this->pageLookup->getPageByReference( $page );
			if ( !$page ) {
				throw new RevisionAccessException(
					'Page {name} not found',
					[ 'name' => $name ]
				);
			}
		}

		$revision ??= $page->getLatest();

		if ( is_int( $revision ) ) {
			$revId = $revision;
			$revision = $this->revisionLookup->getRevisionById( $revId );

			if ( !$revision ) {
				throw new RevisionAccessException(
					'Revision {revId} not found',
					[ 'revId' => $revId ]
				);
			}
		}

		if ( $page->getId() !== $revision->getPageId() ) {
			if ( $lenientRevHandling ) {
				$page = $this->pageLookup->getPageById( $revision->getPageId() );
				if ( !$page ) {
					// This should ideally never trigger!
					throw new \RuntimeException(
						"Unexpected NULL page for pageid " . $revision->getPageId() .
						" from revision " . $revision->getId()
					);
				}
				// Don't cache this!
				$uncacheable = true;
			} else {
				throw new RevisionAccessException(
					'Revision {revId} does not belong to page {name}',
					[ 'name' => $page->getDBkey(), 'revId' => $revision->getId() ]
				);
			}
		}

		return [ $page, $revision, $uncacheable ];
	}

	private function adjustParserOptions( RevisionRecord $revision, ParserOptions $parserOpts ): void {
		$mainSlot = $revision->getSlot( SlotRecord::MAIN );
		$contentModel = $mainSlot->getModel();
		if ( $this->siteConfig->supportsContentModel( $contentModel ) ) {
			// Since we know Parsoid supports this content model, explicitly
			// call ParserOptions::setUseParsoid. This ensures that when
			// we query the parser-cache, the right cache key is called.
			// This is an optional transition step to using ParserOutputAccess.
			$parserOpts->setUseParsoid();
		}
	}
}
