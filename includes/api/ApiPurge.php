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

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	/**
	 * Purges the cache of a page
	 */
	public function execute() {
		$user = $this->getUser();
		$params = $this->extractRequestParams();
		if ( !$user->isAllowed( 'purge' ) && !$this->getMain()->isInternalMode() &&
				!$this->getRequest()->wasPosted() ) {
			$this->dieUsageMsg( array( 'mustbeposted', $this->getModuleName() ) );
		}

		$forceLinkUpdate = $params['forcelinkupdate'];
		$pageSet = new ApiPageSet( $this );
		$pageSet->execute();

		$result = array();
		foreach( $pageSet->getInvalidTitles() as $title ) {
			$r = array();
			$r['title'] = $title;
			$r['invalid'] = '';
			$result[] = $r;
		}
		foreach( $pageSet->getMissingPageIDs() as $p ) {
			$page = array();
			$page['pageid'] = $p;
			$page['missing'] = '';
			$result[] = $page;
		}
		foreach( $pageSet->getMissingRevisionIDs() as $r ) {
			$rev = array();
			$rev['revid'] = $r;
			$rev['missing'] = '';
			$result[] = $rev;
		}

		foreach ( $pageSet->getTitles() as $title ) {
			$r = array();

			ApiQueryBase::addTitleInfo( $r, $title );
			if ( !$title->exists() ) {
				$r['missing'] = '';
				$result[] = $r;
				continue;
			}

			$page = WikiPage::factory( $title );
			$page->doPurge(); // Directly purge and skip the UI part of purge().
			$r['purged'] = '';

			if( $forceLinkUpdate ) {
				if ( !$user->pingLimiter() ) {
					global $wgParser, $wgEnableParserCache;

					$popts = $page->makeParserOptions( 'canonical' );
					$p_result = $wgParser->parse( $page->getRawText(), $title, $popts,
						true, true, $page->getLatest() );

					# Update the links tables
					$updates = $p_result->getSecondaryDataUpdates( $title );
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
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$psModule = new ApiPageSet( $this );
		return $psModule->getAllowedParams() + array(
			'forcelinkupdate' => false,
		);
	}

	public function getParamDescription() {
		$psModule = new ApiPageSet( $this );
		return $psModule->getParamDescription() + array(
			'forcelinkupdate' => 'Update the links tables',
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
				'missing' => 'boolean',
				'purged' => 'boolean',
				'linkupdate' => 'boolean'
			)
		);
	}

	public function getDescription() {
		return array( 'Purge the cache for the given titles.',
			'Requires a POST request if the user is not logged in.'
		);
	}

	public function getPossibleErrors() {
		$psModule = new ApiPageSet( $this );
		return array_merge(
			parent::getPossibleErrors(),
			$psModule->getPossibleErrors()
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

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
