<?php
/**
 * Displays information about a page.
 *
 * Copyright © 2011 Alexandre Emsenhuber
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
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

class InfoAction extends FormlessAction {
	/**
	 * Returns the name of the action this object responds to.
	 *
	 * @return string lowercase
	 */
	public function getName() {
		return 'info';
	}

	/**
	 * Whether this action can still be executed by a blocked user.
	 *
	 * @return bool
	 */
	public function requiresUnblock() {
		return false;
	}

	/**
	 * Whether this action requires the wiki not to be locked.
	 *
	 * @return bool
	 */
	public function requiresWrite() {
		return false;
	}

	/**
	 * Shows page information on GET request.
	 *
	 * @return string Page information that will be added to the output
	 */
	public function onView() {
		global $wgDisableCounters, $wgRCMaxAge, $wgRestrictionTypes;

		$lang = $this->getLanguage();
		$title = $this->getTitle();

		$article = new Article( $title );
		$id = $title->getArticleID();

		// Get page information that would be too "expensive" to retrieve by normal means
		$userCanViewUnwatchedPages = $this->getUser()->isAllowed( 'unwatchedpages' );
		$pageInfo = self::pageCountInfo( $title, $userCanViewUnwatchedPages, $wgDisableCounters );

		// Get page properties
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			'page_props',
			array( 'pp_propname', 'pp_value' ),
			array( 'pp_page' => $id ),
			__METHOD__
		);

		$pageProperties = array();
		foreach ( $result as $row ) {
			$pageProperties[$row->pp_propname] = $row->pp_value;
		}

		$content = '';
		$table = '';

		// Basic information
		$this->addHeader( $content, $this->msg( 'pageinfo-header-basic' ) );

		// Display title
		$displayTitle = $title->getPrefixedText();
		if ( !empty( $pageProperties['displaytitle'] ) ) {
			$displayTitle = $pageProperties['displaytitle'];
		}

		$this->addRow( $table,
			$this->msg( 'pageinfo-display-title' ), $displayTitle );

		// Default sort key
		$sortKey = $title->getCategorySortKey();
		if ( !empty( $pageProperties['defaultsort'] ) ) {
			$sortKey = $pageProperties['defaultsort'];
		}

		$this->addRow( $table,
			$this->msg( 'pageinfo-default-sort' ), $sortKey );

		// Page length (in bytes)
		$this->addRow( $table,
			$this->msg( 'pageinfo-length' ), $lang->formatNum( $title->getLength() ) );

		// Page ID
		$this->addRow( $table,
			$this->msg( 'pageinfo-article-id' ), $lang->formatNum( $id ) );

		// Search engine status
		$pOutput = new ParserOutput();
		if ( isset( $pageProperties['noindex'] ) ) {
			$pOutput->setIndexPolicy( 'noindex' );
		}

		// Use robot policy logic
		$policy = $article->getRobotPolicy( 'view', $pOutput );
		$this->addRow( $table,
			'Search engine status', "Marked as '" . $policy['index'] . "'"
		);

		if ( !$wgDisableCounters ) {
			// Number of views
			$this->addRow( $table,
				$this->msg( 'pageinfo-views' ), $lang->formatNum( $pageInfo['views'] )
			);
		}

		if ( $userCanViewUnwatchedPages ) {
			// Number of page watchers
			$this->addRow( $table,
				$this->msg( 'pageinfo-watchers' ), $lang->formatNum( $pageInfo['watchers'] ) );
		}

		// Redirects to this page
		$whatLinksHere = SpecialPage::getTitleFor( 'WhatLinksHere', $title->getPrefixedText() );
		$this->addRow( $table,
			$this->msg( 'pageinfo-redirects-name' ),
			$this->msg( 'pageinfo-redirects-value',
				$lang->formatNum( count( $title->getRedirectsHere() ) ),
				Linker::link(
					$whatLinksHere,
					$this->msg( 'pageinfo-list-link' ),
					array(),
					array( 'hidelinks' => 1, 'hidetrans' => 1 )
				)
			)->text()
		);

		// Subpages of this page
		$prefixIndex = SpecialPage::getTitleFor( 'PrefixIndex', $title->getPrefixedText() . '/' );
		$this->addRow( $table,
			$this->msg( 'pageinfo-subpages-name' ),
			$this->msg( 'pageinfo-subpages-value',
				$lang->formatNum( $pageInfo['subpages']['total'] ),
				$pageInfo['subpages']['redirects'],
				$pageInfo['subpages']['nonredirects'],
				Linker::link( $prefixIndex, $this->msg( 'pageinfo-list-link' ) )
			)->text()
		);

		// Page protection
		$this->addTable( $content, $table );
		$this->addHeader( $content, $this->msg( 'pageinfo-header-restrictions' ) );

		// Page protection
		foreach ( $wgRestrictionTypes as $restrictionType ) {
			$protectionLevel = implode( ', ', $title->getRestrictions( $restrictionType ) );
			if ( $protectionLevel == '' ) {
				// Allow all users
				$message = $this->msg( "protect-default" );
			} else {
				// Administrators only
				$message = $this->msg( "protect-level-$protectionLevel" );
				if ( !$message->exists() ) {
					// Require "$1" permission
					$message = $this->msg( "protect-fallback", $protectionLevel );
				}
			}

			$this->addRow( $table,
				$this->msg( 'pageinfo-restriction', $restrictionType ), $message
			);
		}

		// Edit history
		$this->addTable( $content, $table );
		$this->addHeader( $content, $this->msg( 'pageinfo-header-edits' ) );

		// Page creator
		$this->addRow( $table,
			$this->msg( 'pageinfo-firstuser' ), $pageInfo['firstuser']
		);

		// Date of page creation
		$this->addRow( $table,
			$this->msg( 'pageinfo-firsttime' ), $lang->timeanddate( $pageInfo['firsttime'] )
		);

		// Latest editor
		$this->addRow( $table,
			$this->msg( 'pageinfo-lastuser' ), $pageInfo['lastuser']
		);

		// Date of latest edit
		$this->addRow( $table,
			$this->msg( 'pageinfo-lasttime' ), $lang->timeanddate( $pageInfo['lasttime'] )
		);

		// Total number of edits
		$this->addRow( $table,
			$this->msg( 'pageinfo-edits' ), $lang->formatNum( $pageInfo['edits'] )
		);

		// Total number of distinct authors
		$this->addRow( $table,
			$this->msg( 'pageinfo-authors' ), $lang->formatNum( $pageInfo['authors'] )
		);

		// Recent number of edits (within past 30 days)
		$this->addRow( $table,
			$this->msg( 'pageinfo-recent-edits', $lang->formatDuration( $wgRCMaxAge ) ),
			$lang->formatNum( $pageInfo['recent_edits'] )
		);

		// Recent number of distinct authors
		$this->addRow( $table,
			$this->msg( 'pageinfo-recent-authors' ), $lang->formatNum( $pageInfo['recent_authors'] )
		);

		// Page properties
		$this->addTable( $content, $table );
		$this->addHeader( $content, $this->msg( 'pageinfo-header-properties' ) );

		// Array of MagicWord objects
		$magicWords = MagicWord::getDoubleUnderscoreArray();

		// Array of magic word IDs
		$wordIDs = $magicWords->names;

		// Array of IDs => localized magic words
		$localizedWords = $lang->getMagicWords();

		$listItems = array();
		foreach ( $pageProperties as $property => $value ) {
			if ( in_array( $property, $wordIDs ) ) {
				$listItems[] = Html::element( 'li', array(), $localizedWords[$property][1] );
			}
		}

		$localizedList = Html::rawElement( 'ul', array(), implode( '', $listItems ) );

		// Magic words
		$this->addRow( $table,
			$this->msg( 'pageinfo-magic-words' ), $localizedList
		);

		// Hide "This page is a member of # hidden categories explanation
		$content .= Html::element( 'style', array(),
			'.mw-hiddenCategoriesExplanation { display: none; }' );

		// Hidden categories
		$hiddenCategories = $article->getHiddenCategories();
		$this->addRow( $table,
			$this->msg( 'pageinfo-hidden-categories' ),
			Linker::formatHiddenCategories( $hiddenCategories )
		);

		// Hide "Templates used on this page:" explanation
		$content .= Html::element( 'style', array(),
			'.mw-templatesUsedExplanation { display: none; }' );

		// Transcluded templates
		$transcludedTemplates = $title->getTemplateLinksFrom();
		$this->addRow( $table,
			$this->msg( 'pageinfo-templates', count( $transcludedTemplates ) ),
			Linker::formatTemplates( $transcludedTemplates )
		);

		$this->addTable( $content, $table );
		return $content;
	}

	/**
	 * Returns page information that would be too "expensive" to retrieve by normal means.
	 *
	 * @param $title Title object
	 * @param $canViewUnwatched bool
	 * @param $disableCounter bool
	 * @return array
	 */
	public static function pageCountInfo( $title, $canViewUnwatched, $disableCounter ) {
		global $wgRCMaxAge;

		wfProfileIn( __METHOD__ );
		$id = $title->getArticleID();

		$dbr = wfGetDB( DB_SLAVE );
		$result = array();

		if ( !$disableCounter ) {
			// Number of views
			$views = (int) $dbr->selectField(
				'page',
				'page_counter',
				array( 'page_id' => $id ),
				__METHOD__
			);
			$result['views'] = $views;
		}

		if ( $canViewUnwatched ) {
			// Number of page watchers
			$watchers = (int) $dbr->selectField(
				'watchlist',
				'COUNT(*)',
				array(
					'wl_namespace' => $title->getNamespace(),
					'wl_title'     => $title->getDBkey(),
				),
				__METHOD__
			);
			$result['watchers'] = $watchers;
		}

		// Total number of edits
		$edits = (int) $dbr->selectField(
			'revision',
			'COUNT(rev_page)',
			array( 'rev_page' => $id ),
			__METHOD__
		);
		$result['edits'] = $edits;

		// Total number of distinct authors
		$authors = (int) $dbr->selectField(
			'revision',
			'COUNT(DISTINCT rev_user_text)',
			array( 'rev_page' => $id ),
			__METHOD__
		);
		$result['authors'] = $authors;

		// "Recent" threshold defined by $wgRCMaxAge
		$threshold = date( 'YmdHis', time() - $wgRCMaxAge );

		// Recent number of edits
		$edits = (int) $dbr->selectField(
			'revision',
			'COUNT(rev_page)',
			array(
				'rev_page' => $id ,
				"rev_timestamp >= $threshold"
			),
			__METHOD__
		);
		$result['recent_edits'] = $edits;

		// Recent number of distinct authors
		$authors = (int) $dbr->selectField(
			'revision',
			'COUNT(DISTINCT rev_user_text)',
			array(
				'rev_page' => $id,
				"rev_timestamp >= $threshold"
			),
			__METHOD__
		);
		$result['recent_authors'] = $authors;

		$conds = array( 'page_namespace' => $title->getNamespace(), 'page_is_redirect' => 1 );
		$conds[] = 'page_title ' . $dbr->buildLike( $title->getDBkey() . '/', $dbr->anyString() );

		// Subpages of this page (redirects)
		$result['subpages']['redirects'] = (int) $dbr->selectField(
			'page',
			'COUNT(page_id)',
			$conds,
			__METHOD__ );

		// Subpages of this page (non-redirects)
		$conds['page_is_redirect'] = 0;
		$result['subpages']['nonredirects'] = (int) $dbr->selectField(
			'page',
			'COUNT(page_id)',
			$conds,
			__METHOD__
		);

		// Subpages of this page (total)
		$result['subpages']['total'] = $result['subpages']['redirects']
			+ $result['subpages']['nonredirects'];

		// Latest editor + date of latest edit
		$options = array( 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 1 );
		$row = $dbr->fetchRow( $dbr->select(
			'revision',
			array( 'rev_user_text', 'rev_timestamp' ),
			array( 'rev_page' => $id ),
			__METHOD__,
			$options
		) );

		$result['firstuser'] = $row['rev_user_text'];
		$result['firsttime'] = $row['rev_timestamp'];

		// Latest editor + date of latest edit
		$options['ORDER BY'] = 'rev_timestamp DESC';
		$row = $dbr->fetchRow( $dbr->select(
			'revision',
			array( 'rev_user_text', 'rev_timestamp' ),
			array( 'rev_page' => $id ),
			__METHOD__,
			$options
		) );

		$result['lastuser'] = $row['rev_user_text'];
		$result['lasttime'] = $row['rev_timestamp'];

		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * Adds a header to the content that will be added to the output.
	 *
	 * @param &$content string The content that will be added to the output
	 * @param $header string The value of the header
	 */
	protected function addHeader( &$content, $header ) {
		$content .= Html::element( 'h2', array(), $header );
	}

	/**
	 * Adds a row to a table that will be added to the content.
	 *
	 * @param &$table string The table that will be added to the content
	 * @param $name string The name of the row
	 * @param $value string The value of the row
	 */
	protected function addRow( &$table, $name, $value ) {
		$table .= Html::rawElement( 'tr', array(),
			Html::rawElement( 'td', array(), $name ) .
			Html::rawElement( 'td', array(), $value )
		);
	}

	/**
	 * Adds a table to the content that will be added to the output.
	 *
	 * @param &$content string The content that will be added to the output
	 * @param &$table string The table
	 */
	protected function addTable( &$content, &$table ) {
		$content .= Html::rawElement( 'table', array( 'class' => 'wikitable mw-page-info' ),
			$table );
		$table = '';
	}

	/**
	 * Returns the description that goes below the <h1> tag.
	 *
	 * @return string
	 */
	protected function getDescription() {
		return '';
	}

	/**
	 * Returns the name that goes in the <h1> page title.
	 *
	 * @return string
	 */
	protected function getPageTitle() {
		return $this->msg( 'pageinfo-title', $this->getTitle()->getPrefixedText() )->text();
	}
}
