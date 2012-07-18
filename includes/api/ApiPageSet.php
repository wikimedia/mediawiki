<?php
/**
 *
 *
 * Created on Sep 24, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 */
class ApiPageSet extends ApiQueryBase {

	private $mAllPages; // [ns][dbkey] => page_id or negative when missing
	private $mTitles, $mGoodTitles, $mMissingTitles, $mInvalidTitles;
	private $mMissingPageIDs, $mRedirectTitles, $mSpecialTitles;
	private $mNormalizedTitles, $mInterwikiTitles;
	private $mResolveRedirects, $mPendingRedirectIDs;
	private $mConvertTitles, $mConvertedTitles;
	private $mGoodRevIDs, $mMissingRevIDs;
	private $mFakePageId;

	private $mRequestedPageFields;

	/**
	 * Constructor
	 * @param $query ApiBase
	 * @param $resolveRedirects bool Whether redirects should be resolved
	 * @param $convertTitles bool
	 */
	public function __construct( $query, $resolveRedirects = false, $convertTitles = false ) {
		parent::__construct( $query, 'query' );

		$this->mAllPages = array();
		$this->mTitles = array();
		$this->mGoodTitles = array();
		$this->mMissingTitles = array();
		$this->mInvalidTitles = array();
		$this->mMissingPageIDs = array();
		$this->mRedirectTitles = array();
		$this->mNormalizedTitles = array();
		$this->mInterwikiTitles = array();
		$this->mGoodRevIDs = array();
		$this->mMissingRevIDs = array();
		$this->mSpecialTitles = array();

		$this->mRequestedPageFields = array();
		$this->mResolveRedirects = $resolveRedirects;
		if ( $resolveRedirects ) {
			$this->mPendingRedirectIDs = array();
		}

		$this->mConvertTitles = $convertTitles;
		$this->mConvertedTitles = array();

		$this->mFakePageId = - 1;
	}

	/**
	 * Check whether this PageSet is resolving redirects
	 * @return bool
	 */
	public function isResolvingRedirects() {
		return $this->mResolveRedirects;
	}

	/**
	 * Request an additional field from the page table. Must be called
	 * before execute()
	 * @param $fieldName string Field name
	 */
	public function requestField( $fieldName ) {
		$this->mRequestedPageFields[$fieldName] = null;
	}

	/**
	 * Get the value of a custom field previously requested through
	 * requestField()
	 * @param $fieldName string Field name
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
	 * @return array of Title objects
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
	 * @return array page_id (int) => Title (obj)
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
	 * @return array of Title objects
	 */
	public function getMissingTitles() {
		return $this->mMissingTitles;
	}

