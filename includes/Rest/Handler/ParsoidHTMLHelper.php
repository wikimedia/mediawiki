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
 * @ingroup Page
 */
namespace MediaWiki\Rest\Handler;

use IBufferingStatsdDataFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Revision\RevisionRecord;
use ParserCache;
use ParserOptions;
use ParserOutput;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Helper for getting output of a given wikitext page rendered by parsoid.
 *
 * @since 1.36
 *
 * @unstable Pending consolidation of the Parsoid extension with core code.
 *           Part of this class should probably become a service.
 */
class ParsoidHTMLHelper {

	private const RENDER_ID_KEY = 'parsoid-render-id';

	/**
	 * @internal needed in test case
	 */
	public const PARSOID_PAGE_BUNDLE_KEY = 'parsoid-page-bundle';

	/**
	 * @internal
	 * @var string[]
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig
	];

	/** @var ParserCache */
	private $parserCache;

	/** @var RevisionOutputCache */
	private $revisionOutputCache;

	/** @var GlobalIdGenerator */
	private $globalIdGenerator;

	/** @var ParsoidOutputStash */
	private $parsoidOutputStash;

	/** @var PageRecord|null */
	private $page = null;

	/** @var Parsoid|null */
	private $parsoid = null;

	/** @var RevisionRecord|null */
	private $revision = null;

	/** @var ?string [ 'view', 'stash' ] are the supported flavors for now */
	private $flavor = null;

	/** @var bool */
	private $stash = false;

	/** @var ParserOutput|null */
	private $parserOutput = null;

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/** @var User */
	private $user;

	/** @var mixed */
	private $parsoidCacheConfig;

	/**
	 * @param ParserCache $parserCache
	 * @param RevisionOutputCache $revisionOutputCache
	 * @param GlobalIdGenerator $globalIdGenerator
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param IBufferingStatsdDataFactory $statsDataFactory
	 * @param ServiceOptions $options
	 */
	public function __construct(
		ParserCache $parserCache,
		RevisionOutputCache $revisionOutputCache,
		GlobalIdGenerator $globalIdGenerator,
		ParsoidOutputStash $parsoidOutputStash,
		IBufferingStatsdDataFactory $statsDataFactory,
		ServiceOptions $options
	) {
		$this->parserCache = $parserCache;
		$this->globalIdGenerator = $globalIdGenerator;
		$this->revisionOutputCache = $revisionOutputCache;
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->stats = $statsDataFactory;

		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->parsoidCacheConfig = $options->get( MainConfigNames::ParsoidCacheConfig );
	}

	/**
	 * @param PageRecord $page
	 * @param array $parameters
	 * @param User $user
	 * @param RevisionRecord|null $revision
	 */
	public function init(
		PageRecord $page,
		array $parameters,
		User $user,
		?RevisionRecord $revision = null
	) {
		$this->page = $page;
		$this->user = $user;
		$this->revision = $revision;
		$this->stash = $parameters['stash'];
		$this->flavor = $parameters['stash'] ? 'stash' : 'view'; // more to come, T308743
	}

