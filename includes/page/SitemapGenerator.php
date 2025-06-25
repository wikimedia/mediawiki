<?php

namespace MediaWiki\Page;

use MediaWiki\Cache\GenderCache;
use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\Xml\Xml;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Utility for generating a sitemap
 *
 * @internal
 */
class SitemapGenerator {
	private ?array $namespaces = null;
	private ?array $excludedNamespaces = null;
	private ?int $startId = null;
	private ?int $endId = null;
	private array $variants = [];
	private bool $skipRedirects = false;
	private ?int $limit = null;
	private ?int $nextBatchStart = null;

	/**
	 * The gender cache, or null if the content language has no gendered namespaces
	 * @var GenderCache|null
	 */
	private ?GenderCache $genderCache;

	public static function getVariants(
		Language $contLang,
		LanguageConverterFactory $languageConverterFactory,
	) {
		$converter = $languageConverterFactory->getLanguageConverter( $contLang );
		$variants = [];
		foreach ( $converter->getVariants() as $vCode ) {
			// We don't want the default variant
			if ( $vCode !== $contLang->getCode() ) {
				$variants[] = $vCode;
			}
		}
		return $variants;
	}

	public function __construct(
		Language $contLang,
		LanguageConverterFactory $languageConverterFactory,
		GenderCache $genderCache,
	) {
		$this->variants = self::getVariants( $contLang, $languageConverterFactory );
		$this->genderCache = $contLang->needsGenderDistinction() ? $genderCache : null;
	}

	/**
	 * Optionally limit the namespace IDs
	 *
	 * @param int[]|null $namespaces
	 * @return $this
	 */
	public function namespaces( ?array $namespaces ) {
		$this->namespaces = $namespaces;
		return $this;
	}

	/**
	 * Set the included/excluded namespace list based on configuration
	 *
	 * @param Config $config
	 * @return $this
	 */
	public function namespacesFromConfig( Config $config ) {
		$this->namespaces = null;
		$this->excludedNamespaces = null;

		$namespaces = $config->get( MainConfigNames::SitemapNamespaces );
		if ( $namespaces ) {
			$this->namespaces = $namespaces;
			return $this;
		}
		$defaultPolicy = $config->get( MainConfigNames::DefaultRobotPolicy );
		$namespacePolicies = $config->get( MainConfigNames::NamespaceRobotPolicies );
		if ( self::isNoIndex( $defaultPolicy ) ) {
			$namespaces = [];
			foreach ( $namespacePolicies as $ns => $policy ) {
				if ( !self::isNoIndex( $policy ) ) {
					$namespaces[] = $ns;
				}
			}
			$this->namespaces = $namespaces;
		} else {
			$excluded = [];
			foreach ( $namespacePolicies as $ns => $policy ) {
				if ( self::isNoIndex( $policy ) ) {
					$excluded[] = $ns;
				}
			}
			if ( $excluded ) {
				$this->excludedNamespaces = $excluded;
			}
		}
		return $this;
	}

	/**
	 * Interpret a configured robots policy
	 *
	 * @param string|array $policy
	 * @return bool
	 */
	private static function isNoIndex( $policy ) {
		$policyArray = Article::formatRobotPolicy( $policy );
		return ( $policyArray['index'] ?? '' ) === 'noindex';
	}

	/**
	 * Limit the page_id range to the given half-open interval
	 *
	 * @param int|null $startId The start ID, or null for unlimited
	 * @param int|null $endId The end ID, or null for unlimited. Only pages
	 *   with a page_id less than this value will be returned.
	 * @return $this
	 */
	public function idRange( ?int $startId, ?int $endId ) {
		$this->startId = $startId;
		$this->endId = $endId;
		return $this;
	}

	/**
	 * Skip redirects
	 *
	 * @param bool $skip
	 * @return $this
	 */
	public function skipRedirects( bool $skip = true ) {
		$this->skipRedirects = $skip;
		return $this;
	}

	/**
	 * Limit the number of returned results.
	 *
	 * @param int $limit
	 * @return $this
	 */
	public function limit( int $limit ) {
		$this->limit = $limit;
		return $this;
	}

	/**
	 * If a previous call to getXml() reached the limit set by limit() and there
	 * were still more rows, calling this will advance an internal cursor to the
	 * start of the next batch, and return true.
	 *
	 * If there were no more rows, return false.
	 *
	 * @return bool
	 */
	public function nextBatch() {
		if ( $this->nextBatchStart !== null ) {
			$this->startId = $this->nextBatchStart;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Use the previously set options to generate an XML sitemap
	 *
	 * @param IReadableDatabase $dbr
	 * @return string
	 */
	public function getXml( IReadableDatabase $dbr ) {
		$empty = false;
		$sqb = $dbr->newSelectQueryBuilder()
			->select( [ 'page_id', 'page_namespace', 'page_title', 'page_touched' ] )
			->from( 'page' )
			->leftJoin( 'page_props', null, [ 'page_id = pp_page', 'pp_propname' => 'noindex' ] )
			->where( [ 'pp_propname' => null ] )
			->orderBy( 'page_id' )
			->caller( __METHOD__ );

		if ( $this->startId !== null ) {
			$sqb->where( $dbr->expr( 'page_id', '>=', $this->startId ) );
		}
		if ( $this->endId !== null ) {
			$sqb->where( $dbr->expr( 'page_id', '<', $this->endId ) );
		}
		if ( $this->namespaces !== null ) {
			if ( $this->namespaces === [] ) {
				$empty = true;
			} else {
				$sqb->where( [ 'page_namespace' => $this->namespaces ] );
			}
		}
		if ( $this->excludedNamespaces !== null ) {
			$sqb->where( [ $dbr->expr( 'page_namespace', '!=', $this->excludedNamespaces ) ] );
		}
		if ( $this->skipRedirects ) {
			$sqb->where( [ 'page_is_redirect' => 0 ] );
		}
		if ( $this->variants ) {
			$variants = array_merge( [ null ], $this->variants );
		} else {
			$variants = [ null ];
		}
		if ( $this->limit ) {
			$pageLimit = (int)( $this->limit / count( $variants ) );
			$sqb->limit( $pageLimit + 1 );
		} else {
			$pageLimit = null;
		}

		$res = $empty ? [] : $sqb->fetchResultSet();
		$this->genderCache?->doPageRows( $res );

		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
			"<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		$count = 0;
		$nextBatchStart = 0;
		foreach ( $res as $row ) {
			if ( $pageLimit !== null && ++$count > $pageLimit ) {
				$nextBatchStart = (int)$row->page_id;
				break;
			}
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			foreach ( $variants as $variant ) {
				$query = $variant === null ? '' : 'variant=' . urlencode( $variant );
				$xml .= '<url>' .
					Xml::element( 'loc', null, $title->getCanonicalURL( $query ) ) .
					Xml::element( 'lastmod', null, wfTimestamp( TS_ISO_8601, $row->page_touched ) ) .
					"</url>\n";
			}
		}
		$xml .= "</urlset>\n";

		if ( $nextBatchStart ) {
			$this->nextBatchStart = $nextBatchStart;
		} else {
			$this->nextBatchStart = null;
		}

		return $xml;
	}
}
