<?php
/**
 * Copyright © 2006, 2013 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

use MediaWiki\Api\Validator\SubmoduleDef;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * This class contains a list of pages that the client has requested.
 * Initially, when the client passes in titles=, pageids=, or revisions=
 * parameter, an instance of the ApiPageSet class will normalize titles,
 * determine if the pages/revisions exist, and prefetch any additional page
 * data requested.
 *
 * When a generator is used, the result of the generator will become the input
 * for the second instance of this class, and all subsequent actions will use
 * the second instance for all their work.
 *
 * @ingroup API
 * @since 1.21 derives from ApiBase instead of ApiQueryBase
 */
class ApiPageSet extends ApiBase {
	/**
	 * Constructor flag: The new instance of ApiPageSet will ignore the 'generator=' parameter
	 * @since 1.21
	 */
	private const DISABLE_GENERATORS = 1;

	/** @var ApiBase used for getDb() call */
	private $mDbSource;

	/** @var array */
	private $mParams;

	/** @var bool */
	private $mResolveRedirects;

	/** @var bool */
	private $mConvertTitles;

	/** @var bool */
	private $mAllowGenerator;

	/** @var int[][] [ns][dbkey] => page_id or negative when missing */
	private $mAllPages = [];

	/** @var Title[] */
	private $mTitles = [];

	/** @var int[][] [ns][dbkey] => page_id or negative when missing */
	private $mGoodAndMissingPages = [];

	/** @var int[][] [ns][dbkey] => page_id */
	private $mGoodPages = [];

	/** @var Title[] */
	private $mGoodTitles = [];

	/** @var int[][] [ns][dbkey] => fake page_id */
	private $mMissingPages = [];

	/** @var Title[] */
	private $mMissingTitles = [];

	/** @var array[] [fake_page_id] => [ 'title' => $title, 'invalidreason' => $reason ] */
	private $mInvalidTitles = [];

	/** @var int[] */
	private $mMissingPageIDs = [];

	/** @var Title[] */
	private $mRedirectTitles = [];

	/** @var Title[] */
	private $mSpecialTitles = [];

	/** @var int[][] separate from mAllPages to avoid breaking getAllTitlesByNamespace() */
	private $mAllSpecials = [];

	/** @var string[] */
	private $mNormalizedTitles = [];

	/** @var string[] */
	private $mInterwikiTitles = [];

	/** @var Title[] */
	private $mPendingRedirectIDs = [];

	/** @var Title[][] [dbkey] => [ Title $from, Title $to ] */
	private $mPendingRedirectSpecialPages = [];

	/** @var Title[] */
	private $mResolvedRedirectTitles = [];

	/** @var string[] */
	private $mConvertedTitles = [];

	/** @var int[] Array of revID (int) => pageID (int) */
	private $mGoodRevIDs = [];

	/** @var int[] Array of revID (int) => pageID (int) */
	private $mLiveRevIDs = [];

	/** @var int[] Array of revID (int) => pageID (int) */
	private $mDeletedRevIDs = [];

	/** @var int[] */
	private $mMissingRevIDs = [];

	/** @var array[][] [ns][dbkey] => data array */
	private $mGeneratorData = [];

	/** @var int */
	private $mFakePageId = -1;

	/** @var string */
	private $mCacheMode = 'public';

	/** @var array */
	private $mRequestedPageFields = [];

	/** @var int */
	private $mDefaultNamespace;

	/** @var callable|null */
	private $mRedirectMergePolicy;

	/** @var string[]|null see getGenerators() */
	private static $generators = null;

	/** @var Language */
	private $contentLanguage;

	/** @var LinkCache */
	private $linkCache;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var GenderCache */
	private $genderCache;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var ILanguageConverter */
	private $languageConverter;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/**
	 * Add all items from $values into the result
	 * @param array &$result Output
	 * @param array $values Values to add
	 * @param string[] $flags The names of boolean flags to mark this element
	 * @param string|null $name If given, name of the value
	 */
	private static function addValues( array &$result, $values, $flags = [], $name = null ) {
		foreach ( $values as $val ) {
			if ( $val instanceof Title ) {
				$v = [];
				ApiQueryBase::addTitleInfo( $v, $val );
			} elseif ( $name !== null ) {
				$v = [ $name => $val ];
			} else {
				$v = $val;
			}
			foreach ( $flags as $flag ) {
				$v[$flag] = true;
			}
			$result[] = $v;
		}
	}

	/**
	 * @param ApiBase $dbSource Module implementing getDB().
	 *        Allows PageSet to reuse existing db connection from the shared state like ApiQuery.
	 * @param int $flags Zero or more flags like DISABLE_GENERATORS
	 * @param int $defaultNamespace The namespace to use if none is specified by a prefix.
	 * @since 1.21 accepts $flags instead of two boolean values
	 */
	public function __construct( ApiBase $dbSource, $flags = 0, $defaultNamespace = NS_MAIN ) {
		parent::__construct( $dbSource->getMain(), $dbSource->getModuleName() );
		$this->mDbSource = $dbSource;
		$this->mAllowGenerator = ( $flags & self::DISABLE_GENERATORS ) == 0;
		$this->mDefaultNamespace = $defaultNamespace;

		$this->mParams = $this->extractRequestParams();
		$this->mResolveRedirects = $this->mParams['redirects'];
		$this->mConvertTitles = $this->mParams['converttitles'];

		// Needs service injection - T283314
		$services = MediaWikiServices::getInstance();
		$this->contentLanguage = $services->getContentLanguage();
		$this->linkCache = $services->getLinkCache();
		$this->namespaceInfo = $services->getNamespaceInfo();
		$this->genderCache = $services->getGenderCache();
		$this->linkBatchFactory = $services->getLinkBatchFactory();
		$this->titleFactory = $services->getTitleFactory();
		$this->languageConverter = $services->getLanguageConverterFactory()
			->getLanguageConverter( $this->contentLanguage );
		$this->specialPageFactory = $services->getSpecialPageFactory();
		$this->wikiPageFactory = $services->getWikiPageFactory();
	}

	/**
	 * In case execute() is not called, call this method to mark all relevant parameters as used
	 * This prevents unused parameters from being reported as warnings
	 */
	public function executeDryRun() {
		$this->executeInternal( true );
	}

	/**
	 * Populate the PageSet from the request parameters.
	 */
	public function execute() {
		$this->executeInternal( false );
	}

