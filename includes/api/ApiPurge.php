<?php

/**
 * API for MediaWiki 1.14+
 *
 * Created on Sep 2, 2008
 *
 * Copyright © 2008 Chad Horohoe
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
 * API interface for page purging
 * @ingroup API
 */
class ApiPurge extends ApiBase {

	private $mPageSet;

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
	 * Purges the cache of a page
	 */
	public function execute() {
		$params = $this->extractRequestParams();

		$forceLinkUpdate = $params['forcelinkupdate'];
		$forceRecursiveLinkUpdate = $params['forcerecursivelinkupdate'];
		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = array();
		self::addValues( $result, $pageSet->getInvalidTitles(), 'invalid', 'title' );
		self::addValues( $result, $pageSet->getSpecialTitles(), 'special', 'title' );
		self::addValues( $result, $pageSet->getMissingPageIDs(), 'missing', 'pageid' );
		self::addValues( $result, $pageSet->getMissingRevisionIDs(), 'missing', 'revid' );
		self::addValues( $result, $pageSet->getMissingTitles(), 'missing' );
		self::addValues( $result, $pageSet->getInterwikiTitlesAsResult() );

		foreach ( $pageSet->getGoodTitles() as $title ) {
			$r = array();
			ApiQueryBase::addTitleInfo( $r, $title );
			$page = WikiPage::factory( $title );
			$page->doPurge(); // Directly purge and skip the UI part of purge().
			$r['purged'] = '';

			if ( $forceLinkUpdate || $forceRecursiveLinkUpdate ) {
				if ( !$this->getUser()->pingLimiter( 'linkpurge' ) ) {
					global $wgEnableParserCache;

					$popts = $page->makeParserOptions( 'canonical' );

					# Parse content; note that HTML generation is only needed if we want to cache the result.
					$content = $page->getContent( Revision::RAW );
					$p_result = $content->getParserOutput( $title, $page->getLatest(), $popts, $wgEnableParserCache );

					# Update the links tables
					$updates = $content->getSecondaryDataUpdates(
						$title, null, $forceRecursiveLinkUpdate, $p_result );
					DataUpdate::runUpdates( $updates );

					$r['linkupdate'] = '';

					if ( $wgEnableParserCache ) {
						$pcache = ParserCache::singleton();
						$pcache->save( $p_result, $page, $popts );
					}
				} else {
					$error = $this->parseMsg( array( 'actionthrottledtext' ) );
					$this->setWarning( $error['info'] );
					$forceLinkUpdate = false;
				}
			}

			$result[] = $r;
		}
		$apiResult = $this->getResult();
		$apiResult->setIndexedTagName( $result, 'page' );
		$apiResult->addValue( null, $this->getModuleName(), $result );

		$values = $pageSet->getNormalizedTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'normalized', $values );
		}
		$values = $pageSet->getConvertedTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'converted', $values );
		}
		$values = $pageSet->getRedirectTitlesAsResult( $apiResult );
		if ( $values ) {
			$apiResult->addValue( null, 'redirects', $values );
		}
	}

	/**
	 * Get a cached instance of an ApiPageSet object
	 * @return ApiPageSet
	 */
	private function getPageSet() {
		if ( !isset( $this->mPageSet ) ) {
			$this->mPageSet = new ApiPageSet( $this );
		}
		return $this->mPageSet;
	}

	public function isWriteMode() {
		return true;
	}

	public function mustBePosted() {
		// Anonymous users are not allowed a non-POST request
		return !$this->getUser()->isAllowed( 'purge' );
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = array(
			'forcelinkupdate' => false,
			'forcerecursivelinkupdate' => false
		);
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}
		return $result;
	}

	public function getParamDescription() {
		return $this->getPageSet()->getFinalParamDescription()
			+ array(
				'forcelinkupdate' => 'Update the links tables',
				'forcerecursivelinkupdate' => 'Update the links table, and update ' .
					'the links tables for any page that uses this page as a template',
			);
	}

	public function getResultProperties() {
		return array(
			ApiBase::PROP_LIST => true,
			'' => array(
				'ns' => array(
					ApiBase::PROP_TYPE => 'namespace',
					ApiBase::PROP_NULLABLE => true
				),
				'title' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'pageid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'revid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'invalid' => 'boolean',
				'special' => 'boolean',
				'missing' => 'boolean',
				'purged' => 'boolean',
				'linkupdate' => 'boolean',
				'iw' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
			)
		);
	}

	public function getDescription() {
		return array( 'Purge the cache for the given titles.',
			'Requires a POST request if the user is not logged in.'
		);
	}

	public function getPossibleErrors() {
		return array_merge(
			parent::getPossibleErrors(),
			$this->getPageSet()->getFinalPossibleErrors()
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=purge&titles=Main_Page|API' => 'Purge the "Main Page" and the "API" page',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Purge';
	}
}