	/**
	 * @return ParserOutput
	 * @throws LocalizedHttpException
	 */
	private function parse(): ParserOutput {
		$parsoid = $this->createParsoid();
		$pageConfig = $this->createPageConfig();
		try {
			$startTime = microtime( true );
			$pageBundle = $parsoid->wikitext2html( $pageConfig, [
				'pageBundle' => true,
			] );
			$parserOutput = $this->createParserOutputFromPageBundle( $pageBundle );
			$time = microtime( true ) - $startTime;
			if ( $time > 3 ) {
				LoggerFactory::getInstance( 'slow-parsoid' )
					->info( 'Parsing {title} was slow, took {time} seconds', [
						'time' => number_format( $time, 2 ),
						'title' => (string)$this->page,
					] );
			}
			return $parserOutput;
		} catch ( ClientError $e ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-html-backend-error' ),
				400,
				[ 'reason' => $e->getMessage() ]
			);
		} catch ( ResourceLimitExceededException $e ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-resource-limit-exceeded' ),
				413,
				[ 'reason' => $e->getMessage() ]
			);
		}
	}

	/**
	 * Assert that Parsoid services are available.
	 * TODO: once parsoid glue services are in core,
	 * this will become a no-op and will be removed.
	 * See T265518
	 * @throws LocalizedHttpException
	 */
	private function assertParsoidInstalled() {
		$services = MediaWikiServices::getInstance();
		if ( $services->has( 'ParsoidSiteConfig' ) &&
			$services->has( 'ParsoidPageConfigFactory' ) &&
			$services->has( 'ParsoidDataAccess' )
		) {
			return;
		}
		throw new LocalizedHttpException(
			MessageValue::new( 'rest-html-backend-error' ),
			501
		);
	}

	/**
	 * @return Parsoid
	 * @throws LocalizedHttpException
	 */
	private function createParsoid(): Parsoid {
		$this->assertParsoidInstalled();
		if ( $this->parsoid === null ) {
			// TODO: Once parsoid glue services are in core,
			// this will need to use normal DI.
			// At that point, we may want to extract a more high level
			// service for rendering a revision, and inject that into this class.
			// See T265518
			$services = MediaWikiServices::getInstance();
			$this->parsoid = new Parsoid(
				$services->get( 'ParsoidSiteConfig' ),
				$services->get( 'ParsoidDataAccess' )
			);
		}
		return $this->parsoid;
	}

	/**
	 * @return PageConfig
	 * @throws LocalizedHttpException
	 */
	private function createPageConfig(): PageConfig {
		$this->assertParsoidInstalled();
		// Currently everything is parsed as anon since Parsoid
		// can't report the used options.
		// Already checked that title/revision exist and accessible.
		// TODO: make ParsoidPageConfigFactory take a RevisionRecord
		// TODO: make ParsoidPageConfigFactory take PageReference as well
		return MediaWikiServices::getInstance()
			->get( 'ParsoidPageConfigFactory' )
			->create( $this->page, null, $this->revision );
	}

	/**
	 * @param ParserOutput $parserOutput
	 *
	 * @return PageBundle
	 */
	private function makePageBundle( ParserOutput $parserOutput ): PageBundle {
		$pbData = $parserOutput->getExtensionData( self::PARSOID_PAGE_BUNDLE_KEY );
		return new PageBundle(
			$parserOutput->getRawText(),
			$pbData['parsoid'] ?? [],
			$pbData['mw'] ?? []
		);
	}

	/**
	 * @return ParserOutput a tuple with html and content-type
	 * @throws LocalizedHttpException
	 */
	public function getHtml(): ParserOutput {
		$parserOutput = $this->getParserOutput();

		if ( $this->stash ) {
			if ( $this->user->pingLimiter( 'stashbasehtml' ) ) {
				throw new LocalizedHttpException(
					MessageValue::new( 'parsoid-stash-rate-limit-error' ),
					// See https://www.rfc-editor.org/rfc/rfc6585#section-4
					429,
					[ 'reason' => 'Rate limiter tripped, wait for a few minutes and try again' ]
				);
			}

			$parsoidStashKey = ParsoidRenderID::newFromKey(
				$parserOutput->getExtensionData( self::RENDER_ID_KEY )
			);
			$stashSuccess = $this->parsoidOutputStash->set(
				$parsoidStashKey,
				$this->makePageBundle( $parserOutput )
			);
			if ( !$stashSuccess ) {
				$this->stats->increment( 'parsoidhtmlhelper.stash.fail' );
				throw new LocalizedHttpException(
					MessageValue::new( 'rest-html-backend-error' ),
					500,
					[ 'reason' => 'Failed to stash parser output' ]
				);
			}
			$this->stats->increment( 'parsoidhtmlhelper.stash.save' );
		}

		return $parserOutput;
	}

	/**
	 * Returns an ETag uniquely identifying the HTML output.
	 *
	 * @param string $suffix A suffix to attach to the etag.
	 *
	 * @return string|null
	 */
	public function getETag( string $suffix = '' ): ?string {
		$parserOutput = $this->getParserOutput();
		$eTag = $parserOutput->getExtensionData( self::RENDER_ID_KEY );

		if ( $suffix !== '' ) {
			$eTag = "$eTag/{$this->flavor}/$suffix";
		} else {
			$eTag = "$eTag/{$this->flavor}";
		}

		return "\"{$eTag}\"";
	}

	/**
	 * Returns the time at which the HTML was rendered.
	 *
	 * @return string|null
	 */
	public function getLastModified(): ?string {
		return $this->getParserOutput()->getCacheTime();
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
	 * @return array
	 */
	public function getParamSettings(): array {
		return [
			'stash' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_REQUIRED => false,
			]
		];
	}

	/**
	 * @return ParserOutput
	 */
	private function getParserOutput(): ParserOutput {
		if ( $this->parserOutput ) {
			return $this->parserOutput;
		}

		$revId = $this->revision ? $this->revision->getId() : $this->page->getLatest();
		$parserOptions = ParserOptions::newFromAnon();

		$isOld = $revId !== $this->page->getLatest();

		if ( $isOld ) {
			$this->parserOutput = $this->revisionOutputCache->get( $this->revision,
				$parserOptions );
			$statsKey = 'parsoidhtmlhelper.revision.cache';
		} else {
			$this->parserOutput = $this->parserCache->get( $this->page,
				$parserOptions );
			$statsKey = 'parsoidhtmlhelper.parser.cache';

			if ( $this->parserOutput ) {
				// Ignore cached ParserOutput if it is incomplete,
				// because it was stored by an old version of the code.
				if ( !$this->parserOutput->getExtensionData( self::PARSOID_PAGE_BUNDLE_KEY )
					|| !$this->parserOutput->getExtensionData( self::RENDER_ID_KEY )
				) {
					$this->parserOutput = null;
				}
			}
		}
		if ( $this->parserOutput ) {
			$this->stats->increment( $statsKey . '.get.hit' );
			return $this->parserOutput;
		}

		$this->stats->increment( $statsKey . '.get.miss' );

		$startTime = microtime( true );
		$this->parserOutput = $this->parse();
		$time = microtime( true ) - $startTime;

		// TODO: when we make tighter integration with Parsoid, render ID should become
		// a standard ParserOutput property. Nothing else needs it now, so don't generate
		// it in ParserCache just yet.
		$parsoidRenderId = new ParsoidRenderID( $revId, $this->globalIdGenerator->newUUIDv1() );
		$this->parserOutput->setExtensionData( self::RENDER_ID_KEY, $parsoidRenderId->getKey() );

		// XXX: ParserOutput should just always record the revision ID and timestamp
		$now = wfTimestampNow();
		$this->parserOutput->setCacheRevisionId( $revId );
		$this->parserOutput->setCacheTime( $now );

		if ( $time > $this->parsoidCacheConfig['CacheThresholdTime'] ) {
			if ( $isOld ) {
				$this->revisionOutputCache->save( $this->parserOutput, $this->revision, $parserOptions, $now );
			} else {
				$this->parserCache->save( $this->parserOutput, $this->page, $parserOptions, $now );
			}
			$this->stats->increment( $statsKey . '.save.ok' );
		} else {
			$this->stats->increment( $statsKey . '.save.skipfast' );
		}

		return $this->parserOutput;
	}

}