	/**
	 * Populate the PageSet from the request parameters.
	 * @param bool $isDryRun If true, instantiates generator, but only to mark
	 *    relevant parameters as used
	 */
	private function executeInternal( $isDryRun ) {
		$generatorName = $this->mAllowGenerator ? $this->mParams['generator'] : null;
		if ( isset( $generatorName ) ) {
			$dbSource = $this->mDbSource;
			if ( !$dbSource instanceof ApiQuery ) {
				// If the parent container of this pageset is not ApiQuery, we must create it to run generator
				$dbSource = $this->getMain()->getModuleManager()->getModule( 'query' );
			}
			$generator = $dbSource->getModuleManager()->getModule( $generatorName, null, true );
			if ( $generator === null ) {
				$this->dieWithError( [ 'apierror-badgenerator-unknown', $generatorName ], 'badgenerator' );
			}
			if ( !$generator instanceof ApiQueryGeneratorBase ) {
				$this->dieWithError( [ 'apierror-badgenerator-notgenerator', $generatorName ], 'badgenerator' );
			}
			// Create a temporary pageset to store generator's output,
			// add any additional fields generator may need, and execute pageset to populate titles/pageids
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable T240141
			$tmpPageSet = new ApiPageSet( $dbSource, self::DISABLE_GENERATORS );
			$generator->setGeneratorMode( $tmpPageSet );
			$this->mCacheMode = $generator->getCacheMode( $generator->extractRequestParams() );

			if ( !$isDryRun ) {
				$generator->requestExtraData( $tmpPageSet );
			}
			$tmpPageSet->executeInternal( $isDryRun );

			// populate this pageset with the generator output
			if ( !$isDryRun ) {
				$generator->executeGenerator( $this );

				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable T240141
				$this->getHookRunner()->onAPIQueryGeneratorAfterExecute( $generator, $this );
			} else {
				// Prevent warnings from being reported on these parameters
				$main = $this->getMain();
				foreach ( $generator->extractRequestParams() as $paramName => $param ) {
					$main->markParamsUsed( $generator->encodeParamName( $paramName ) );
				}
			}

			if ( !$isDryRun ) {
				$this->resolvePendingRedirects();
			}
		} else {
			// Only one of the titles/pageids/revids is allowed at the same time
			$dataSource = null;
			if ( isset( $this->mParams['titles'] ) ) {
				$dataSource = 'titles';
			}
			if ( isset( $this->mParams['pageids'] ) ) {
				if ( isset( $dataSource ) ) {
					$this->dieWithError(
						[
							'apierror-invalidparammix-cannotusewith',
							$this->encodeParamName( 'pageids' ),
							$this->encodeParamName( $dataSource )
						],
						'multisource'
					);
				}
				$dataSource = 'pageids';
			}
			if ( isset( $this->mParams['revids'] ) ) {
				if ( isset( $dataSource ) ) {
					$this->dieWithError(
						[
							'apierror-invalidparammix-cannotusewith',
							$this->encodeParamName( 'revids' ),
							$this->encodeParamName( $dataSource )
						],
						'multisource'
					);
				}
				$dataSource = 'revids';
			}

			if ( !$isDryRun ) {
				// Populate page information with the original user input
				switch ( $dataSource ) {
					case 'titles':
						$this->initFromTitles( $this->mParams['titles'] );
						break;
					case 'pageids':
						$this->initFromPageIds( $this->mParams['pageids'] );
						break;
					case 'revids':
						if ( $this->mResolveRedirects ) {
							$this->addWarning( 'apiwarn-redirectsandrevids' );
						}
						$this->mResolveRedirects = false;
						$this->initFromRevIDs( $this->mParams['revids'] );
						break;
					default:
						// Do nothing - some queries do not need any of the data sources.
						break;
				}
			}
		}
	}

	/**
	 * Check whether this PageSet is resolving redirects
	 * @return bool
	 */
	public function isResolvingRedirects() {
		return $this->mResolveRedirects;
	}

	/**
	 * Return the parameter name that is the source of data for this PageSet
	 *
	 * If multiple source parameters are specified (e.g. titles and pageids),
	 * one will be named arbitrarily.
	 *
	 * @return string|null
	 */
	public function getDataSource() {
		if ( $this->mAllowGenerator && isset( $this->mParams['generator'] ) ) {
			return 'generator';
		}
		if ( isset( $this->mParams['titles'] ) ) {
			return 'titles';
		}
		if ( isset( $this->mParams['pageids'] ) ) {
			return 'pageids';
		}
		if ( isset( $this->mParams['revids'] ) ) {
			return 'revids';
		}

		return null;
	}

	/**
	 * Request an additional field from the page table.
	 * Must be called before execute()
	 * @param string $fieldName
	 */
	public function requestField( $fieldName ) {
		$this->mRequestedPageFields[$fieldName] = null;
	}

	/**
	 * Get the value of a custom field previously requested through
	 * requestField()
	 * @param string $fieldName
	 * @return mixed Field value
	 */
	public function getCustomField( $fieldName ) {
		return $this->mRequestedPageFields[$fieldName];
	}

	/**
	 * Get the fields that have to be queried from the page table:
	 * the ones requested through requestField() and a few basic ones
	 * we always need
	 * @return string[] Array of field names
	 */
	public function getPageTableFields() {
		// Ensure we get minimum required fields
		// DON'T change this order
		$pageFlds = [
			'page_namespace' => null,
			'page_title' => null,
			'page_id' => null,
		];

		if ( $this->mResolveRedirects ) {
			$pageFlds['page_is_redirect'] = null;
		}

		$pageFlds['page_content_model'] = null;

		if ( $this->getConfig()->get( MainConfigNames::PageLanguageUseDB ) ) {
			$pageFlds['page_lang'] = null;
		}

		foreach ( LinkCache::getSelectFields() as $field ) {
			$pageFlds[$field] = null;
		}

		$pageFlds = array_merge( $pageFlds, $this->mRequestedPageFields );

		return array_keys( $pageFlds );
	}

	/**
	 * Returns an array [ns][dbkey] => page_id for all requested titles.
	 * page_id is a unique negative number in case title was not found.
	 * Invalid titles will also have negative page IDs and will be in namespace 0
	 * @return array
	 */
	public function getAllTitlesByNamespace() {
		return $this->mAllPages;
	}

	/**
	 * All existing and missing pages including redirects.
	 * Does not include special pages, interwiki links, and invalid titles.
	 * If redirects are resolved, both the redirect and the target will be included here.
	 *
	 * @deprecated since 1.37, use getPages() instead.
	 * @return Title[]
	 */
	public function getTitles() {
		return $this->mTitles;
	}

	/**
	 * All existing and missing pages including redirects.
	 * Does not include special pages, interwiki links, and invalid titles.
	 * If redirects are resolved, both the redirect and the target will be included here.
	 *
	 * @since 1.37
	 * @return PageIdentity[]
	 */
	public function getPages(): array {
		return $this->mTitles;
	}

	/**
	 * Returns the number of unique pages (not revisions) in the set.
	 * @return int
	 */
	public function getTitleCount() {
		return count( $this->mTitles );
	}

	/**
	 * Returns an array [ns][dbkey] => page_id for all good titles.
	 * @return array
	 */
	public function getGoodTitlesByNamespace() {
		return $this->mGoodPages;
	}

	/**
	 * Title objects that were found in the database, including redirects.
	 * If redirects are resolved, this will include existing redirect targets.
	 * @deprecated since 1.37, use getGoodPages() instead.
	 * @return Title[] Array page_id (int) => Title (obj)
	 */
	public function getGoodTitles() {
		return $this->mGoodTitles;
	}

