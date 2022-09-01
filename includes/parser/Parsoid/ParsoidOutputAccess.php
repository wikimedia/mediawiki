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

use Config;
use HashConfig;
use IBufferingStatsdDataFactory;
use InvalidArgumentException;
use Language;
use Liuggio\StatsdClient\Factory\StatsdDataFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use ParserCache;
use ParserOptions;
use ParserOutput;
use Status;
use UnexpectedValueException;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\UUID\GlobalIdGenerator;

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
	 * @var string Key used to store the parsoid render ID in ParserOutput
	 */
	private const RENDER_ID_KEY = 'parsoid-render-id';

	/**
	 * @var string Key used to store parsoid page bundle data in ParserOutput
	 */
	private const PARSOID_PAGE_BUNDLE_KEY = 'parsoid-page-bundle';

	/** @var int Do not check the cache before parsing (force parse) */
	public const OPT_FORCE_PARSE = 1;

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig
	];

	/** @var RevisionOutputCache */
	private $revisionOutputCache;

	/** @var ParserCache */
	private $parserCache;

	/** @var GlobalIdGenerator */
	private $globalIdGenerator;

	/** @var StatsdDataFactory */
	private $stats;

	/** @var Config */
	private $parsoidCacheConfig;

	/** @var Parsoid */
	private $parsoid;

	/** @var PageConfigFactory */
	private $parsoidPageConfigFactory;

	/** @var RevisionLookup */
	private $revisionLookup;
	/**
	 * @var SiteConfig
	 */
	private $siteConfig;

	/**
	 * @param ServiceOptions $options
	 * @param ParserCacheFactory $parserCacheFactory
	 * @param RevisionLookup $revisionLookup
	 * @param GlobalIdGenerator $globalIdGenerator
	 * @param IBufferingStatsdDataFactory $stats
	 * @param Parsoid $parsoid
	 * @param SiteConfig $siteConfig
	 * @param PageConfigFactory $parsoidPageConfigFactory
	 */
	public function __construct(
		ServiceOptions $options,
		ParserCacheFactory $parserCacheFactory,
		RevisionLookup $revisionLookup,
		GlobalIdGenerator $globalIdGenerator,
		IBufferingStatsdDataFactory $stats,
		Parsoid $parsoid,
		SiteConfig $siteConfig,
		PageConfigFactory $parsoidPageConfigFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->parsoidCacheConfig = new HashConfig( $options->get( MainConfigNames::ParsoidCacheConfig ) );
		$this->revisionOutputCache = $parserCacheFactory
			->getRevisionOutputCache( self::PARSOID_PARSER_CACHE_NAME );
		$this->parserCache = $parserCacheFactory->getParserCache( self::PARSOID_PARSER_CACHE_NAME );
		$this->revisionLookup = $revisionLookup;
		$this->globalIdGenerator = $globalIdGenerator;
		$this->stats = $stats;
		$this->parsoid = $parsoid;
		$this->siteConfig = $siteConfig;
		$this->parsoidPageConfigFactory = $parsoidPageConfigFactory;
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

		return $this->siteConfig->getContentModelHandler( $model ) !== null;
	}

	/**
	 * @param PageRecord $page
	 * @param ParserOptions $parserOpts
	 * @param ?RevisionRecord $revision
	 * @param int $options See the OPT_XXX constants
	 *
	 * @return Status<ParserOutput>
	 */
	public function getParserOutput(
		PageRecord $page,
		ParserOptions $parserOpts,
		?RevisionRecord $revision = null,
		int $options = 0
	): Status {
		$revId = $revision ? $revision->getId() : $page->getLatest();
		if ( !$revision ) {
			$revision = $this->revisionLookup->getRevisionById( $revId );

			if ( !$revision ) {
				throw new RevisionAccessException(
					'Revision {revId} not found',
					[ 'revId' => $revId ]
				);
			}
		}

		$isOld = $revId !== $page->getLatest();
		$statsKey = $isOld ? 'ParsoidOutputAccess.Cache.revision' : 'ParsoidOutputAccess.Cache.parser';

		$mainSlot = $revision->getSlot( SlotRecord::MAIN );
		if ( !$this->supportsContentModel( $mainSlot->getModel() ) ) {
			throw new UnexpectedValueException( 'Parsoid does not support content model ' . $mainSlot->getModel() );
		}

		if ( !( $options & self::OPT_FORCE_PARSE ) ) {
			$parserOutput = $this->getCachedParserOutput(
				$page,
				$parserOpts,
				$revision,
				$isOld,
				$statsKey
			);

			if ( $parserOutput ) {
				return Status::newGood( $parserOutput );
			}
		}

		$startTime = microtime( true );
		$status = $this->parse( $page, $parserOpts, $revision );
		$time = microtime( true ) - $startTime;

		if ( $status->isOK() ) {
			if ( $time > $this->parsoidCacheConfig->get( 'CacheThresholdTime' ) ) {
				$parserOutput = $status->getValue();
				$now = $parserOutput->getCacheTime();

				if ( $isOld ) {
					$this->revisionOutputCache->save( $parserOutput, $revision, $parserOpts, $now );
				} else {
					$this->parserCache->save( $parserOutput, $page, $parserOpts, $now );
				}
				$this->stats->increment( $statsKey . '.save.ok' );
			} else {
				$this->stats->increment( $statsKey . '.save.skipfast' );
			}
		} else {
			$this->stats->increment( $statsKey . '.save.notok' );
		}

		return $status;
	}

	/**
	 * @param PageIdentity $page
	 * @param ?RevisionRecord $revision
	 * @param Language|null $languageOverride
	 *
	 * @return Status<ParserOutput>
	 */
	private function parseInternal(
		PageIdentity $page,
		?RevisionRecord $revision = null,
		Language $languageOverride = null
	): Status {
		try {
			$langCode = $languageOverride ? $languageOverride->getCode() : null;
			$pageConfig = $this->parsoidPageConfigFactory->create(
				$page,
				null,
				$revision,
				null,
				$langCode
			);
			$startTime = microtime( true );
			$pageBundle = $this->parsoid->wikitext2html(
				$pageConfig,
				[ 'pageBundle' => true ]
			);
			$parserOutput = $this->createParserOutputFromPageBundle( $pageBundle );
			$time = microtime( true ) - $startTime;
			if ( $time > 3 ) {
				LoggerFactory::getInstance( 'slow-parsoid' )
					->info( 'Parsing {title} was slow, took {time} seconds', [
						'time' => number_format( $time, 2 ),
						'title' => (string)$page,
					] );
			}
			return Status::newGood( $parserOutput );
		} catch ( ClientError $e ) {
			return Status::newFatal( 'parsoid-client-error', $e->getMessage() );
		} catch ( ResourceLimitExceededException $e ) {
			return Status::newFatal( 'parsoid-resource-limit-exceeded', $e->getMessage() );
		}
	}

	/**
	 * Creates a ParserOutput object containing the relevant data from
	 * the given PageBundle object.
	 *
	 * We need to inject data-parsoid and other properties into the
	 * parser output object for caching, so we can use it for VE edits
	 * and transformations.
	 *
	 * @param PageBundle $pageBundle
	 *
	 * @return ParserOutput
	 */
	private function createParserOutputFromPageBundle( PageBundle $pageBundle ): ParserOutput {
		$parserOutput = new ParserOutput( $pageBundle->html );
		$parserOutput->setExtensionData(
			self::PARSOID_PAGE_BUNDLE_KEY,
			[
				'parsoid' => $pageBundle->parsoid,
				'mw' => $pageBundle->mw
			]
		);

		return $parserOutput;
	}

	/**
	 * NOTE: This needs to be ParserOutput returned by ->getParserOutput()
	 *   in this class.
	 *
	 * @param ParserOutput $parserOutput
	 *
	 * @return ParsoidRenderID
	 */
	public function getParsoidRenderID( ParserOutput $parserOutput ): ParsoidRenderID {
		// XXX: ParserOutput may be coming from the parser cache, so we need to be careful
		// when we change how we store the render key in the ParserOutput object.
		$renderId = $parserOutput->getExtensionData( self::RENDER_ID_KEY );
		if ( !$renderId ) {
			throw new InvalidArgumentException( 'ParserOutput does not have a render ID' );
		}

		return ParsoidRenderID::newFromKey( $renderId );
	}

	/**
	 * Returns a Parsoid PageBundle equivalent to the given ParserOutput.
	 *
	 * @param ParserOutput $parserOutput
	 *
	 * @return PageBundle
	 */
	public function getParsoidPageBundle( ParserOutput $parserOutput ): PageBundle {
		$pbData = $parserOutput->getExtensionData( self::PARSOID_PAGE_BUNDLE_KEY );
		return new PageBundle(
			$parserOutput->getRawText(),
			$pbData['parsoid'] ?? [],
			$pbData['mw'] ?? []
		);
	}

	/**
	 * @param PageRecord $page
	 * @param ParserOptions $parserOpts
	 * @param RevisionRecord|null $revision
	 * @param bool $isOld
	 * @param string $statsKey
	 *
	 * @return ?ParserOutput
	 */
	protected function getCachedParserOutput(
		PageRecord $page,
		ParserOptions $parserOpts,
		?RevisionRecord $revision,
		bool $isOld,
		string $statsKey
	): ?ParserOutput {
		if ( $isOld ) {
			$parserOutput = $this->revisionOutputCache->get( $revision, $parserOpts );
		} else {
			$parserOutput = $this->parserCache->get( $page, $parserOpts );
		}

		if ( $parserOutput ) {
			// Ignore cached ParserOutput if it is incomplete,
			// because it was stored by an old version of the code.
			if ( !$parserOutput->getExtensionData( self::PARSOID_PAGE_BUNDLE_KEY )
				|| !$parserOutput->getExtensionData( self::RENDER_ID_KEY )
			) {
				$parserOutput = null;
			}
		}

		if ( $parserOutput ) {
			$this->stats->increment( $statsKey . '.get.hit' );
			return $parserOutput;
		} else {
			$this->stats->increment( $statsKey . '.get.miss' );
			return null;
		}
	}

	/**
	 * @param PageRecord $page
	 * @param ParserOptions $parserOpts
	 * @param RevisionRecord|null $revision
	 * @return Status
	 */
	public function parse( PageRecord $page, ParserOptions $parserOpts, ?RevisionRecord $revision ): Status {
		$revId = $revision ? $revision->getId() : $page->getLatest();

		$status = $this->parseInternal( $page, $revision, $parserOpts->getTargetLanguage() );

		if ( !$status->isOK() ) {
			return $status;
		}

		$parserOutput = $status->getValue();

		// TODO: when we make tighter integration with Parsoid, render ID should become
		// a standard ParserOutput property. Nothing else needs it now, so don't generate
		// it in ParserCache just yet.
		$parsoidRenderId = new ParsoidRenderID( $revId, $this->globalIdGenerator->newUUIDv1() );
		$parserOutput->setExtensionData( self::RENDER_ID_KEY, $parsoidRenderId->getKey() );

		// XXX: ParserOutput should just always record the revision ID and timestamp
		$now = wfTimestampNow();
		$parserOutput->setCacheRevisionId( $revId );
		$parserOutput->setCacheTime( $now );

		return $status;
	}
}
