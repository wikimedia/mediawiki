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
use MediaWiki\Page\PageLookup;
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

	/** @var int Do not check the cache before parsing (force parse) */
	public const OPT_FORCE_PARSE = 1;

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig,
		MainConfigNames::ParsoidSettings,
		'ParsoidWikiID'
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

	/** @var PageLookup */
	private $pageLookup;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var SiteConfig */
	private $siteConfig;

	/** @var ServiceOptions */
	private $options;

	/** @var string */
	private $parsoidWikiId;

	/**
	 * @param ServiceOptions $options
	 * @param ParserCacheFactory $parserCacheFactory
	 * @param PageLookup $pageLookup
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
		PageLookup $pageLookup,
		RevisionLookup $revisionLookup,
		GlobalIdGenerator $globalIdGenerator,
		IBufferingStatsdDataFactory $stats,
		Parsoid $parsoid,
		SiteConfig $siteConfig,
		PageConfigFactory $parsoidPageConfigFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->parsoidCacheConfig = new HashConfig( $options->get( MainConfigNames::ParsoidCacheConfig ) );
		$this->revisionOutputCache = $parserCacheFactory
			->getRevisionOutputCache( self::PARSOID_PARSER_CACHE_NAME );
		$this->parserCache = $parserCacheFactory->getParserCache( self::PARSOID_PARSER_CACHE_NAME );
		$this->pageLookup = $pageLookup;
		$this->revisionLookup = $revisionLookup;
		$this->globalIdGenerator = $globalIdGenerator;
		$this->stats = $stats;
		$this->parsoid = $parsoid;
		$this->siteConfig = $siteConfig;
		$this->parsoidPageConfigFactory = $parsoidPageConfigFactory;

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

		return $this->siteConfig->getContentModelHandler( $model ) !== null;
	}

	/**
	 * @param PageIdentity $page
	 * @param ParserOptions $parserOpts
	 * @param RevisionRecord|int|null $revision
	 * @param int $options See the OPT_XXX constants
	 *
	 * @return Status<ParserOutput>
	 */
	public function getParserOutput(
		PageIdentity $page,
		ParserOptions $parserOpts,
		$revision = null,
		int $options = 0
	): Status {
		[ $page, $revision ] = $this->resolveRevision( $page, $revision );
		$isOld = $revision->getId() !== $page->getLatest();

		$statsKey = $isOld ? 'ParsoidOutputAccess.Cache.revision' : 'ParsoidOutputAccess.Cache.parser';

		$mainSlot = $revision->getSlot( SlotRecord::MAIN );
		if ( !$this->supportsContentModel( $mainSlot->getModel() ) ) {
			throw new UnexpectedValueException( 'Parsoid does not support content model ' . $mainSlot->getModel() );
		}

		if ( !( $options & self::OPT_FORCE_PARSE ) ) {
			$parserOutput = $this->getCachedParserOutputInternal(
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
		$status = $this->parse( $page, $parserOpts, [ 'pageBundle' => true ], $revision );
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
	 * @param array $envOptions
	 * @param ?RevisionRecord $revision
	 * @param Language|null $languageOverride
	 *
	 * @return Status
	 */
	private function parseInternal(
		PageIdentity $page,
		array $envOptions,
		?RevisionRecord $revision = null,
		Language $languageOverride = null
	): Status {
		$defaultOptions = [
			'pageBundle' => true,
			'prefix' => $this->parsoidWikiId,
			'pageName' => $page,
			'htmlVariantLanguage' => $languageOverride ? $languageOverride->getCode() : null,
			'outputContentVersion' => Parsoid::defaultHTMLVersion(),
		];

		try {
			$langCode = $languageOverride ? $languageOverride->getCode() : null;
			$pageConfig = $this->parsoidPageConfigFactory->create(
				$page,
				null,
				$revision,
				null,
				$langCode,
				$this->options->get( MainConfigNames::ParsoidSettings )
			);
			$startTime = microtime( true );
			$pageBundle = $this->parsoid->wikitext2html(
				$pageConfig,
				$envOptions + $defaultOptions
			);

			$parserOutput = PageBundleParserOutputConverter::parserOutputFromPageBundle( $pageBundle );
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
	 * @param PageIdentity $page
	 * @param ParserOptions $parserOpts
	 * @param RevisionRecord|int|null $revision
	 *
	 * @return ?ParserOutput
	 */
	public function getCachedParserOutput(
		PageIdentity $page,
		ParserOptions $parserOpts,
		$revision = null
	): ?ParserOutput {
		[ $page, $revision ] = $this->resolveRevision( $page, $revision );
		$isOld = $revision->getId() !== $page->getLatest();

		$statsKey = $isOld ? 'ParsoidOutputAccess.Cache.revision' : 'ParsoidOutputAccess.Cache.parser';

		return $this->getCachedParserOutputInternal(
			$page,
			$parserOpts,
			$revision,
			$isOld,
			$statsKey
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
	protected function getCachedParserOutputInternal(
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
			if ( !$parserOutput->getExtensionData( PageBundleParserOutputConverter::PARSOID_PAGE_BUNDLE_KEY )
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
	 * @param PageIdentity $page
	 * @param ParserOptions $parserOpts
	 * @param array $envOptions
	 * @param RevisionRecord|int|null $revision
	 *
	 * @return Status
	 */
	public function parse(
		PageIdentity $page,
		ParserOptions $parserOpts,
		array $envOptions,
		$revision
	): Status {
		// NOTE: If we have a RevisionRecord already, just use it, there is no need to resolve $page to
		//       a PageRecord (and it may not be possible if the page doesn't exist).
		if ( !$revision instanceof RevisionRecord ) {
			[ $page, $revision ] = $this->resolveRevision( $page, $revision );
		}

		$revId = $revision ? $revision->getId() : $page->getId();

		$status = $this->parseInternal( $page, $envOptions, $revision, $parserOpts->getTargetLanguage() );

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

	/**
	 * @param PageIdentity $page
	 * @param RevisionRecord|int|null $revision
	 *
	 * @return array [ PageRecord $page, RevisionRecord $revision ]
	 */
	private function resolveRevision( PageIdentity $page, $revision ): array {
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

		return [ $page, $revision ];
	}
}