	/**
	 * Pages that were found in the database, including redirects.
	 * If redirects are resolved, this will include existing redirect targets.
	 * @since 1.37
	 * @return PageIdentity[] Array page_id (int) => PageIdentity (obj)
	 */
	public function getGoodPages(): array {
		return $this->mGoodTitles;
	}

	/**
	 * Returns the number of found unique pages (not revisions) in the set.
	 * @return int
	 */
	public function getGoodTitleCount() {
		return count( $this->mGoodTitles );
	}

	/**
	 * Returns an array [ns][dbkey] => fake_page_id for all missing titles.
	 * fake_page_id is a unique negative number.
	 * @return array
	 */
	public function getMissingTitlesByNamespace() {
		return $this->mMissingPages;
	}

	/**
	 * Title objects that were NOT found in the database.
	 * The array's index will be negative for each item.
	 * If redirects are resolved, this will include missing redirect targets.
	 * @deprecated since 1.37, use getMissingPages instead.
	 * @return Title[]
	 */
	public function getMissingTitles() {
		return $this->mMissingTitles;
	}

	/**
	 * Pages that were NOT found in the database.
	 * The array's index will be negative for each item.
	 * If redirects are resolved, this will include missing redirect targets.
	 * @since 1.37
	 * @return PageIdentity[]
	 */
	public function getMissingPages(): array {
		return $this->mMissingTitles;
	}

	/**
	 * Returns an array [ns][dbkey] => page_id for all good and missing titles.
	 * @return array
	 */
	public function getGoodAndMissingTitlesByNamespace() {
		return $this->mGoodAndMissingPages;
	}

	/**
	 * Title objects for good and missing titles.
	 * @deprecated since 1.37, use getGoodAndMissingPages() instead.
	 * @return Title[]
	 */
	public function getGoodAndMissingTitles() {
		return $this->mGoodTitles + $this->mMissingTitles;
	}

	/**
	 * Pages for good and missing titles.
	 * @since 1.37
	 * @return PageIdentity[]
	 */
	public function getGoodAndMissingPages(): array {
		return $this->mGoodTitles + $this->mMissingTitles;
	}

	/**
	 * Titles that were deemed invalid by Title::newFromText()
	 * The array's index will be unique and negative for each item
	 * @return array[] Array of arrays with 'title' and 'invalidreason' properties
	 */
	public function getInvalidTitlesAndReasons() {
		return $this->mInvalidTitles;
	}

	/**
	 * Page IDs that were not found in the database
	 * @return int[] Array of page IDs
	 */
	public function getMissingPageIDs() {
		return $this->mMissingPageIDs;
	}

	/**
	 * Get a list of redirect resolutions - maps a title to its redirect
	 * target.
	 * @deprecated since 1.37, use getRedirectTargets instead.
	 * @return Title[]
	 */
	public function getRedirectTitles() {
		return $this->mRedirectTitles;
	}

	/**
	 * Get a list of redirect resolutions - maps a title to its redirect
	 * target.
	 * @since 1.37
	 * @return LinkTarget[]
	 */
	public function getRedirectTargets(): array {
		return $this->mRedirectTitles;
	}

	/**
	 * Get a list of redirect resolutions - maps a title to its redirect
	 * target. Includes generator data for redirect source when available.
	 * @param ApiResult|null $result
	 * @return string[][]
	 * @since 1.21
	 */
	public function getRedirectTitlesAsResult( $result = null ) {
		$values = [];
		foreach ( $this->getRedirectTitles() as $titleStrFrom => $titleTo ) {
			$r = [
				'from' => strval( $titleStrFrom ),
				'to' => $titleTo->getPrefixedText(),
			];
			if ( $titleTo->hasFragment() ) {
				$r['tofragment'] = $titleTo->getFragment();
			}
			if ( $titleTo->isExternal() ) {
				$r['tointerwiki'] = $titleTo->getInterwiki();
			}
			if ( isset( $this->mResolvedRedirectTitles[$titleStrFrom] ) ) {
				$titleFrom = $this->mResolvedRedirectTitles[$titleStrFrom];
				$ns = $titleFrom->getNamespace();
				$dbkey = $titleFrom->getDBkey();
				if ( isset( $this->mGeneratorData[$ns][$dbkey] ) ) {
					$r = array_merge( $this->mGeneratorData[$ns][$dbkey], $r );
				}
			}

			$values[] = $r;
		}
		if ( !empty( $values ) && $result ) {
			ApiResult::setIndexedTagName( $values, 'r' );
		}

		return $values;
	}

	/**
	 * Get a list of title normalizations - maps a title to its normalized
	 * version.
	 * @return string[] Array of raw_prefixed_title (string) => prefixed_title (string)
	 */
	public function getNormalizedTitles() {
		return $this->mNormalizedTitles;
	}

	/**
	 * Get a list of title normalizations - maps a title to its normalized
	 * version in the form of result array.
	 * @param ApiResult|null $result
	 * @return string[][]
	 * @since 1.21
	 */
	public function getNormalizedTitlesAsResult( $result = null ) {
		$values = [];
		foreach ( $this->getNormalizedTitles() as $rawTitleStr => $titleStr ) {
			$encode = $this->contentLanguage->normalize( $rawTitleStr ) !== $rawTitleStr;
			$values[] = [
				'fromencoded' => $encode,
				'from' => $encode ? rawurlencode( $rawTitleStr ) : $rawTitleStr,
				'to' => $titleStr
			];
		}
		if ( !empty( $values ) && $result ) {
			ApiResult::setIndexedTagName( $values, 'n' );
		}

		return $values;
	}

	/**
	 * Get a list of title conversions - maps a title to its converted
	 * version.
	 * @return string[] Array of raw_prefixed_title (string) => prefixed_title (string)
	 */
	public function getConvertedTitles() {
		return $this->mConvertedTitles;
	}

	/**
	 * Get a list of title conversions - maps a title to its converted
	 * version as a result array.
	 * @param ApiResult|null $result
	 * @return string[][] Array of (from, to) strings
	 * @since 1.21
	 */
	public function getConvertedTitlesAsResult( $result = null ) {
		$values = [];
		foreach ( $this->getConvertedTitles() as $rawTitleStr => $titleStr ) {
			$values[] = [
				'from' => $rawTitleStr,
				'to' => $titleStr
			];
		}
		if ( !empty( $values ) && $result ) {
			ApiResult::setIndexedTagName( $values, 'c' );
		}

		return $values;
	}

	/**
	 * Get a list of interwiki titles - maps a title to its interwiki
	 * prefix.
	 * @return string[] Array of raw_prefixed_title (string) => interwiki_prefix (string)
	 */
	public function getInterwikiTitles() {
		return $this->mInterwikiTitles;
	}

