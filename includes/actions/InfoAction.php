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
		global $wgContLang, $wgDisableCounters, $wgRCMaxAge;

		$user = $this->getUser();
		$lang = $this->getLanguage();
		$title = $this->getTitle();
		$id = $title->getArticleID();

		// Get page information that would be too "expensive" to retrieve by normal means
		$userCanViewUnwatchedPages = $user->isAllowed( 'unwatchedpages' );
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

		// Header
		if ( !$this->msg( 'pageinfo-header' )->isDisabled() ) {
			$content .= $this->msg( 'pageinfo-header' )->parse();
		}

		// Credits
		if ( $title->exists() ) {
			$content .= Html::rawElement( 'div', array( 'id' => 'mw-credits' ), $this->getContributors() );
		}

		// Basic information
		$content .= $this->makeHeader( $this->msg( 'pageinfo-header-basic' )->plain() );

		// Display title
		$displayTitle = $title->getPrefixedText();
		if ( !empty( $pageProperties['displaytitle'] ) ) {
			$displayTitle = $pageProperties['displaytitle'];
		}

		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-display-title' )->escaped(), $displayTitle );

		// Default sort key
		$sortKey = $title->getCategorySortKey();
		if ( !empty( $pageProperties['defaultsort'] ) ) {
			$sortKey = $pageProperties['defaultsort'];
		}

		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-default-sort' )->escaped(), $sortKey );

		// Page length (in bytes)
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-length' )->escaped(), $lang->formatNum( $title->getLength() ) );

		// Page ID (number not localised, as it's a database ID.)
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-article-id' )->escaped(), $id );

		// Search engine status
		$pOutput = new ParserOutput();
		if ( isset( $pageProperties['noindex'] ) ) {
			$pOutput->setIndexPolicy( 'noindex' );
		}

		// Use robot policy logic
		$policy = $this->page->getRobotPolicy( 'view', $pOutput );
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-robot-policy' )->escaped(),
			$this->msg( "pageinfo-robot-${policy['index']}" )->escaped()
		);

		if ( !$wgDisableCounters ) {
			// Number of views
			$table = $this->addRow( $table,
				$this->msg( 'pageinfo-views' )->escaped(), $lang->formatNum( $pageInfo['views'] )
			);
		}

		if ( $userCanViewUnwatchedPages ) {
			// Number of page watchers
			$table = $this->addRow( $table,
				$this->msg( 'pageinfo-watchers' )->escaped(), $lang->formatNum( $pageInfo['watchers'] ) );
		}

		// Redirects to this page
		$whatLinksHere = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedText() );
		$table = $this->addRow( $table,
			Linker::link(
				$whatLinksHere,
				$this->msg( 'pageinfo-redirects-name' )->escaped(),
				array(),
				array( 'hidelinks' => 1, 'hidetrans' => 1 )
			),
			$this->msg( 'pageinfo-redirects-value' )
				->numParams( count( $title->getRedirectsHere() ) )->escaped()
		);

		// Subpages of this page
		$prefixIndex = SpecialPage::getTitleFor( 'Prefixindex', $title->getPrefixedText() . '/' );
		$table = $this->addRow( $table,
			Linker::link( $prefixIndex, $this->msg( 'pageinfo-subpages-name' )->escaped() ),
			$this->msg( 'pageinfo-subpages-value' )
				->numParams(
					$pageInfo['subpages']['total'],
					$pageInfo['subpages']['redirects'],
					$pageInfo['subpages']['nonredirects'] )->escaped()
		);

		// Page protection
		$content = $this->addTable( $content, $table );
		$content .= $this->makeHeader( $this->msg( 'pageinfo-header-restrictions' )->plain() );
		$table = '';

		// Page protection
		foreach ( $title->getRestrictionTypes() as $restrictionType ) {
			$protectionLevel = implode( ', ', $title->getRestrictions( $restrictionType ) );

			if ( $protectionLevel == '' ) {
				// Allow all users
				$message = $this->msg( 'protect-default' )->escaped();
			} else {
				// Administrators only
				$message = $this->msg( "protect-level-$protectionLevel" );
				if ( $message->isDisabled() ) {
					// Require "$1" permission
					$message = $this->msg( "protect-fallback", $protectionLevel )->parse();
				} else {
					$message = $message->escaped();
				}
			}

			$table = $this->addRow( $table,
				$this->msg( "restriction-$restrictionType" )->plain(),
				$message
			);
		}

		// Edit history
		$content = $this->addTable( $content, $table );
		$content .= $this->makeHeader( $this->msg( 'pageinfo-header-edits' )->plain() );
		$table = '';

		// Page creator
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-firstuser' )->escaped(), Linker::userLink( $pageInfo['firstuserid'], $pageInfo['firstuser'] )
		);

		// Date of page creation
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-firsttime' )->escaped(), $lang->userTimeAndDate( $pageInfo['firsttime'], $user )
		);

		// Latest editor
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-lastuser' )->escaped(), Linker::userLink( $pageInfo['lastuserid'], $pageInfo['lastuser'] )
		);

		// Date of latest edit
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-lasttime' )->escaped(), $lang->userTimeAndDate( $pageInfo['lasttime'], $user )
		);

		// Total number of edits
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-edits' )->escaped(), $lang->formatNum( $pageInfo['edits'] )
		);

		// Total number of distinct authors
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-authors' )->escaped(), $lang->formatNum( $pageInfo['authors'] )
		);

		// Recent number of edits (within past 30 days)
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-recent-edits', $lang->formatDuration( $wgRCMaxAge ) )->escaped(),
			$lang->formatNum( $pageInfo['recent_edits'] )
		);

		// Recent number of distinct authors
		$table = $this->addRow( $table,
			$this->msg( 'pageinfo-recent-authors' )->escaped(), $lang->formatNum( $pageInfo['recent_authors'] )
		);

		$content = $this->addTable( $content, $table );

		// Array of MagicWord objects
		$magicWords = MagicWord::getDoubleUnderscoreArray();

		// Array of magic word IDs
		$wordIDs = $magicWords->names;

		// Array of IDs => localized magic words
		$localizedWords = $wgContLang->getMagicWords();

		$listItems = array();
		foreach ( $pageProperties as $property => $value ) {
			if ( in_array( $property, $wordIDs ) ) {
				$listItems[] = Html::element( 'li', array(), $localizedWords[$property][1] );
			}
		}

		$localizedList = Html::rawElement( 'ul', array(), implode( '', $listItems ) );
		$hiddenCategories = $this->page->getHiddenCategories();
		$transcludedTemplates = $title->getTemplateLinksFrom();

		if ( count( $listItems ) > 0
			|| count( $hiddenCategories ) > 0
			|| count( $transcludedTemplates ) > 0 ) {
			// Page properties
			$content .= $this->makeHeader( $this->msg( 'pageinfo-header-properties' )->plain() );
			$table = '';

			// Magic words
			if ( count( $listItems ) > 0 ) {
				$table = $this->addRow( $table,
					$this->msg( 'pageinfo-magic-words' )->numParams( count( $listItems ) )->escaped(),
					$localizedList
				);
			}

			// Hide "This page is a member of # hidden categories explanation
			$content .= Html::element( 'style', array(),
				'.mw-hiddenCategoriesExplanation { display: none; }' );

			// Hidden categories
			if ( count( $hiddenCategories ) > 0 ) {
				$table = $this->addRow( $table,
					$this->msg( 'pageinfo-hidden-categories' )
						->numParams( count( $hiddenCategories ) )->escaped(),
					Linker::formatHiddenCategories( $hiddenCategories )
				);
			}

			// Hide "Templates used on this page:" explanation
			$content .= Html::element( 'style', array(),
				'.mw-templatesUsedExplanation { display: none; }' );

			// Transcluded templates
			if ( count( $transcludedTemplates ) > 0 ) {
				$table = $this->addRow( $table,
					$this->msg( 'pageinfo-templates' )
						->numParams( count( $transcludedTemplates ) )->escaped(),
					Linker::formatTemplates( $transcludedTemplates )
				);
			}

			$content = $this->addTable( $content, $table );
		}

		// Footer
		if ( !$this->msg( 'pageinfo-footer' )->isDisabled() ) {
			$content .= $this->msg( 'pageinfo-footer' )->parse();
		}

		return $content;
	}

	/**
	 * Creates a header that can be added to the output.
	 *
	 * @param $header The header text.
	 * @return string The HTML.
	 */
	public static function makeHeader( $header ) {
		global $wgParser;
		$spanAttribs = array( 'class' => 'mw-headline', 'id' => $wgParser->guessSectionNameFromWikiText( $header ) );
		return Html::rawElement( 'h2', array(), Html::element( 'span', $spanAttribs, $header ) );
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
		$threshold = $dbr->timestamp( time() - $wgRCMaxAge );

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
			array( 'rev_user', 'rev_user_text', 'rev_timestamp' ),
			array( 'rev_page' => $id ),
			__METHOD__,
			$options
		) );

		$result['firstuser'] = $row['rev_user_text'];
		$result['firstuserid'] = $row['rev_user'];
		$result['firsttime'] = $row['rev_timestamp'];

		// Latest editor + date of latest edit
		$options['ORDER BY'] = 'rev_timestamp DESC';
		$row = $dbr->fetchRow( $dbr->select(
			'revision',
			array( 'rev_user', 'rev_user_text', 'rev_timestamp' ),
			array( 'rev_page' => $id ),
			__METHOD__,
			$options
		) );

		$result['lastuser'] = $row['rev_user_text'];
		$result['lastuserid'] = $row['rev_user'];
		$result['lasttime'] = $row['rev_timestamp'];

		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * Adds a row to a table that will be added to the content.
	 *
	 * @param $table string The table that will be added to the content
	 * @param $name string The name of the row
	 * @param $value string The value of the row
	 * @return string The table with the row added
	 */
	protected function addRow( $table, $name, $value ) {
		return $table . Html::rawElement( 'tr', array(),
			Html::rawElement( 'td', array( 'valign' => 'top' ), $name ) .
			Html::rawElement( 'td', array(), $value )
		);
	}

	/**
	 * Adds a table to the content that will be added to the output.
	 *
	 * @param $content string The content that will be added to the output
	 * @param $table string The table
	 * @return string The content with the table added
	 */
	protected function addTable( $content, $table ) {
		return $content . Html::rawElement( 'table', array( 'class' => 'wikitable mw-page-info' ),
			$table );
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

	/**
	 * Get a list of contributors of $article
	 * @return string: html
	 */
	protected function getContributors() {
		global $wgHiddenPrefs;

		$contributors = $this->page->getContributors();
		$real_names = array();
		$user_names = array();
		$anon_ips = array();

		# Sift for real versus user names
		foreach ( $contributors as $user ) {
			$page = $user->isAnon()
				? SpecialPage::getTitleFor( 'Contributions', $user->getName() )
				: $user->getUserPage();

			if ( $user->getID() == 0 ) {
				$anon_ips[] = Linker::link( $page, htmlspecialchars( $user->getName() ) );
			} elseif ( !in_array( 'realname', $wgHiddenPrefs ) && $user->getRealName() ) {
				$real_names[] = Linker::link( $page, htmlspecialchars( $user->getRealName() ) );
			} else {
				$user_names[] = Linker::link( $page, htmlspecialchars( $user->getName() ) );
			}
		}

		$lang = $this->getLanguage();

		$real = $lang->listToText( $real_names );

		# "ThisSite user(s) A, B and C"
		if ( count( $user_names ) ) {
			$user = $this->msg( 'siteusers' )->rawParams( $lang->listToText( $user_names ) )->params(
				count( $user_names ) )->escaped();
		} else {
			$user = false;
		}

		if ( count( $anon_ips ) ) {
			$anon = $this->msg( 'anonusers' )->rawParams( $lang->listToText( $anon_ips ) )->params(
				count( $anon_ips ) )->escaped();
		} else {
			$anon = false;
		}

		# This is the big list, all mooshed together. We sift for blank strings
		$fulllist = array();
		foreach ( array( $real, $user, $anon ) as $s ) {
			if ( $s !== '' ) {
				array_push( $fulllist, $s );
			}
		}

		$count = count( $fulllist );
		# "Based on work by ..."
		return $count
			? $this->msg( 'othercontribs' )->rawParams(
				$lang->listToText( $fulllist ) )->params( $count )->escaped()
			: '';
	}
}