	/**
	 * Titles that were deemed invalid by Title::newFromText()
	 * The array's index will be unique and negative for each item
	 * @return array of strings (not Title objects)
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
	 * target.
	 * @return array prefixed_title (string) => Title object
	 */
	public function getRedirectTitles() {
		return $this->mRedirectTitles;
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
	 * Get a list of title conversions - maps a title to its converted
	 * version.
	 * @return array raw_prefixed_title (string) => prefixed_title (string)
	 */
	public function getConvertedTitles() {
		return $this->mConvertedTitles;
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
	 * Populate the PageSet from the request parameters.
	 */
	public function execute() {
		$this->profileIn();
		$params = $this->extractRequestParams();

		// Only one of the titles/pageids/revids is allowed at the same time
		$dataSource = null;
		if ( isset( $params['titles'] ) ) {
			$dataSource = 'titles';
		}
		if ( isset( $params['pageids'] ) ) {
			if ( isset( $dataSource ) ) {
				$this->dieUsage( "Cannot use 'pageids' at the same time as '$dataSource'", 'multisource' );
			}
			$dataSource = 'pageids';
		}
		if ( isset( $params['revids'] ) ) {
			if ( isset( $dataSource ) ) {
				$this->dieUsage( "Cannot use 'revids' at the same time as '$dataSource'", 'multisource' );
			}
			$dataSource = 'revids';
		}

		switch ( $dataSource ) {
			case 'titles':
				$this->initFromTitles( $params['titles'] );
				break;
			case 'pageids':
				$this->initFromPageIds( $params['pageids'] );
				break;
			case 'revids':
				if ( $this->mResolveRedirects ) {
					$this->setWarning( 'Redirect resolution cannot be used together with the revids= parameter. ' .
					'Any redirects the revids= point to have not been resolved.' );
				}
				$this->mResolveRedirects = false;
				$this->initFromRevIDs( $params['revids'] );
				break;
			default:
				// Do nothing - some queries do not need any of the data sources.
				break;
		}
		$this->profileOut();
	}

	/**
	 * Populate this PageSet from a list of Titles
	 * @param $titles array of Title objects
	 */
	public function populateFromTitles( $titles ) {
		$this->profileIn();
		$this->initFromTitles( $titles );
		$this->profileOut();
	}

	/**
	 * Populate this PageSet from a list of page IDs
	 * @param $pageIDs array of page IDs
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
	 * @param $revIDs array of revision IDs
	 */
	public function populateFromRevisionIDs( $revIDs ) {
		$this->profileIn();
		$this->initFromRevIDs( $revIDs );
		$this->profileOut();
	}

	/**
	 * Extract all requested fields from the row received from the database
	 * @param $row Result row
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
			$fieldValues[$pageId] = $row-> $fieldName;
		}
	}

	/**
	 * Resolve redirects, if applicable
	 */
	public function finishPageSetGeneration() {
		$this->profileIn();
		$this->resolvePendingRedirects();
		$this->profileOut();
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
	 * @param $titles array of Title objects or strings
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
	 * @param $pageids array of page IDs
	 */
	private function initFromPageIds( $pageids ) {
		if ( !count( $pageids ) ) {
			return;
		}

		$pageids = array_map( 'intval', $pageids ); // paranoia
		$remaining = array_flip( $pageids );

		$pageids = self::getPositiveIntegers( $pageids );

		$res = null;
		if ( count( $pageids ) ) {
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

		$this->initFromQueryResult( $res, $remaining, false );	// process PageIDs

		// Resolve any found redirects
		$this->resolvePendingRedirects();
	}

	/**
	 * Iterate through the result of the query on 'page' table,
	 * and for each row create and store title object and save any extra fields requested.
	 * @param $res ResultWrapper DB Query result
	 * @param $remaining array of either pageID or ns/title elements (optional).
	 *        If given, any missing items will go to $mMissingPageIDs and $mMissingTitles
	 * @param $processTitles bool Must be provided together with $remaining.
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
				if( MWNamespace::hasGenderDistinction( $row->page_namespace ) ) {
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
						if( MWNamespace::hasGenderDistinction( $ns ) ) {
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
	 * @param $revids array of revision IDs
	 */
	private function initFromRevIDs( $revids ) {
		if ( !count( $revids ) ) {
			return;
		}

		$revids = array_map( 'intval', $revids ); // paranoia
		$db = $this->getDB();
		$pageids = array();
		$remaining = array_flip( $revids );

		$revids = self::getPositiveIntegers( $revids );

		if ( count( $revids ) ) {
			$tables = array( 'revision', 'page' );
			$fields = array( 'rev_id', 'rev_page' );
			$where = array( 'rev_id' => $revids, 'rev_page = page_id' );

			// Get pageIDs data from the `page` table
			$this->profileDBIn();
			$res = $db->select( $tables, $fields, $where,  __METHOD__ );
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
			$to = Title::makeTitle( $row->rd_namespace, $row->rd_title, $row->rd_fragment, $row->rd_interwiki );
			unset( $this->mPendingRedirectIDs[$rdfrom] );
			if ( !isset( $this->mAllPages[$row->rd_namespace][$row->rd_title] ) ) {
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
	 * Given an array of title strings, convert them into Title objects.
	 * Alternativelly, an array of Title objects may be given.
	 * This method validates access rights for the title,
	 * and appends normalization values to the output.
	 *
	 * @param $titles array of Title objects or strings
	 * @return LinkBatch
	 */
	private function processTitlesArray( $titles ) {
		$genderCache = GenderCache::singleton();
		$genderCache->doTitlesArray( $titles, __METHOD__ );

		$linkBatch = new LinkBatch();

		foreach ( $titles as $title ) {
			$titleObj = is_string( $title ) ? Title::newFromText( $title ) : $title;
			if ( !$titleObj ) {
				// Handle invalid titles gracefully
				$this->mAllpages[0][$title] = $this->mFakePageId;
				$this->mInvalidTitles[$this->mFakePageId] = $title;
				$this->mFakePageId--;
				continue; // There's nothing else we can do
			}
			$unconvertedTitle = $titleObj->getPrefixedText();
			$titleWasConverted = false;
			$iw = $titleObj->getInterwiki();
			if ( strval( $iw ) !== '' ) {
				// This title is an interwiki link.
				$this->mInterwikiTitles[$titleObj->getPrefixedText()] = $iw;
			} else {
				// Variants checking
				global $wgContLang;
				if ( $this->mConvertTitles &&
						count( $wgContLang->getVariants() ) > 1  &&
						!$titleObj->exists() ) {
					// Language::findVariantLink will modify titleObj into
					// the canonical variant if possible
					$wgContLang->findVariantLink( $title, $titleObj );
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
				$this->mConvertedTitles[$title] = $titleObj->getPrefixedText();
			} elseif ( is_string( $title ) && $title !== $titleObj->getPrefixedText() ) {
				$this->mNormalizedTitles[$title] = $titleObj->getPrefixedText();
			}
		}

		return $linkBatch;
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
		foreach( $array as $i => $int ) {
			if ( $int < 0 ) {
				unset( $array[$i] );
			}
		}

		return $array;
	}

	public function getAllowedParams() {
		return array(
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
			)
		);
	}

	public function getParamDescription() {
		return array(
			'titles' => 'A list of titles to work on',
			'pageids' => 'A list of page IDs to work on',
			'revids' => 'A list of revision IDs to work on'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'multisource', 'info' => "Cannot use 'pageids' at the same time as 'dataSource'" ),
			array( 'code' => 'multisource', 'info' => "Cannot use 'revids' at the same time as 'dataSource'" ),
		) );
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