	/**
	 * Get a list of interwiki titles - maps a title to its interwiki
	 * prefix as result.
	 * @param ApiResult|null $result
	 * @param bool $iwUrl
	 * @return string[][]
	 * @since 1.21
	 */
	public function getInterwikiTitlesAsResult( $result = null, $iwUrl = false ) {
		$values = [];
		foreach ( $this->getInterwikiTitles() as $rawTitleStr => $interwikiStr ) {
			$item = [
				'title' => $rawTitleStr,
				'iw' => $interwikiStr,
			];
			if ( $iwUrl ) {
				$title = $this->titleFactory->newFromText( $rawTitleStr );
				$item['url'] = $title->getFullURL( '', false, PROTO_CURRENT );
			}
			$values[] = $item;
		}
		if ( !empty( $values ) && $result ) {
			ApiResult::setIndexedTagName( $values, 'i' );
		}

		return $values;
	}

	/**
	 * Get an array of invalid/special/missing titles.
	 *
	 * @param string[] $invalidChecks List of types of invalid titles to include.
	 *   Recognized values are:
	 *   - invalidTitles: Titles and reasons from $this->getInvalidTitlesAndReasons()
	 *   - special: Titles from $this->getSpecialTitles()
	 *   - missingIds: ids from $this->getMissingPageIDs()
	 *   - missingRevIds: ids from $this->getMissingRevisionIDs()
	 *   - missingTitles: Titles from $this->getMissingTitles()
	 *   - interwikiTitles: Titles from $this->getInterwikiTitlesAsResult()
	 * @return array Array suitable for inclusion in the response
	 * @since 1.23
	 */
	public function getInvalidTitlesAndRevisions( $invalidChecks = [ 'invalidTitles',
		'special', 'missingIds', 'missingRevIds', 'missingTitles', 'interwikiTitles' ]
	) {
		$result = [];
		if ( in_array( 'invalidTitles', $invalidChecks ) ) {
			self::addValues( $result, $this->getInvalidTitlesAndReasons(), [ 'invalid' ] );
		}
		if ( in_array( 'special', $invalidChecks ) ) {
			$known = [];
			$unknown = [];
			foreach ( $this->getSpecialTitles() as $title ) {
				if ( $title->isKnown() ) {
					$known[] = $title;
				} else {
					$unknown[] = $title;
				}
			}
			self::addValues( $result, $unknown, [ 'special', 'missing' ] );
			self::addValues( $result, $known, [ 'special' ] );
		}
		if ( in_array( 'missingIds', $invalidChecks ) ) {
			self::addValues( $result, $this->getMissingPageIDs(), [ 'missing' ], 'pageid' );
		}
		if ( in_array( 'missingRevIds', $invalidChecks ) ) {
			self::addValues( $result, $this->getMissingRevisionIDs(), [ 'missing' ], 'revid' );
		}
		if ( in_array( 'missingTitles', $invalidChecks ) ) {
			$known = [];
			$unknown = [];
			foreach ( $this->getMissingTitles() as $title ) {
				if ( $title->isKnown() ) {
					$known[] = $title;
				} else {
					$unknown[] = $title;
				}
			}
			self::addValues( $result, $unknown, [ 'missing' ] );
			self::addValues( $result, $known, [ 'missing', 'known' ] );
		}
		if ( in_array( 'interwikiTitles', $invalidChecks ) ) {
			self::addValues( $result, $this->getInterwikiTitlesAsResult() );
		}

		return $result;
	}

	/**
	 * Get the list of valid revision IDs (requested with the revids= parameter)
	 * @return int[] Array of revID (int) => pageID (int)
	 */
	public function getRevisionIDs() {
		return $this->mGoodRevIDs;
	}

	/**
	 * Get the list of non-deleted revision IDs (requested with the revids= parameter)
	 * @return int[] Array of revID (int) => pageID (int)
	 */
	public function getLiveRevisionIDs() {
		return $this->mLiveRevIDs;
	}

	/**
	 * Get the list of revision IDs that were associated with deleted titles.
	 * @return int[] Array of revID (int) => pageID (int)
	 */
	public function getDeletedRevisionIDs() {
		return $this->mDeletedRevIDs;
	}

	/**
	 * Revision IDs that were not found in the database
	 * @return int[] Array of revision IDs
	 */
	public function getMissingRevisionIDs() {
		return $this->mMissingRevIDs;
	}

	/**
	 * Revision IDs that were not found in the database as result array.
	 * @param ApiResult|null $result
	 * @return int[][]
	 * @since 1.21
	 */
	public function getMissingRevisionIDsAsResult( $result = null ) {
		$values = [];
		foreach ( $this->getMissingRevisionIDs() as $revid ) {
			$values[$revid] = [
				'revid' => $revid,
				'missing' => true,
			];
		}
		if ( !empty( $values ) && $result ) {
			ApiResult::setIndexedTagName( $values, 'rev' );
		}

		return $values;
	}

	/**
	 * Get the list of titles with negative namespace
	 * @deprecated since 1.37, use getSpecialPages() instead.
	 * @return Title[]
	 */
	public function getSpecialTitles() {
		return $this->mSpecialTitles;
	}

	/**
	 * Get the list of pages with negative namespace
	 * @since 1.37
	 * @return PageReference[]
	 */
	public function getSpecialPages(): array {
		return $this->mSpecialTitles;
	}

	/**
	 * Returns the number of revisions (requested with revids= parameter).
	 * @return int Number of revisions.
	 */
	public function getRevisionCount() {
		return count( $this->getRevisionIDs() );
	}

	/**
	 * Populate this PageSet
	 * @param string[]|LinkTarget[]|PageReference[] $titles
	 */
	public function populateFromTitles( $titles ) {
		$this->initFromTitles( $titles );
	}

	/**
	 * Populate this PageSet from a list of page IDs
	 * @param int[] $pageIDs
	 */
	public function populateFromPageIDs( $pageIDs ) {
		$this->initFromPageIds( $pageIDs );
	}

	/**
	 * Populate this PageSet from a rowset returned from the database
	 *
	 * Note that the query result must include the columns returned by
	 * $this->getPageTableFields().
	 *
	 * @param IDatabase $db
	 * @param IResultWrapper $queryResult
	 */
	public function populateFromQueryResult( $db, $queryResult ) {
		$this->initFromQueryResult( $queryResult );
	}

	/**
	 * Populate this PageSet from a list of revision IDs
	 * @param int[] $revIDs Array of revision IDs
	 */
	public function populateFromRevisionIDs( $revIDs ) {
		$this->initFromRevIDs( $revIDs );
	}

	/**
	 * Extract all requested fields from the row received from the database
	 * @param stdClass $row Result row
	 */
	public function processDbRow( $row ) {
		// Store Title object in various data structures
		$title = $this->titleFactory->newFromRow( $row );

		$this->linkCache->addGoodLinkObjFromRow( $title, $row );

		$pageId = (int)$row->page_id;
		$this->mAllPages[$row->page_namespace][$row->page_title] = $pageId;
		$this->mTitles[] = $title;

		if ( $this->mResolveRedirects && $row->page_is_redirect == '1' ) {
			$this->mPendingRedirectIDs[$pageId] = $title;
		} else {
			$this->mGoodPages[$row->page_namespace][$row->page_title] = $pageId;
			$this->mGoodAndMissingPages[$row->page_namespace][$row->page_title] = $pageId;
			$this->mGoodTitles[$pageId] = $title;
		}

		foreach ( $this->mRequestedPageFields as $fieldName => &$fieldValues ) {
			$fieldValues[$pageId] = $row->$fieldName;
		}
	}

