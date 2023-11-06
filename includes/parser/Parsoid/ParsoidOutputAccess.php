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

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MWUnknownContentModelException;
use ParserOptions;
use ParserOutput;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;

/**
 * MediaWiki service for getting Parsoid Output objects.
 * @since 1.39
 * @unstable
 */
class ParsoidOutputAccess {
	/**
	 * @internal
	 * @var string Name of the parsoid parser cache instance
	 */
	public const PARSOID_PARSER_CACHE_NAME = 'parsoid';

	/**
	 * @deprecated since 1.42 use ParserOutputAccess::OPT_NO_UPDATE_CACHE instead
	 * Temporarily needed while we migrate extensions and other codebases
	 * to use the ParserOutputAccess constant directly
	 */
	public const OPT_NO_UPDATE_CACHE = ParserOutputAccess::OPT_NO_UPDATE_CACHE;

	/**
	 * @deprecated Parsoid will always lint at this point. This option
	 * has no effect and will be removed once all callers are fixed.
	 *
	 * @var int Collect linter data for the ParserLogLinterData hook.
	 */
	public const OPT_LOG_LINT_DATA = 64;

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig,
		'ParsoidWikiID'
	];

	/** @var ParsoidParserFactory */
	private $parsoidParserFactory;

	/** @var PageLookup */
	private $pageLookup;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var ParserOutputAccess */
	private $parserOutputAccess;

	/** @var SiteConfig */
	private $siteConfig;

	/** @var ServiceOptions */
	private $options;

	/** @var string */
	private $parsoidWikiId;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/**
	 * @param ServiceOptions $options
	 * @param ParsoidParserFactory $parsoidParserFactory
	 * @param ParserOutputAccess $parserOutputAccess
	 * @param PageLookup $pageLookup
	 * @param RevisionLookup $revisionLookup
	 * @param SiteConfig $siteConfig
	 * @param IContentHandlerFactory $contentHandlerFactory
	 */
	public function __construct(
		ServiceOptions $options,
		ParsoidParserFactory $parsoidParserFactory,
		ParserOutputAccess $parserOutputAccess,
		PageLookup $pageLookup,
		RevisionLookup $revisionLookup,
		SiteConfig $siteConfig,
		IContentHandlerFactory $contentHandlerFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->parsoidParserFactory = $parsoidParserFactory;
		$this->parserOutputAccess = $parserOutputAccess;
		$this->pageLookup = $pageLookup;
		$this->revisionLookup = $revisionLookup;
		$this->siteConfig = $siteConfig;
		$this->contentHandlerFactory = $contentHandlerFactory;

		// NOTE: This is passed as the "prefix" option to parsoid, which it uses
		//       to locate wiki specific configuration in the baseconfig directory.
		//       This should probably be managed by SiteConfig instead, so
		//       we hopefully will not need it here in the future.
		$this->parsoidWikiId = $options->get( 'ParsoidWikiID' );
	}

	/**
	 * @param string $model
	 *
	 * @return bool
	 */
	public function supportsContentModel( string $model ): bool {
		if ( $model === CONTENT_MODEL_WIKITEXT ) {
			return true;
		}

		// Check if the content model serializes to wikitext.
		// NOTE: We could use isSupportedFormat( CONTENT_FORMAT_WIKITEXT ) if PageContent::getContent()
		//       would specify the format when calling serialize().
		try {
			$handler = $this->contentHandlerFactory->getContentHandler( $model );
			if ( $handler->getDefaultFormat() === CONTENT_FORMAT_WIKITEXT ) {
				return true;
			}
		} catch ( MWUnknownContentModelException $ex ) {
			// If the content model is not known, it can't be supported.
			return false;
		}

		return $this->siteConfig->getContentModelHandler( $model ) !== null;
	}

	/**
	 * @internal
	 *
	 * @param ParserOutput $parserOutput
	 *
	 * @return ParsoidRenderID
	 */
	public function getParsoidRenderID( ParserOutput $parserOutput ): ParsoidRenderID {
		// XXX: ParserOutput may be coming from the parser cache, so we need to be careful
		// when we change how we store the render key in the ParserOutput object.
		$renderId = $parserOutput->getParsoidRenderId();
		if ( !$renderId ) {
			throw new InvalidArgumentException( 'ParserOutput does not have a render ID' );
		}

		return ParsoidRenderID::newFromKey( $renderId );
	}

	private function handleUnsupportedContentModel( RevisionRecord $revision ): ?Status {
		$mainSlot = $revision->getSlot( SlotRecord::MAIN );
		$contentModel = $mainSlot->getModel();
		if ( $this->supportsContentModel( $contentModel ) ) {
			return null;
		} else {
			// This is a messy fix for T324711. The real solution is T311648.
			// For now, just return dummy parser output.
			return $this->makeDummyParserOutput( $contentModel );
			// TODO: go back to throwing, once RESTbase no longer expects to get a parsoid rendering for
			//any kind of content (T324711).
			/*
				// TODO: throw an internal exception here, convert to HttpError in HtmlOutputRendererHelper.
				throw new HttpException( 'Parsoid does not support content model ' . $mainSlot->getModel(), 400 );
			}
			*/
		}
	}

	/**
	 * @param PageIdentity $page
	 * @param ParserOptions $parserOpts
	 * @param RevisionRecord|int|null $revision
	 * @param int $options See the OPT_XXX constants
	 * @param bool $lenientRevHandling
	 *
	 * @return Status<ParserOutput>
	 */
	public function getParserOutput(
		PageIdentity $page,
		ParserOptions $parserOpts,
		$revision = null,
		int $options = 0,
		bool $lenientRevHandling = false
	): Status {
		[ $page, $revision, $uncacheable ] = $this->resolveRevision( $page, $revision, $lenientRevHandling );
		$status = $this->handleUnsupportedContentModel( $revision );
		if ( $status ) {
			return $status;
		}

		try {
			if ( $uncacheable ) {
				$options = $options | ParserOutputAccess::OPT_NO_UPDATE_CACHE;
			}
			// Since we know we are generating Parsoid output, explicitly
			// call ParserOptions::setUseParsoid. This ensures that when
			// we query the parser-cache, the right cache key is computed.
			// This is an optional transition step to using ParserOutputAccess.
			$parserOpts->setUseParsoid();
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
	 */
	public function getCachedParserOutput(
		PageIdentity $page,
		ParserOptions $parserOpts,
		$revision = null,
		bool $lenientRevHandling = false
	): ?ParserOutput {
		[ $page, $revision, $ignored ] = $this->resolveRevision( $page, $revision, $lenientRevHandling );
		$mainSlot = $revision->getSlot( SlotRecord::MAIN );
		$contentModel = $mainSlot->getModel();
		if ( $this->supportsContentModel( $contentModel ) ) {
			// Since we know Parsoid supports this content model, explicitly
			// call ParserOptions::setUseParsoid. This ensures that when
			// we query the parser-cache, the right cache key is called.
			// This is an optional transition step to using ParserOutputAccess.
			$parserOpts->setUseParsoid();
		}
		return $this->parserOutputAccess->getCachedParserOutput( $page, $parserOpts, $revision );
	}

	private function makeDummyParserOutput( string $contentModel ): Status {
		$msg = "Dummy output. Parsoid does not support content model $contentModel. See T324711.";
		$output = new ParserOutput( $msg );

		// This is fast to generate so it's fine not to write this to parser cache.
		$output->updateCacheExpiry( 0 );
		// The render ID is required for rendering of dummy output: T311728.
		$output->setExtensionData( ParserOutput::PARSOID_RENDER_ID_KEY, '0/dummy-output' );
		// Required in HtmlOutputRendererHelper::putHeaders when $forHtml
		$output->setExtensionData(
			PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY,
			[
				'headers' => [ 'content-language' => 'en' ],
			]
		);

		return Status::newGood( $output );
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
	 */
	public function parseUncacheable(
		PageIdentity $page,
		ParserOptions $parserOpts,
		$revision,
		bool $lenientRevHandling = false
	): Status {
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

		$status = $this->handleUnsupportedContentModel( $revision );
		if ( $status ) {
			return $status;
		}

		try {
			// Since we aren't caching this output, there is no need to
			// call setUseParsoid() here.
			$parser = $this->parsoidParserFactory->create();
			$parserOutput = $this->parsoidParserFactory->create()->parseFakeRevision(
				$revision, $page, $parserOpts );
			$parserOutput->updateCacheExpiry( 0 ); // Ensure this isn't accidentally cached
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

		if ( $revision === null ) {
			$revision = $page->getLatest();
		}

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
}
