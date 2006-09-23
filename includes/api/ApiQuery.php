<?php


/*
 * Created on Sep 7, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <FirstnameLastname@gmail.com>
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ("ApiBase.php");
}

class ApiQuery extends ApiBase {

	var $mMetaModuleNames, $mPropModuleNames, $mListModuleNames;

	private $mQueryMetaModules = array (
//		'siteinfo' => 'ApiQuerySiteinfo',
//		'userinfo' => 'ApiQueryUserinfo'
	);
	private $mQueryPropModules = array (
//		'info' => 'ApiQueryInfo',
//		'categories' => 'ApiQueryCategories',
//		'imageinfo' => 'ApiQueryImageinfo',
//		'langlinks' => 'ApiQueryLanglinks',
//		'links' => 'ApiQueryLinks',
//		'templates' => 'ApiQueryTemplates',
//		'revisions' => 'ApiQueryRevisions',

		// Should be removed
		'content' => 'ApiQueryContent'
	);
	private $mQueryListModules = array (
//		'allpages' => 'ApiQueryAllpages',
//		'backlinks' => 'ApiQueryBacklinks',
//		'categorymembers' => 'ApiQueryCategorymembers',
//		'embeddedin' => 'ApiQueryEmbeddedin',
//		'imagelinks' => 'ApiQueryImagelinks',
//		'logevents' => 'ApiQueryLogevents',
//		'recentchanges' => 'ApiQueryRecentchanges',
//		'usercontribs' => 'ApiQueryUsercontribs',
//		'users' => 'ApiQueryUsers',
//		'watchlist' => 'ApiQueryWatchlist',
	);

	private $mSlaveDB = null;

	public function __construct($main, $action) {
		parent :: __construct($main);
		$this->mMetaModuleNames = array_keys($this->mQueryMetaModules);
		$this->mPropModuleNames = array_keys($this->mQueryPropModules);
		$this->mListModuleNames = array_keys($this->mQueryListModules);
		
		$this->mAllowedGenerators = array_merge( $this->mListModuleNames, $this->mPropModuleNames);
	}

	public function GetDB() {
		if (!isset ($this->mSlaveDB))
			$this->mSlaveDB = & wfGetDB(DB_SLAVE);
		return $this->mSlaveDB;
	}

	public function Execute() {
		$meta = $prop = $list = $generator = $titles = $pageids = $revids = null;
		$redirects = null;
		extract($this->ExtractRequestParams());

		//
		// Only one of the titles/pageids/revids is allowed at the same time
		//
		$dataSource = null;
		if (isset($titles))
			$dataSource = 'titles';
		if (isset($pageids)) {
			if (isset($dataSource))
				$this->DieUsage("Cannot use 'pageids' at the same time as '$dataSource'", 'multisource');
			$dataSource = 'pageids';
		}
		if (isset($revids)) {
			if (isset($dataSource))
				$this->DieUsage("Cannot use 'revids' at the same time as '$dataSource'", 'multisource');
			$dataSource = 'revids';
		}
		
		//
		// Normalize titles
		//
		if ($dataSource === 'titles') {
			$linkBatch = new LinkBatch;
			foreach ( $titles as &$titleString ) {
				$titleObj = &Title::newFromText( $titleString );

				// Validation
				if (!$titleObj)
					$this->dieUsage( "bad title $titleString", 'pi_invalidtitle' );
				if ($titleObj->getNamespace() < 0)
					$this->dieUsage( "No support for special page $titleString has been implemented", 'pi_unsupportednamespace' );
				if (!$titleObj->userCanRead())
					$this->dieUsage( "No read permission for $titleString", 'pi_titleaccessdenied' );

                $linkBatch->addObj( $titleObj );

				// Make sure we remember the original title that was given to us
				// This way the caller can correlate new titles with the originally requested, i.e. namespace is localized or capitalization
				if( $titleString !== $titleObj->getPrefixedText() ) {
					$this->GetResult()->AddMessage('query', 'normalized', array($titleString => $titleObj->getPrefixedText()));
				}
			}
		}
	}

	protected function GetAllowedParams() {
		return array (
			'meta' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_CHOICES => $this->mMetaModuleNames
			),
			'prop' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_CHOICES => $this->mPropModuleNames
			),
			'list' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_CHOICES => $this->mListModuleNames
			),
			'generator' => array (
				GN_ENUM_CHOICES => $this->mAllowedGenerators
			),
			'titles' => array (
				GN_ENUM_ISMULTI => true
			),
			'pageids' => array (
				GN_ENUM_TYPE => 'integer',
				GN_ENUM_ISMULTI => true
			),
			'revids' => array (
				GN_ENUM_TYPE => 'integer',
				GN_ENUM_ISMULTI => true
			)
		);
	}

	protected function GetParamDescription() {
		return array (
			'meta' => 'Which meta data to get about the site',
			'prop' => 'Which properties to get for the titles/revisions/pageids',
			'list' => 'Which lists to get',
			'generator' => 'Use the output of a list as the input for other prop/list/meta items',
			'titles' => 'A list of titles to work on',
			'pageids' => 'A list of page IDs to work on',
			'revids' => 'A list of revision IDs to work on'
		);
	}

	protected function GetDescription() {
		return array(
				'Query API module allows applications to get needed pieces of data from the MediaWiki databases,',
				'and is loosely based on the Query API interface currently available on all MediaWiki servers.',
				'All data modifications will first have to use query to acquire a token to prevent abuse from malicious sites.');
	}

	protected function GetExamples() {
		return array (
			'api.php ? action=query & what=content & titles=ArticleA|ArticleB'
		);
	}
}
?>