	/**
	 * This method populates internal variables with page information
	 * based on the given array of title strings.
	 *
	 * Steps:
	 * #1 For each title, get data from `page` table
	 * #2 If page was not found in the DB, store it as missing
	 *
	 * Additionally, when resolving redirects:
	 * #3 If no more redirects left, stop.
	 * #4 For each redirect, get its target from the `redirect` table.
	 * #5 Substitute the original LinkBatch object with the new list
	 * #6 Repeat from step #1
	 *
	 * @param string[]|LinkTarget[]|PageReference[] $titles
	 */
	private function initFromTitles( $titles ) {
		// Get validated and normalized title objects
		$linkBatch = $this->processTitlesArray( $titles );
		if ( $linkBatch->isEmpty() ) {
			// There might be special-page redirects
			$this->resolvePendingRedirects();
			return;
		}

		$db = $this->getDB();

		// Get pageIDs data from the `page` table
		$res = $db->newSelectQueryBuilder()
			->select( $this->getPageTableFields() )
			->from( 'page' )
			->where( $linkBatch->constructSet( 'page', $db ) )
			->caller( __METHOD__ )
			->fetchResultSet();

		// Hack: get the ns:titles stored in [ ns => [ titles ] ] format
		$this->initFromQueryResult( $res, $linkBatch->data, true ); // process Titles

		// Resolve any found redirects
		$this->resolvePendingRedirects();
	}

	/**
	 * Does the same as initFromTitles(), but is based on page IDs instead
	 * @param int[] $pageids
	 * @param bool $filterIds Whether the IDs need filtering
	 */
	private function initFromPageIds( $pageids, $filterIds = true ) {
		if ( !$pageids ) {
			return;
		}

		$pageids = array_map( 'intval', $pageids ); // paranoia
		$remaining = array_fill_keys( $pageids, true );

		if ( $filterIds ) {
			$pageids = $this->filterIDs( [ [ 'page', 'page_id' ] ], $pageids );
		}

		$res = null;
		if ( !empty( $pageids ) ) {
			$db = $this->getDB();

			// Get pageIDs data from the `page` table
			$res = $db->newSelectQueryBuilder()
				->select( $this->getPageTableFields() )
				->from( 'page' )
				->where( [ 'page_id' => $pageids ] )
				->caller( __METHOD__ )
				->fetchResultSet();
		}

		$this->initFromQueryResult( $res, $remaining, false ); // process PageIDs

		// Resolve any found redirects
		$this->resolvePendingRedirects();
	}

	/**
	 * Iterate through the result of the query on 'page' table,
	 * and for each row create and store title object and save any extra fields requested.
	 * @param IResultWrapper|null $res DB Query result
	 * @param array|null &$remaining Array of either pageID or ns/title elements (optional).
	 *        If given, any missing items will go to $mMissingPageIDs and $mMissingTitles
	 * @param bool|null $processTitles Must be provided together with $remaining.
	 *        If true, treat $remaining as an array of [ns][title]
	 *        If false, treat it as an array of [pageIDs]
	 */
	private function initFromQueryResult( $res, &$remaining = null, $processTitles = null ) {
		if ( $remaining !== null && $processTitles === null ) {
			ApiBase::dieDebug( __METHOD__, 'Missing $processTitles parameter when $remaining is provided' );
		}

		$usernames = [];
		if ( $res ) {
			foreach ( $res as $row ) {
				$pageId = (int)$row->page_id;

				// Remove found page from the list of remaining items
				if ( $remaining ) {
					if ( $processTitles ) {
						unset( $remaining[$row->page_namespace][$row->page_title] );
					} else {
						unset( $remaining[$pageId] );
					}
				}

				// Store any extra fields requested by modules
				$this->processDbRow( $row );

				// Need gender information
				if ( $this->namespaceInfo->hasGenderDistinction( $row->page_namespace ) ) {
					$usernames[] = $row->page_title;
				}
			}
		}

		if ( $remaining ) {
			// Any items left in the $remaining list are added as missing
			if ( $processTitles ) {
				// The remaining titles in $remaining are non-existent pages
				foreach ( $remaining as $ns => $dbkeys ) {
					foreach ( array_keys( $dbkeys ) as $dbkey ) {
						$title = $this->titleFactory->makeTitle( $ns, $dbkey );
						$this->linkCache->addBadLinkObj( $title );
						$this->mAllPages[$ns][$dbkey] = $this->mFakePageId;
						$this->mMissingPages[$ns][$dbkey] = $this->mFakePageId;
						$this->mGoodAndMissingPages[$ns][$dbkey] = $this->mFakePageId;
						$this->mMissingTitles[$this->mFakePageId] = $title;
						$this->mFakePageId--;
						$this->mTitles[] = $title;

						// need gender information
						if ( $this->namespaceInfo->hasGenderDistinction( $ns ) ) {
							$usernames[] = $dbkey;
						}
					}
				}
			} else {
				// The remaining pageids do not exist
				if ( !$this->mMissingPageIDs ) {
					$this->mMissingPageIDs = array_keys( $remaining );
				} else {
					$this->mMissingPageIDs = array_merge( $this->mMissingPageIDs, array_keys( $remaining ) );
				}
			}
		}

		// Get gender information
		$this->genderCache->doQuery( $usernames, __METHOD__ );
	}

