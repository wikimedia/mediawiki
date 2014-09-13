<?php
/**
 *
 *
 * Created on Sep 24, 2006
 *
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
	const DISABLE_GENERATORS = 1;

	private $mDbSource;
	private $mParams;
	private $mResolveRedirects;
	private $mConvertTitles;
	private $mAllowGenerator;

	private $mAllPages = array(); // [ns][dbkey] => page_id or negative when missing
	private $mTitles = array();
	private $mGoodTitles = array();
	private $mMissingTitles = array();
	private $mInvalidTitles = array();
	private $mMissingPageIDs = array();
	private $mRedirectTitles = array();
	private $mSpecialTitles = array();
	private $mNormalizedTitles = array();
	private $mInterwikiTitles = array();
	private $mPendingRedirectIDs = array();
	private $mConvertedTitles = array();
	private $mGoodRevIDs = array();
	private $mMissingRevIDs = array();
	private $mFakePageId = -1;
	private $mCacheMode = 'public';
	private $mRequestedPageFields = array();
	/**
	 * @var int
	 */
	private $mDefaultNamespace = NS_MAIN;

	/**
	 * Add all items from $values into the result
	 * @param array $result output
	 * @param array $values values to add
	 * @param string $flag the name of the boolean flag to mark this element
	 * @param string $name if given, name of the value
	 */
	private static function addValues( array &$result, $values, $flag = null, $name = null ) {
		foreach ( $values as $val ) {
			if ( $val instanceof Title ) {
				$v = array();
				ApiQueryBase::addTitleInfo( $v, $val );
			} elseif ( $name !== null ) {
				$v = array( $name => $val );
			} else {
				$v = $val;
			}
			if ( $flag !== null ) {
				$v[$flag] = '';
			}
			$result[] = $v;
		}
	}

	/**
	 * Constructor
	 * @param $dbSource ApiBase Module implementing getDB().
	 *        Allows PageSet to reuse existing db connection from the shared state like ApiQuery.
	 * @param int $flags Zero or more flags like DISABLE_GENERATORS
	 * @param int $defaultNamespace the namespace to use if none is specified by a prefix.
	 * @since 1.21 accepts $flags instead of two boolean values
	 */
	public function __construct( ApiBase $dbSource, $flags = 0, $defaultNamespace = NS_MAIN ) {
		parent::__construct( $dbSource->getMain(), $dbSource->getModuleName() );
		$this->mDbSource = $dbSource;
		$this->mAllowGenerator = ( $flags & ApiPageSet::DISABLE_GENERATORS ) == 0;
		$this->mDefaultNamespace = $defaultNamespace;

		$this->profileIn();
		$this->mParams = $this->extractRequestParams();
		$this->mResolveRedirects = $this->mParams['redirects'];
		$this->mConvertTitles = $this->mParams['converttitles'];
		$this->profileOut();
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
		$this->profileIn();

		$generatorName = $this->mAllowGenerator ? $this->mParams['generator'] : null;
		if ( isset( $generatorName ) ) {
			$dbSource = $this->mDbSource;
			$isQuery = $dbSource instanceof ApiQuery;
			if ( !$isQuery ) {
				// If the parent container of this pageset is not ApiQuery, we must create it to run generator
				$dbSource = $this->getMain()->getModuleManager()->getModule( 'query' );
				// Enable profiling for query module because it will be used for db sql profiling
				$dbSource->profileIn();
			}
			$generator = $dbSource->getModuleManager()->getModule( $generatorName, null, true );
			if ( $generator === null ) {
				$this->dieUsage( 'Unknown generator=' . $generatorName, 'badgenerator' );
			}
			if ( !$generator instanceof ApiQueryGeneratorBase ) {
				$this->dieUsage( "Module $generatorName cannot be used as a generator", 'badgenerator' );
			}
			// Create a temporary pageset to store generator's output,
			// add any additional fields generator may need, and execute pageset to populate titles/pageids
			$tmpPageSet = new ApiPageSet( $dbSource, ApiPageSet::DISABLE_GENERATORS );
			$generator->setGeneratorMode( $tmpPageSet );
			$this->mCacheMode = $generator->getCacheMode( $generator->extractRequestParams() );

			if ( !$isDryRun ) {
				$generator->requestExtraData( $tmpPageSet );
			}
			$tmpPageSet->executeInternal( $isDryRun );

			// populate this pageset with the generator output
			$this->profileOut();
			$generator->profileIn();

			if ( !$isDryRun ) {
				$generator->executeGenerator( $this );
				wfRunHooks( 'APIQueryGeneratorAfterExecute', array( &$generator, &$this ) );
			} else {
				// Prevent warnings from being reported on these parameters
				$main = $this->getMain();
				foreach ( $generator->extractRequestParams() as $paramName => $param ) {
					$main->getVal( $generator->encodeParamName( $paramName ) );
				}
			}
			$generator->profileOut();
			$this->profileIn();

			if ( !$isDryRun ) {
				$this->resolvePendingRedirects();
			}

			if ( !$isQuery ) {
				// If this pageset is not part of the query, we called profileIn() above
				$dbSource->profileOut();
			}
		} else {
			// Only one of the titles/pageids/revids is allowed at the same time
			$dataSource = null;
			if ( isset( $this->mParams['titles'] ) ) {
				$dataSource = 'titles';
			}
			if ( isset( $this->mParams['pageids'] ) ) {
				if ( isset( $dataSource ) ) {
					$this->dieUsage( "Cannot use 'pageids' at the same time as '$dataSource'", 'multisource' );
				}
				$dataSource = 'pageids';
			}
			if ( isset( $this->mParams['revids'] ) ) {
				if ( isset( $dataSource ) ) {
					$this->dieUsage( "Cannot use 'revids' at the same time as '$dataSource'", 'multisource' );
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
							$this->setWarning( 'Redirect resolution cannot be used ' .
								'together with the revids= parameter. Any redirects ' .
								'the revids= point to have not been resolved.' );
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
		$this->profileOut();
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
	 * @param string $fieldName Field name
	 */
	public function requestField( $fieldName ) {
		$this->mRequestedPageFields[$fieldName] = null;
	}

	/**
	 * Get the value of a custom field previously requested through
	 * requestField()
	 * @param string $fieldName Field name
	 * @return mixed Field value
	 */
	public function getCustomField( $fieldName ) {
		return $this->mRequestedPageFields[$fieldName];
	}

	/**
	 * Get the fields that have to be queried from the page table:
	 * the ones requested through requestField() and a few basic ones
	 * we always need
	 * @return array of field names
	 */
	public function getPageTableFields() {
		// Ensure we get minimum required fields
		// DON'T change this order
		$pageFlds = array(
			'page_namespace' => null,
			'page_title' => null,
			'page_id' => null,
		);

		if ( $this->mResolveRedirects ) {
			$pageFlds['page_is_redirect'] = null;
		}

		// only store non-default fields
		$this->mRequestedPageFields = array_diff_key( $this->mRequestedPageFields, $pageFlds );

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
	 * All Title objects provided.
	 * @return Title[]
	 */
	public function getTitles() {
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
	 * Title objects that were found in the database.
	 * @return Title[] Array page_id (int) => Title (obj)
	 */
	public function getGoodTitles() {
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
	 * Title objects that were NOT found in the database.
	 * The array's index will be negative for each item
	 * @return Title[]
	 */
	public function getMissingTitles() {
		return $this->mMissingTitles;
	}

	/**
	 * Titles that were deemed invalid by Title::newFromText()
	 * The array's index will be unique and negative for each item
	 * @return string[] Array of strings (not Title objects)
	 */
	public function getInvalidTitles() {
		return $this->mInvalidTitles;
	}

	/**
	 * Page IDs that were not found in the database
	 * @return array of page IDs
	 */
	public function getMissingPageIDs() {
		return $this->mMissingPageIDs;
	}

	/**
	 * Get a list of redirect resolutions - maps a title to its redirect
	 * target, as an array of output-ready arrays
	 * @return array
	 */
	public function getRedirectTitles() {
		return $this->mRedirectTitles;
	}

	/**
	 * Get a list of redirect resolutions - maps a title to its redirect
	 * target.
	 * @param $result ApiResult
	 * @return array of prefixed_title (string) => Title object
	 * @since 1.21
	 */
	public function getRedirectTitlesAsResult( $result = null ) {
		$values = array();
		foreach ( $this->getRedirectTitles() as $titleStrFrom => $titleTo ) {
			$r = array(
				'from' => strval( $titleStrFrom ),
				'to' => $titleTo->getPrefixedText(),
			);
			if ( $titleTo->hasFragment() ) {
				$r['tofragment'] = $titleTo->getFragment();
			}
			$values[] = $r;
		}
		if ( !empty( $values ) && $result ) {
			$result->setIndexedTagName( $values, 'r' );
		}

		return $values;
	}

	/**
	 * Get a list of title normalizations - maps a title to its normalized
	 * version.
	 * @return array raw_prefixed_title (string) => prefixed_title (string)
	 */
	public function getNormalizedTitles() {
		return $this->mNormalizedTitles;
	}

	/**
	 * Get a list of title normalizations - maps a title to its normalized
	 * version in the form of result array.
	 * @param $result ApiResult
	 * @return array of raw_prefixed_title (string) => prefixed_title (string)
	 * @since 1.21
	 */
	public function getNormalizedTitlesAsResult( $result = null ) {
		$values = array();
		foreach ( $this->getNormalizedTitles() as $rawTitleStr => $titleStr ) {
			$values[] = array(
				'from' => $rawTitleStr,
				'to' => $titleStr
			);
		}
		if ( !empty( $values ) && $result ) {
			$result->setIndexedTagName( $values, 'n' );
		}

		return $values;
	}

	/**
	 * Get a list of title conversions - maps a title to its converted
	 * version.
	 * @return array raw_prefixed_title (string) => prefixed_title (string)
	 */
	public function getConvertedTitles() {
		return $this->mConvertedTitles;
	}

	/**
	 * Get a list of title conversions - maps a title to its converted
	 * version as a result array.
	 * @param $result ApiResult
	 * @return array of (from, to) strings
	 * @since 1.21
	 */
	public function getConvertedTitlesAsResult( $result = null ) {
		$values = array();
		foreach ( $this->getConvertedTitles() as $rawTitleStr => $titleStr ) {
			$values[] = array(
				'from' => $rawTitleStr,
				'to' => $titleStr
			);
		}
		if ( !empty( $values ) && $result ) {
			$result->setIndexedTagName( $values, 'c' );
		}

		return $values;
	}

	/**
	 * Get a list of interwiki titles - maps a title to its interwiki
	 * prefix.
	 * @return array raw_prefixed_title (string) => interwiki_prefix (string)
	 */
	public function getInterwikiTitles() {
		return $this->mInterwikiTitles;
	}

	/**
	 * Get a list of interwiki titles - maps a title to its interwiki
	 * prefix as result.
	 * @param $result ApiResult
	 * @param $iwUrl boolean
	 * @return array raw_prefixed_title (string) => interwiki_prefix (string)
	 * @since 1.21
	 */
	public function getInterwikiTitlesAsResult( $result = null, $iwUrl = false ) {
		$values = array();
		foreach ( $this->getInterwikiTitles() as $rawTitleStr => $interwikiStr ) {
			$item = array(
				'title' => $rawTitleStr,
				'iw' => $interwikiStr,
			);
			if ( $iwUrl ) {
				$title = Title::newFromText( $rawTitleStr );
				$item['url'] = $title->getFullURL( '', false, PROTO_CURRENT );
			}
			$values[] = $item;
		}
		if ( !empty( $values ) && $result ) {
			$result->setIndexedTagName( $values, 'i' );
		}

		return $values;
	}

	/**
	 * Get an array of invalid/special/missing titles.
	 *
	 * @param $invalidChecks List of types of invalid titles to include.
	 *   Recognized values are:
	 *   - invalidTitles: Titles from $this->getInvalidTitles()
	 *   - special: Titles from $this->getSpecialTitles()
	 *   - missingIds: ids from $this->getMissingPageIDs()
	 *   - missingRevIds: ids from $this->getMissingRevisionIDs()
	 *   - missingTitles: Titles from $this->getMissingTitles()
	 *   - interwikiTitles: Titles from $this->getInterwikiTitlesAsResult()
	 * @return array Array suitable for inclusion in the response
	 * @since 1.23
	 */
	public function getInvalidTitlesAndRevisions( $invalidChecks = array( 'invalidTitles',
		'special', 'missingIds', 'missingRevIds', 'missingTitles', 'interwikiTitles' )
	) {
		$result = array();
		if ( in_array( "invalidTitles", $invalidChecks ) ) {
			self::addValues( $result, $this->getInvalidTitles(), 'invalid', 'title' );
		}
		if ( in_array( "special", $invalidChecks ) ) {
			self::addValues( $result, $this->getSpecialTitles(), 'special', 'title' );
		}
		if ( in_array( "missingIds", $invalidChecks ) ) {
			self::addValues( $result, $this->getMissingPageIDs(), 'missing', 'pageid' );
		}
		if ( in_array( "missingRevIds", $invalidChecks ) ) {
			self::addValues( $result, $this->getMissingRevisionIDs(), 'missing', 'revid' );
		}
		if ( in_array( "missingTitles", $invalidChecks ) ) {
			self::addValues( $result, $this->getMissingTitles(), 'missing' );
		}
		if ( in_array( "interwikiTitles", $invalidChecks ) ) {
			self::addValues( $result, $this->getInterwikiTitlesAsResult() );
		}

		return $result;
	}

	/**
	 * Get the list of revision IDs (requested with the revids= parameter)
	 * @return array revID (int) => pageID (int)
	 */
	public function getRevisionIDs() {
		return $this->mGoodRevIDs;
	}

	/**
	 * Revision IDs that were not found in the database
	 * @return array of revision IDs
	 */
	public function getMissingRevisionIDs() {
		return $this->mMissingRevIDs;
	}

	/**
	 * Revision IDs that were not found in the database as result array.
	 * @param $result ApiResult
	 * @return array of revision IDs
	 * @since 1.21
	 */
	public function getMissingRevisionIDsAsResult( $result = null ) {
		$values = array();
		foreach ( $this->getMissingRevisionIDs() as $revid ) {
			$values[$revid] = array(
				'revid' => $revid
			);
		}
		if ( !empty( $values ) && $result ) {
			$result->setIndexedTagName( $values, 'rev' );
		}

		return $values;
	}

	/**
	 * Get the list of titles with negative namespace
	 * @return array Title
	 */
	public function getSpecialTitles() {
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
	 * Populate this PageSet from a list of Titles
	 * @param array $titles of Title objects
	 */
	public function populateFromTitles( $titles ) {
		$this->profileIn();
		$this->initFromTitles( $titles );
		$this->profileOut();
	}

	/**
	 * Populate this PageSet from a list of page IDs
	 * @param array $pageIDs of page IDs
	 */
	public function populateFromPageIDs( $pageIDs ) {
		$this->profileIn();
		$this->initFromPageIds( $pageIDs );
		$this->profileOut();
	}

	/**
	 * Populate this PageSet from a rowset returned from the database
	 * @param $db DatabaseBase object
	 * @param $queryResult ResultWrapper Query result object
	 */
	public function populateFromQueryResult( $db, $queryResult ) {
		$this->profileIn();
		$this->initFromQueryResult( $queryResult );
		$this->profileOut();
	}

	/**
	 * Populate this PageSet from a list of revision IDs
	 * @param array $revIDs of revision IDs
	 */
	public function populateFromRevisionIDs( $revIDs ) {
		$this->profileIn();
		$this->initFromRevIDs( $revIDs );
		$this->profileOut();
	}

	/**
	 * Extract all requested fields from the row received from the database
	 * @param stdClass $row Result row
	 */
	public function processDbRow( $row ) {
		// Store Title object in various data structures
		$title = Title::newFromRow( $row );

		$pageId = intval( $row->page_id );
		$this->mAllPages[$row->page_namespace][$row->page_title] = $pageId;
		$this->mTitles[] = $title;

		if ( $this->mResolveRedirects && $row->page_is_redirect == '1' ) {
			$this->mPendingRedirectIDs[$pageId] = $title;
		} else {
			$this->mGoodTitles[$pageId] = $title;
		}

		foreach ( $this->mRequestedPageFields as $fieldName => &$fieldValues ) {
			$fieldValues[$pageId] = $row->$fieldName;
		}
	}

	/**
	 * Do not use, does nothing, will be removed
	 * @deprecated since 1.21
	 */
	public function finishPageSetGeneration() {
		wfDeprecated( __METHOD__, '1.21' );
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
	 * @param array $titles of Title objects or strings
	 */
	private function initFromTitles( $titles ) {
		// Get validated and normalized title objects
		$linkBatch = $this->processTitlesArray( $titles );
		if ( $linkBatch->isEmpty() ) {
			return;
		}

		$db = $this->getDB();
		$set = $linkBatch->constructSet( 'page', $db );

		// Get pageIDs data from the `page` table
		$this->profileDBIn();
		$res = $db->select( 'page', $this->getPageTableFields(), $set,
			__METHOD__ );
		$this->profileDBOut();

		// Hack: get the ns:titles stored in array(ns => array(titles)) format
		$this->initFromQueryResult( $res, $linkBatch->data, true ); // process Titles

		// Resolve any found redirects
		$this->resolvePendingRedirects();
	}

	/**
	 * Does the same as initFromTitles(), but is based on page IDs instead
	 * @param array $pageids of page IDs
	 */
	private function initFromPageIds( $pageids ) {
		if ( !$pageids ) {
			return;
		}

		$pageids = array_map( 'intval', $pageids ); // paranoia
		$remaining = array_flip( $pageids );

		$pageids = self::getPositiveIntegers( $pageids );

		$res = null;
		if ( !empty( $pageids ) ) {
			$set = array(
				'page_id' => $pageids
			);
			$db = $this->getDB();

			// Get pageIDs data from the `page` table
			$this->profileDBIn();
			$res = $db->select( 'page', $this->getPageTableFields(), $set,
				__METHOD__ );
			$this->profileDBOut();
		}

		$this->initFromQueryResult( $res, $remaining, false ); // process PageIDs

		// Resolve any found redirects
		$this->resolvePendingRedirects();
	}

	/**
	 * Iterate through the result of the query on 'page' table,
	 * and for each row create and store title object and save any extra fields requested.
	 * @param $res ResultWrapper DB Query result
	 * @param array $remaining of either pageID or ns/title elements (optional).
	 *        If given, any missing items will go to $mMissingPageIDs and $mMissingTitles
	 * @param bool $processTitles Must be provided together with $remaining.
	 *        If true, treat $remaining as an array of [ns][title]
	 *        If false, treat it as an array of [pageIDs]
	 */
	private function initFromQueryResult( $res, &$remaining = null, $processTitles = null ) {
		if ( !is_null( $remaining ) && is_null( $processTitles ) ) {
			ApiBase::dieDebug( __METHOD__, 'Missing $processTitles parameter when $remaining is provided' );
		}

		$usernames = array();
		if ( $res ) {
			foreach ( $res as $row ) {
				$pageId = intval( $row->page_id );

				// Remove found page from the list of remaining items
				if ( isset( $remaining ) ) {
					if ( $processTitles ) {
						unset( $remaining[$row->page_namespace][$row->page_title] );
					} else {
						unset( $remaining[$pageId] );
					}
				}

				// Store any extra fields requested by modules
				$this->processDbRow( $row );

				// Need gender information
				if ( MWNamespace::hasGenderDistinction( $row->page_namespace ) ) {
					$usernames[] = $row->page_title;
				}
			}
		}

		if ( isset( $remaining ) ) {
			// Any items left in the $remaining list are added as missing
			if ( $processTitles ) {
				// The remaining titles in $remaining are non-existent pages
				foreach ( $remaining as $ns => $dbkeys ) {
					foreach ( array_keys( $dbkeys ) as $dbkey ) {
						$title = Title::makeTitle( $ns, $dbkey );
						$this->mAllPages[$ns][$dbkey] = $this->mFakePageId;
						$this->mMissingTitles[$this->mFakePageId] = $title;
						$this->mFakePageId--;
						$this->mTitles[] = $title;

						// need gender information
						if ( MWNamespace::hasGenderDistinction( $ns ) ) {
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
		$genderCache = GenderCache::singleton();
		$genderCache->doQuery( $usernames, __METHOD__ );
	}

	/**
	 * Does the same as initFromTitles(), but is based on revision IDs
	 * instead
	 * @param array $revids of revision IDs
	 */
	private function initFromRevIDs( $revids ) {
		if ( !$revids ) {
			return;
		}

		$revids = array_map( 'intval', $revids ); // paranoia
		$db = $this->getDB();
		$pageids = array();
		$remaining = array_flip( $revids );

		$revids = self::getPositiveIntegers( $revids );

		if ( !empty( $revids ) ) {
			$tables = array( 'revision', 'page' );
			$fields = array( 'rev_id', 'rev_page' );
			$where = array( 'rev_id' => $revids, 'rev_page = page_id' );

			// Get pageIDs data from the `page` table
			$this->profileDBIn();
			$res = $db->select( $tables, $fields, $where, __METHOD__ );
			foreach ( $res as $row ) {
				$revid = intval( $row->rev_id );
				$pageid = intval( $row->rev_page );
				$this->mGoodRevIDs[$revid] = $pageid;
				$pageids[$pageid] = '';
				unset( $remaining[$revid] );
			}
			$this->profileDBOut();
		}

		$this->mMissingRevIDs = array_keys( $remaining );

		// Populate all the page information
		$this->initFromPageIds( array_keys( $pageids ) );
	}

	/**
	 * Resolve any redirects in the result if redirect resolution was
	 * requested. This function is called repeatedly until all redirects
	 * have been resolved.
	 */
	private function resolvePendingRedirects() {
		if ( $this->mResolveRedirects ) {
			$db = $this->getDB();
			$pageFlds = $this->getPageTableFields();

			// Repeat until all redirects have been resolved
			// The infinite loop is prevented by keeping all known pages in $this->mAllPages
			while ( $this->mPendingRedirectIDs ) {
				// Resolve redirects by querying the pagelinks table, and repeat the process
				// Create a new linkBatch object for the next pass
				$linkBatch = $this->getRedirectTargets();

				if ( $linkBatch->isEmpty() ) {
					break;
				}

				$set = $linkBatch->constructSet( 'page', $db );
				if ( $set === false ) {
					break;
				}

				// Get pageIDs data from the `page` table
				$this->profileDBIn();
				$res = $db->select( 'page', $pageFlds, $set, __METHOD__ );
				$this->profileDBOut();

				// Hack: get the ns:titles stored in array(ns => array(titles)) format
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
	private function getRedirectTargets() {
		$lb = new LinkBatch();
		$db = $this->getDB();

		$this->profileDBIn();
		$res = $db->select(
			'redirect',
			array(
				'rd_from',
				'rd_namespace',
				'rd_fragment',
				'rd_interwiki',
				'rd_title'
			), array( 'rd_from' => array_keys( $this->mPendingRedirectIDs ) ),
			__METHOD__
		);
		$this->profileDBOut();
		foreach ( $res as $row ) {
			$rdfrom = intval( $row->rd_from );
			$from = $this->mPendingRedirectIDs[$rdfrom]->getPrefixedText();
			$to = Title::makeTitle(
				$row->rd_namespace,
				$row->rd_title,
				$row->rd_fragment,
				$row->rd_interwiki
			);
			unset( $this->mPendingRedirectIDs[$rdfrom] );
			if ( !$to->isExternal() && !isset( $this->mAllPages[$row->rd_namespace][$row->rd_title] ) ) {
				$lb->add( $row->rd_namespace, $row->rd_title );
			}
			$this->mRedirectTitles[$from] = $to;
		}

		if ( $this->mPendingRedirectIDs ) {
			// We found pages that aren't in the redirect table
			// Add them
			foreach ( $this->mPendingRedirectIDs as $id => $title ) {
				$page = WikiPage::factory( $title );
				$rt = $page->insertRedirect();
				if ( !$rt ) {
					// What the hell. Let's just ignore this
					continue;
				}
				$lb->addObj( $rt );
				$this->mRedirectTitles[$title->getPrefixedText()] = $rt;
				unset( $this->mPendingRedirectIDs[$id] );
			}
		}

		return $lb;
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
	 * @param $params
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
	 * @param array $titles of Title objects or strings
	 * @return LinkBatch
	 */
	private function processTitlesArray( $titles ) {
		$usernames = array();
		$linkBatch = new LinkBatch();

		foreach ( $titles as $title ) {
			if ( is_string( $title ) ) {
				$titleObj = Title::newFromText( $title, $this->mDefaultNamespace );
			} else {
				$titleObj = $title;
			}
			if ( !$titleObj ) {
				// Handle invalid titles gracefully
				$this->mAllPages[0][$title] = $this->mFakePageId;
				$this->mInvalidTitles[$this->mFakePageId] = $title;
				$this->mFakePageId--;
				continue; // There's nothing else we can do
			}
			$unconvertedTitle = $titleObj->getPrefixedText();
			$titleWasConverted = false;
			if ( $titleObj->isExternal() ) {
				// This title is an interwiki link.
				$this->mInterwikiTitles[$unconvertedTitle] = $titleObj->getInterwiki();
			} else {
				// Variants checking
				global $wgContLang;
				if ( $this->mConvertTitles &&
					count( $wgContLang->getVariants() ) > 1 &&
					!$titleObj->exists()
				) {
					// Language::findVariantLink will modify titleText and titleObj into
					// the canonical variant if possible
					$titleText = is_string( $title ) ? $title : $titleObj->getPrefixedText();
					$wgContLang->findVariantLink( $titleText, $titleObj );
					$titleWasConverted = $unconvertedTitle !== $titleObj->getPrefixedText();
				}

				if ( $titleObj->getNamespace() < 0 ) {
					// Handle Special and Media pages
					$titleObj = $titleObj->fixSpecialName();
					$this->mSpecialTitles[$this->mFakePageId] = $titleObj;
					$this->mFakePageId--;
				} else {
					// Regular page
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
				if ( is_string( $title ) && $title !== $unconvertedTitle ) {
					$this->mNormalizedTitles[$title] = $unconvertedTitle;
				}
			} elseif ( is_string( $title ) && $title !== $titleObj->getPrefixedText() ) {
				$this->mNormalizedTitles[$title] = $titleObj->getPrefixedText();
			}

			// Need gender information
			if ( MWNamespace::hasGenderDistinction( $titleObj->getNamespace() ) ) {
				$usernames[] = $titleObj->getText();
			}
		}
		// Get gender information
		$genderCache = GenderCache::singleton();
		$genderCache->doQuery( $usernames, __METHOD__ );

		return $linkBatch;
	}

	/**
	 * Get the database connection (read-only)
	 * @return DatabaseBase
	 */
	protected function getDB() {
		return $this->mDbSource->getDB();
	}

	/**
	 * Returns the input array of integers with all values < 0 removed
	 *
	 * @param $array array
	 * @return array
	 */
	private static function getPositiveIntegers( $array ) {
		// bug 25734 API: possible issue with revids validation
		// It seems with a load of revision rows, MySQL gets upset
		// Remove any < 0 integers, as they can't be valid
		foreach ( $array as $i => $int ) {
			if ( $int < 0 ) {
				unset( $array[$i] );
			}
		}

		return $array;
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = array(
			'titles' => array(
				ApiBase::PARAM_ISMULTI => true
			),
			'pageids' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true
			),
			'revids' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_ISMULTI => true
			),
			'redirects' => false,
			'converttitles' => false,
		);
		if ( $this->mAllowGenerator ) {
			if ( $flags & ApiBase::GET_VALUES_FOR_HELP ) {
				$result['generator'] = array(
					ApiBase::PARAM_TYPE => $this->getGenerators()
				);
			} else {
				$result['generator'] = null;
			}
		}

		return $result;
	}

	private static $generators = null;

	/**
	 * Get an array of all available generators
	 * @return array
	 */
	private function getGenerators() {
		if ( self::$generators === null ) {
			$query = $this->mDbSource;
			if ( !( $query instanceof ApiQuery ) ) {
				// If the parent container of this pageset is not ApiQuery,
				// we must create it to get module manager
				$query = $this->getMain()->getModuleManager()->getModule( 'query' );
			}
			$gens = array();
			$mgr = $query->getModuleManager();
			foreach ( $mgr->getNamesWithClasses() as $name => $class ) {
				if ( is_subclass_of( $class, 'ApiQueryGeneratorBase' ) ) {
					$gens[] = $name;
				}
			}
			sort( $gens );
			self::$generators = $gens;
		}

		return self::$generators;
	}

	public function getParamDescription() {
		return array(
			'titles' => 'A list of titles to work on',
			'pageids' => 'A list of page IDs to work on',
			'revids' => 'A list of revision IDs to work on',
			'generator' => array(
				'Get the list of pages to work on by executing the specified query module.',
				'NOTE: generator parameter names must be prefixed with a \'g\', see examples'
			),
			'redirects' => 'Automatically resolve redirects',
			'converttitles' => array(
				'Convert titles to other variants if necessary. Only works if ' .
					'the wiki\'s content language supports variant conversion.',
				'Languages that support variant conversion include ' .
					implode( ', ', LanguageConverter::$languagesWithVariants )
			),
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array(
				'code' => 'multisource',
				'info' => "Cannot use 'pageids' at the same time as 'dataSource'"
			),
			array(
				'code' => 'multisource',
				'info' => "Cannot use 'revids' at the same time as 'dataSource'"
			),
			array(
				'code' => 'badgenerator',
				'info' => 'Module $generatorName cannot be used as a generator'
			),
		) );
	}
}