	/**
	 * Does the same as initFromTitles(), but is based on revision IDs
	 * instead
	 * @param int[] $revids Array of revision IDs
	 */
	private function initFromRevIDs( $revids ) {
		if ( !$revids ) {
			return;
		}

		$revids = array_map( 'intval', $revids ); // paranoia
		$db = $this->getDB();
		$pageids = [];
		$remaining = array_fill_keys( $revids, true );

		$revids = $this->filterIDs( [ [ 'revision', 'rev_id' ], [ 'archive', 'ar_rev_id' ] ], $revids );
		$goodRemaining = array_fill_keys( $revids, true );

		if ( $revids ) {
			$fields = [ 'rev_id', 'rev_page' ];

			// Get pageIDs data from the `page` table
			$res = $db->newSelectQueryBuilder()
				->select( $fields )
				->from( 'page' )
				->where( [ 'rev_id' => $revids ] )
				->join( 'revision', null, [ 'rev_page = page_id' ] )
				->caller( __METHOD__ )
				->fetchResultSet();
			foreach ( $res as $row ) {
				$revid = (int)$row->rev_id;
				$pageid = (int)$row->rev_page;
				$this->mGoodRevIDs[$revid] = $pageid;
				$this->mLiveRevIDs[$revid] = $pageid;
				$pageids[$pageid] = '';
				unset( $remaining[$revid] );
				unset( $goodRemaining[$revid] );
			}
		}

		// Populate all the page information
		$this->initFromPageIds( array_keys( $pageids ), false );

		// If the user can see deleted revisions, pull out the corresponding
		// titles from the archive table and include them too. We ignore
		// ar_page_id because deleted revisions are tied by title, not page_id.
		if ( $goodRemaining &&
			$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {

			$res = $db->newSelectQueryBuilder()
				->select( [ 'ar_rev_id', 'ar_namespace', 'ar_title' ] )
				->from( 'archive' )
				->where( [ 'ar_rev_id' => array_keys( $goodRemaining ) ] )
				->caller( __METHOD__ )
				->fetchResultSet();

			$titles = [];
			foreach ( $res as $row ) {
				$revid = (int)$row->ar_rev_id;
				$titles[$revid] = $this->titleFactory->makeTitle( $row->ar_namespace, $row->ar_title );
				unset( $remaining[$revid] );
			}

			$this->initFromTitles( $titles );

			foreach ( $titles as $revid => $title ) {
				$ns = $title->getNamespace();
				$dbkey = $title->getDBkey();

				// Handle converted titles
				if ( !isset( $this->mAllPages[$ns][$dbkey] ) &&
					isset( $this->mConvertedTitles[$title->getPrefixedText()] )
				) {
					$title = $this->titleFactory->newFromText( $this->mConvertedTitles[$title->getPrefixedText()] );
					$ns = $title->getNamespace();
					$dbkey = $title->getDBkey();
				}

				if ( isset( $this->mAllPages[$ns][$dbkey] ) ) {
					$this->mGoodRevIDs[$revid] = $this->mAllPages[$ns][$dbkey];
					$this->mDeletedRevIDs[$revid] = $this->mAllPages[$ns][$dbkey];
				} else {
					$remaining[$revid] = true;
				}
			}
		}

		$this->mMissingRevIDs = array_keys( $remaining );
	}

	/**
	 * Resolve any redirects in the result if redirect resolution was
	 * requested. This function is called repeatedly until all redirects
	 * have been resolved.
	 */
	private function resolvePendingRedirects() {
		if ( $this->mResolveRedirects ) {
			$db = $this->getDB();

			// Repeat until all redirects have been resolved
			// The infinite loop is prevented by keeping all known pages in $this->mAllPages
			while ( $this->mPendingRedirectIDs || $this->mPendingRedirectSpecialPages ) {
				// Resolve redirects by querying the pagelinks table, and repeat the process
				// Create a new linkBatch object for the next pass
				$linkBatch = $this->loadRedirectTargets();

				if ( $linkBatch->isEmpty() ) {
					break;
				}

				$set = $linkBatch->constructSet( 'page', $db );
				if ( $set === false ) {
					break;
				}

				// Get pageIDs data from the `page` table
				$res = $db->newSelectQueryBuilder()
					->select( $this->getPageTableFields() )
					->from( 'page' )
					->where( $set )
					->caller( __METHOD__ )
					->fetchResultSet();

				// Hack: get the ns:titles stored in [ns => array(titles)] format
				$this->initFromQueryResult( $res, $linkBatch->data, true );
			}
		}
	}

	/**
	 * Get the targets of the pending redirects from the database
	 *
	 * Also creates entries in the redirect table for redirects that don't
	 * have one.
	 * @return LinkBatch
	 */
	private function loadRedirectTargets() {
		$titlesToResolve = [];
		$db = $this->getDB();

		if ( $this->mPendingRedirectIDs ) {
			$res = $db->newSelectQueryBuilder()
				->select( [
					'rd_from',
					'rd_namespace',
					'rd_fragment',
					'rd_interwiki',
					'rd_title'
				] )
				->from( 'redirect' )
				->where( [ 'rd_from' => array_keys( $this->mPendingRedirectIDs ) ] )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $res as $row ) {
				$rdfrom = (int)$row->rd_from;
				$from = $this->mPendingRedirectIDs[$rdfrom]->getPrefixedText();
				$to = $this->titleFactory->makeTitle(
					$row->rd_namespace,
					$row->rd_title,
					$row->rd_fragment ?? '',
					$row->rd_interwiki ?? ''
				);
				$this->mResolvedRedirectTitles[$from] = $this->mPendingRedirectIDs[$rdfrom];
				unset( $this->mPendingRedirectIDs[$rdfrom] );
				if ( $to->isExternal() ) {
					$this->mInterwikiTitles[$to->getPrefixedText()] = $to->getInterwiki();
				} elseif ( !isset( $this->mAllPages[$to->getNamespace()][$to->getDBkey()] ) ) {
					$titlesToResolve[] = $to;
				}
				$this->mRedirectTitles[$from] = $to;
			}

			if ( $this->mPendingRedirectIDs ) {
				// We found pages that aren't in the redirect table
				// Add them
				foreach ( $this->mPendingRedirectIDs as $id => $title ) {
					$page = $this->wikiPageFactory->newFromTitle( $title );
					$rt = $page->insertRedirect();
					if ( !$rt ) {
						// What the hell. Let's just ignore this
						continue;
					}
					if ( $rt->isExternal() ) {
						$this->mInterwikiTitles[$rt->getPrefixedText()] = $rt->getInterwiki();
					} elseif ( !isset( $this->mAllPages[$rt->getNamespace()][$rt->getDBkey()] ) ) {
						$titlesToResolve[] = $rt;
					}
					$from = $title->getPrefixedText();
					$this->mResolvedRedirectTitles[$from] = $title;
					$this->mRedirectTitles[$from] = $rt;
					unset( $this->mPendingRedirectIDs[$id] );
				}
			}
		}

		if ( $this->mPendingRedirectSpecialPages ) {
			foreach ( $this->mPendingRedirectSpecialPages as [ $from, $to ] ) {
				/** @var Title $from */
				$fromKey = $from->getPrefixedText();
				$this->mResolvedRedirectTitles[$fromKey] = $from;
				$this->mRedirectTitles[$fromKey] = $to;
				if ( $to->isExternal() ) {
					$this->mInterwikiTitles[$to->getPrefixedText()] = $to->getInterwiki();
				} elseif ( !isset( $this->mAllPages[$to->getNamespace()][$to->getDBkey()] ) ) {
					$titlesToResolve[] = $to;
				}
			}
			$this->mPendingRedirectSpecialPages = [];

			// Set private caching since we don't know what criteria the
			// special pages used to decide on these redirects.
			$this->mCacheMode = 'private';
		}

		return $this->processTitlesArray( $titlesToResolve );
	}

	/**
	 * Get the cache mode for the data generated by this module.
	 * All PageSet users should take into account whether this returns a more-restrictive
	 * cache mode than the using module itself. For possible return values and other
	 * details about cache modes, see ApiMain::setCacheMode()
	 *
	 * Public caching will only be allowed if *all* the modules that supply
	 * data for a given request return a cache mode of public.
	 *
	 * @param array|null $params
	 * @return string
	 * @since 1.21
	 */
	public function getCacheMode( $params = null ) {
		return $this->mCacheMode;
	}

	/**
	 * Given an array of title strings, convert them into Title objects.
	 * Alternatively, an array of Title objects may be given.
	 * This method validates access rights for the title,
	 * and appends normalization values to the output.
	 *
	 * @param string[]|LinkTarget[]|PageReference[] $titles
	 * @return LinkBatch
	 */
	private function processTitlesArray( $titles ) {
		$linkBatch = $this->linkBatchFactory->newLinkBatch();

		/** @var Title[] $titleObjects */
		$titleObjects = [];
		foreach ( $titles as $index => $title ) {
			if ( is_string( $title ) ) {
				try {
					/** @var Title $titleObj */
					$titleObj = $this->titleFactory->newFromTextThrow( $title, $this->mDefaultNamespace );
				} catch ( MalformedTitleException $ex ) {
					// Handle invalid titles gracefully
					if ( !isset( $this->mAllPages[0][$title] ) ) {
						$this->mAllPages[0][$title] = $this->mFakePageId;
						$this->mInvalidTitles[$this->mFakePageId] = [
							'title' => $title,
							'invalidreason' => $this->getErrorFormatter()->formatException( $ex, [ 'bc' => true ] ),
						];
						$this->mFakePageId--;
					}
					continue; // There's nothing else we can do
				}
			} elseif ( $title instanceof LinkTarget ) {
				$titleObj = $this->titleFactory->castFromLinkTarget( $title );
			} else {
				$titleObj = $this->titleFactory->castFromPageReference( $title );
			}

			$titleObjects[$index] = $titleObj;
		}

		// Get gender information
		$this->genderCache->doTitlesArray( $titleObjects, __METHOD__ );

		foreach ( $titleObjects as $index => $titleObj ) {
			$title = is_string( $titles[$index] ) ? $titles[$index] : false;
			$unconvertedTitle = $titleObj->getPrefixedText();
			$titleWasConverted = false;
			if ( $titleObj->isExternal() ) {
				// This title is an interwiki link.
				$this->mInterwikiTitles[$unconvertedTitle] = $titleObj->getInterwiki();
			} else {
				// Variants checking
				if (
					$this->mConvertTitles
					&& $this->languageConverter->hasVariants()
					&& !$titleObj->exists()
				) {
					// ILanguageConverter::findVariantLink will modify titleText and
					// titleObj into the canonical variant if possible
					$titleText = $title !== false ? $title : $titleObj->getPrefixedText();
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable castFrom does not return null here
					$this->languageConverter->findVariantLink( $titleText, $titleObj );
					$titleWasConverted = $unconvertedTitle !== $titleObj->getPrefixedText();
				}

				if ( $titleObj->getNamespace() < 0 ) {
					// Handle Special and Media pages
					$titleObj = $titleObj->fixSpecialName();
					$ns = $titleObj->getNamespace();
					$dbkey = $titleObj->getDBkey();
					if ( !isset( $this->mAllSpecials[$ns][$dbkey] ) ) {
						$this->mAllSpecials[$ns][$dbkey] = $this->mFakePageId;
						$target = null;
						if ( $ns === NS_SPECIAL && $this->mResolveRedirects ) {
							$special = $this->specialPageFactory->getPage( $dbkey );
							if ( $special instanceof RedirectSpecialArticle ) {
								// Only RedirectSpecialArticle is intended to redirect to an article, other kinds of
								// RedirectSpecialPage are probably applying weird URL parameters we don't want to
								// handle.
								$context = new DerivativeContext( $this );
								$context->setTitle( $titleObj );
								$context->setRequest( new FauxRequest );
								$special->setContext( $context );
								list( /* $alias */, $subpage ) = $this->specialPageFactory->resolveAlias( $dbkey );
								$target = $special->getRedirect( $subpage );
							}
						}
						if ( $target ) {
							$this->mPendingRedirectSpecialPages[$dbkey] = [ $titleObj, $target ];
						} else {
							$this->mSpecialTitles[$this->mFakePageId] = $titleObj;
							$this->mFakePageId--;
						}
					}
				} else {
					// Regular page
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable castFrom does not return null here
					$linkBatch->addObj( $titleObj );
				}
			}

			// Make sure we remember the original title that was
			// given to us. This way the caller can correlate new
			// titles with the originally requested when e.g. the
			// namespace is localized or the capitalization is
			// different
			if ( $titleWasConverted ) {
				$this->mConvertedTitles[$unconvertedTitle] = $titleObj->getPrefixedText();
				// In this case the page can't be Special.
				if ( $title !== false && $title !== $unconvertedTitle ) {
					$this->mNormalizedTitles[$title] = $unconvertedTitle;
				}
			} elseif ( $title !== false && $title !== $titleObj->getPrefixedText() ) {
				$this->mNormalizedTitles[$title] = $titleObj->getPrefixedText();
			}
		}

		return $linkBatch;
	}

	/**
	 * Set data for a title.
	 *
	 * This data may be extracted into an ApiResult using
	 * self::populateGeneratorData. This should generally be limited to
	 * data that is likely to be particularly useful to end users rather than
	 * just being a dump of everything returned in non-generator mode.
	 *
	 * Redirects here will *not* be followed, even if 'redirects' was
	 * specified, since in the case of multiple redirects we can't know which
	 * source's data to use on the target.
	 *
	 * @param PageReference|LinkTarget $title
	 * @param array $data
	 */
	public function setGeneratorData( $title, array $data ) {
		$ns = $title->getNamespace();
		$dbkey = $title->getDBkey();
		$this->mGeneratorData[$ns][$dbkey] = $data;
	}

	/**
	 * Controls how generator data about a redirect source is merged into
	 * the generator data for the redirect target. When not set no data
	 * is merged. Note that if multiple titles redirect to the same target
	 * the order of operations is undefined.
	 *
	 * Example to include generated data from redirect in target, prefering
	 * the data generated for the destination when there is a collision:
	 * @code
	 *   $pageSet->setRedirectMergePolicy( function( array $current, array $new ) {
	 *       return $current + $new;
	 *   } );
	 * @endcode
	 *
	 * @param callable|null $callable Recieves two array arguments, first the
	 *  generator data for the redirect target and second the generator data
	 *  for the redirect source. Returns the resulting generator data to use
	 *  for the redirect target.
	 */
	public function setRedirectMergePolicy( $callable ) {
		$this->mRedirectMergePolicy = $callable;
	}

	/**
	 * Resolve the title a redirect points to.
	 *
	 * Will follow sequential redirects to find the final page. In
	 * the case of a redirect cycle the original page will be returned.
	 * self::resolvePendingRedirects must be executed before calling
	 * this method.
	 *
	 * @param Title $titleFrom A title from $this->mResolvedRedirectTitles
	 * @return Title
	 */
	private function resolveRedirectTitleDest( Title $titleFrom ): Title {
		$seen = [];
		$dest = $titleFrom;
		while ( isset( $this->mRedirectTitles[$dest->getPrefixedText()] ) ) {
			$dest = $this->mRedirectTitles[$dest->getPrefixedText()];
			if ( isset( $seen[$dest->getPrefixedText()] ) ) {
				return $titleFrom;
			}
			$seen[$dest->getPrefixedText()] = true;
		}
		return $dest;
	}

	/**
	 * Populate the generator data for all titles in the result
	 *
	 * The page data may be inserted into an ApiResult object or into an
	 * associative array. The $path parameter specifies the path within the
	 * ApiResult or array to find the "pages" node.
	 *
	 * The "pages" node itself must be an associative array mapping the page ID
	 * or fake page ID values returned by this pageset (see
	 * self::getAllTitlesByNamespace() and self::getSpecialTitles()) to
	 * associative arrays of page data. Each of those subarrays will have the
	 * data from self::setGeneratorData() merged in.
	 *
	 * Data that was set by self::setGeneratorData() for pages not in the
	 * "pages" node will be ignored.
	 *
	 * @param ApiResult|array &$result
	 * @param array $path
	 * @return bool Whether the data fit
	 */
	public function populateGeneratorData( &$result, array $path = [] ) {
		if ( $result instanceof ApiResult ) {
			$data = $result->getResultData( $path );
			if ( $data === null ) {
				return true;
			}
		} else {
			$data = &$result;
			foreach ( $path as $key ) {
				if ( !isset( $data[$key] ) ) {
					// Path isn't in $result, so nothing to add, so everything
					// "fits"
					return true;
				}
				$data = &$data[$key];
			}
		}
		foreach ( $this->mGeneratorData as $ns => $dbkeys ) {
			if ( $ns === NS_SPECIAL ) {
				$pages = [];
				foreach ( $this->mSpecialTitles as $id => $title ) {
					$pages[$title->getDBkey()] = $id;
				}
			} else {
				if ( !isset( $this->mAllPages[$ns] ) ) {
					// No known titles in the whole namespace. Skip it.
					continue;
				}
				$pages = $this->mAllPages[$ns];
			}
			foreach ( $dbkeys as $dbkey => $genData ) {
				if ( !isset( $pages[$dbkey] ) ) {
					// Unknown title. Forget it.
					continue;
				}
				$pageId = $pages[$dbkey];
				if ( !isset( $data[$pageId] ) ) {
					// $pageId didn't make it into the result. Ignore it.
					continue;
				}

				if ( $result instanceof ApiResult ) {
					$path2 = array_merge( $path, [ $pageId ] );
					foreach ( $genData as $key => $value ) {
						if ( !$result->addValue( $path2, $key, $value ) ) {
							return false;
						}
					}
				} else {
					$data[$pageId] = array_merge( $data[$pageId], $genData );
				}
			}
		}

		// Merge data generated about redirect titles into the redirect destination
		if ( $this->mRedirectMergePolicy ) {
			foreach ( $this->mResolvedRedirectTitles as $titleFrom ) {
				$dest = $this->resolveRedirectTitleDest( $titleFrom );
				$fromNs = $titleFrom->getNamespace();
				$fromDBkey = $titleFrom->getDBkey();
				$toPageId = $dest->getArticleID();
				if ( isset( $data[$toPageId] ) &&
					isset( $this->mGeneratorData[$fromNs][$fromDBkey] )
				) {
					// It is necessary to set both $data and add to $result, if an ApiResult,
					// to ensure multiple redirects to the same destination are all merged.
					$data[$toPageId] = call_user_func(
						$this->mRedirectMergePolicy,
						$data[$toPageId],
						$this->mGeneratorData[$fromNs][$fromDBkey]
					);
					if ( $result instanceof ApiResult &&
						!$result->addValue( $path, $toPageId, $data[$toPageId], ApiResult::OVERRIDE )
					) {
						return false;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Get the database connection (read-only)
	 * @return IDatabase
	 */
	protected function getDB() {
		return $this->mDbSource->getDB();
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'titles' => [
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG => 'api-pageset-param-titles',
			],
			'pageids' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG => 'api-pageset-param-pageids',
			],
			'revids' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG => 'api-pageset-param-revids',
			],
			'generator' => [
				ParamValidator::PARAM_TYPE => null,
				ApiBase::PARAM_HELP_MSG => 'api-pageset-param-generator',
				SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX => 'g',
			],
			'redirects' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => $this->mAllowGenerator
					? 'api-pageset-param-redirects-generator'
					: 'api-pageset-param-redirects-nogenerator',
			],
			'converttitles' => [
				ParamValidator::PARAM_DEFAULT => false,
				ApiBase::PARAM_HELP_MSG => [
					'api-pageset-param-converttitles',
					[ Message::listParam( LanguageConverter::$languagesWithVariants, 'text' ) ],
				],
			],
		];

		if ( !$this->mAllowGenerator ) {
			unset( $result['generator'] );
		} elseif ( $flags & ApiBase::GET_VALUES_FOR_HELP ) {
			$result['generator'][ParamValidator::PARAM_TYPE] = 'submodule';
			$result['generator'][SubmoduleDef::PARAM_SUBMODULE_MAP] = $this->getGenerators();
		}

		return $result;
	}

	public function handleParamNormalization( $paramName, $value, $rawValue ) {
		parent::handleParamNormalization( $paramName, $value, $rawValue );

		if ( $paramName === 'titles' ) {
			// For the 'titles' parameter, we want to split it like ApiBase would
			// and add any changed titles to $this->mNormalizedTitles
			$value = ParamValidator::explodeMultiValue( $value, self::LIMIT_SML2 + 1 );
			$l = count( $value );
			$rawValue = ParamValidator::explodeMultiValue( $rawValue, $l );
			for ( $i = 0; $i < $l; $i++ ) {
				if ( $value[$i] !== $rawValue[$i] ) {
					$this->mNormalizedTitles[$rawValue[$i]] = $value[$i];
				}
			}
		}
	}

	/**
	 * Get an array of all available generators
	 * @return string[]
	 */
	private function getGenerators() {
		if ( self::$generators === null ) {
			$query = $this->mDbSource;
			if ( !( $query instanceof ApiQuery ) ) {
				// If the parent container of this pageset is not ApiQuery,
				// we must create it to get module manager
				$query = $this->getMain()->getModuleManager()->getModule( 'query' );
			}
			$gens = [];
			$prefix = $query->getModulePath() . '+';
			$mgr = $query->getModuleManager();
			foreach ( $mgr->getNamesWithClasses() as $name => $class ) {
				if ( is_subclass_of( $class, ApiQueryGeneratorBase::class ) ) {
					$gens[$name] = $prefix . $name;
				}
			}
			ksort( $gens );
			self::$generators = $gens;
		}

		return self::$generators;
	}
}
