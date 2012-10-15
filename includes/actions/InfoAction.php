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
		$content = '';

		// Validate revision
		$oldid = $this->page->getOldID();
		if ( $oldid ) {
			$revision = $this->page->getRevisionFetched();

			// Revision is missing
			if ( $revision === null ) {
				return $this->msg( 'missing-revision', $oldid )->parse();
			}

			// Revision is not current
			if ( !$revision->isCurrent() ) {
				return $this->msg( 'pageinfo-not-current' )->plain();
			}
		}

		// Page header
		if ( !$this->msg( 'pageinfo-header' )->isDisabled() ) {
			$content .= $this->msg( 'pageinfo-header' )->parse();
		}

		// Hide "This page is a member of # hidden categories" explanation
		$content .= Html::element( 'style', array(),
			'.mw-hiddenCategoriesExplanation { display: none; }' );

		// Hide "Templates used on this page" explanation
		$content .= Html::element( 'style', array(),
			'.mw-templatesUsedExplanation { display: none; }' );

		// Get page information
		$pageInfo = $this->pageInfo();

		// Allow extensions to add additional information
		wfRunHooks( 'InfoAction', array( $this->getContext(), &$pageInfo ) );

		// Render page information
		foreach ( $pageInfo as $header => $infoTable ) {
			$content .= $this->makeHeader( $this->msg( "pageinfo-${header}" )->escaped() );
			$table = '';
			foreach ( $infoTable as $infoRow ) {
				$name = ( $infoRow[0] instanceof Message ) ? $infoRow[0]->escaped() : $infoRow[0];
				$value = ( $infoRow[1] instanceof Message ) ? $infoRow[1]->escaped() : $infoRow[1];
				$table = $this->addRow( $table, $name, $value );
			}
			$content = $this->addTable( $content, $table );
		}

		// Page footer
		if ( !$this->msg( 'pageinfo-footer' )->isDisabled() ) {
			$content .= $this->msg( 'pageinfo-footer' )->parse();
		}

		// Page credits
		/*if ( $this->page->exists() ) {
			$content .= Html::rawElement( 'div', array( 'id' => 'mw-credits' ), $this->getContributors() );
		}*/

		return $content;
	}

	/**
	 * Creates a header that can be added to the output.
	 *
	 * @param $header The header text.
	 * @return string The HTML.
	 */
	protected function makeHeader( $header ) {
		global $wgParser;
		$spanAttribs = array( 'class' => 'mw-headline', 'id' => $wgParser->guessSectionNameFromWikiText( $header ) );
		return Html::rawElement( 'h2', array(), Html::element( 'span', $spanAttribs, $header ) );
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
			Html::rawElement( 'td', array( 'style' => 'vertical-align: top;' ), $name ) .
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
	 * Returns page information in an easily-manipulated format. Array keys are used so extensions
	 * may add additional information in arbitrary positions. Array values are arrays with one
	 * element to be rendered as a header, arrays with two elements to be rendered as a table row.
	 */
	protected function pageInfo() {
		global $wgContLang, $wgRCMaxAge;

		$user = $this->getUser();
		$lang = $this->getLanguage();
		$title = $this->getTitle();
		$id = $title->getArticleID();

		// Get page information that would be too "expensive" to retrieve by normal means
		$pageCounts = self::pageCounts( $title, $user );

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

		// Basic information
		$pageInfo = array();
		$pageInfo['header-basic'] = array();

		// Display title
		$displayTitle = $title->getPrefixedText();
		if ( !empty( $pageProperties['displaytitle'] ) ) {
			$displayTitle = $pageProperties['displaytitle'];
		}

		$pageInfo['header-basic'][] = array(
			$this->msg( 'pageinfo-display-title' ), $displayTitle
		);

		// Default sort key
		$sortKey = $title->getCategorySortKey();
		if ( !empty( $pageProperties['defaultsort'] ) ) {
			$sortKey = $pageProperties['defaultsort'];
		}

		$pageInfo['header-basic'][] = array( $this->msg( 'pageinfo-default-sort' ), $sortKey );

		// Page length (in bytes)
		$pageInfo['header-basic'][] = array(
			$this->msg( 'pageinfo-length' ), $lang->formatNum( $title->getLength() )
		);

		// Page ID (number not localised, as it's a database ID)
		$pageInfo['header-basic'][] = array( $this->msg( 'pageinfo-article-id' ), $id );

		// Search engine status
		$pOutput = new ParserOutput();
		if ( isset( $pageProperties['noindex'] ) ) {
			$pOutput->setIndexPolicy( 'noindex' );
		}

		// Use robot policy logic
		$policy = $this->page->getRobotPolicy( 'view', $pOutput );
		$pageInfo['header-basic'][] = array(
			$this->msg( 'pageinfo-robot-policy' ), $this->msg( "pageinfo-robot-${policy['index']}" )
		);

		if ( isset( $pageCounts['views'] ) ) {
			// Number of views
			$pageInfo['header-basic'][] = array(
				$this->msg( 'pageinfo-views' ), $lang->formatNum( $pageCounts['views'] )
			);
		}

		if ( isset( $pageCounts['watchers'] ) ) {
			// Number of page watchers
			$pageInfo['header-basic'][] = array(
				$this->msg( 'pageinfo-watchers' ), $lang->formatNum( $pageCounts['watchers'] )
			);
		}

		// Redirects to this page
		$whatLinksHere = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedText() );
		$pageInfo['header-basic'][] = array(
			Linker::link(
				$whatLinksHere,
				$this->msg( 'pageinfo-redirects-name' )->escaped(),
				array(),
				array( 'hidelinks' => 1, 'hidetrans' => 1 )
			),
			$this->msg( 'pageinfo-redirects-value' )
				->numParams( count( $title->getRedirectsHere() ) )
		);

		// Subpages of this page, if subpages are enabled for the current NS
		if ( MWNamespace::hasSubpages( $title->getNamespace() ) ) {
			$prefixIndex = SpecialPage::getTitleFor( 'Prefixindex', $title->getPrefixedText() . '/' );
			$pageInfo['header-basic'][] = array(
				Linker::link( $prefixIndex, $this->msg( 'pageinfo-subpages-name' )->escaped() ),
				$this->msg( 'pageinfo-subpages-value' )
					->numParams(
						$pageCounts['subpages']['total'],
						$pageCounts['subpages']['redirects'],
						$pageCounts['subpages']['nonredirects'] )
			);
		}

		// Page protection
		$pageInfo['header-restrictions'] = array();

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

			$pageInfo['header-restrictions'][] = array(
				$this->msg( "restriction-$restrictionType" ), $message
			);
		}

		if ( !$this->page->exists() ) {
			return $pageInfo;
		}

		// Edit history
		$pageInfo['header-edits'] = array();

		$firstRev = $this->page->getOldestRevision();

		// Page creator
		$pageInfo['header-edits'][] = array(
			$this->msg( 'pageinfo-firstuser' ),
			Linker::revUserTools( $firstRev )
		);

		// Date of page creation
		$pageInfo['header-edits'][] = array(
			$this->msg( 'pageinfo-firsttime' ),
			Linker::linkKnown(
				$title,
				$lang->userTimeAndDate( $firstRev->getTimestamp(), $user ),
				array(),
				array( 'oldid' => $firstRev->getId() )
			)
		);

		// Latest editor
		$pageInfo['header-edits'][] = array(
			$this->msg( 'pageinfo-lastuser' ),
			Linker::revUserTools( $this->page->getRevision() )
		);

		// Date of latest edit
		$pageInfo['header-edits'][] = array(
			$this->msg( 'pageinfo-lasttime' ),
			Linker::linkKnown(
				$title,
				$lang->userTimeAndDate( $this->page->getTimestamp(), $user ),
				array(),
				array( 'oldid' => $this->page->getLatest() )
			)
		);

		// Total number of edits
		$pageInfo['header-edits'][] = array(
			$this->msg( 'pageinfo-edits' ), $lang->formatNum( $pageCounts['edits'] )
		);

		// Total number of distinct authors
		$pageInfo['header-edits'][] = array(
			$this->msg( 'pageinfo-authors' ), $lang->formatNum( $pageCounts['authors'] )
		);

		// Recent number of edits (within past 30 days)
		$pageInfo['header-edits'][] = array(
			$this->msg( 'pageinfo-recent-edits', $lang->formatDuration( $wgRCMaxAge ) ),
			$lang->formatNum( $pageCounts['recent_edits'] )
		);

		// Recent number of distinct authors
		$pageInfo['header-edits'][] = array(
			$this->msg( 'pageinfo-recent-authors' ), $lang->formatNum( $pageCounts['recent_authors'] )
		);

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
			$pageInfo['header-properties'] = array();

			// Magic words
			if ( count( $listItems ) > 0 ) {
				$pageInfo['header-properties'][] = array(
					$this->msg( 'pageinfo-magic-words' )->numParams( count( $listItems ) ),
					$localizedList
				);
			}

			// Hidden categories
			if ( count( $hiddenCategories ) > 0 ) {
				$pageInfo['header-properties'][] = array(
					$this->msg( 'pageinfo-hidden-categories' )
						->numParams( count( $hiddenCategories ) ),
					Linker::formatHiddenCategories( $hiddenCategories )
				);
			}

			// Transcluded templates
			if ( count( $transcludedTemplates ) > 0 ) {
				$pageInfo['header-properties'][] = array(
					$this->msg( 'pageinfo-templates' )
						->numParams( count( $transcludedTemplates ) ),
					Linker::formatTemplates( $transcludedTemplates )
				);
			}
		}

		return $pageInfo;
	}

	/**
	 * Returns page counts that would be too "expensive" to retrieve by normal means.
	 *
	 * @param $title Title object
	 * @param $user User object
	 * @return array
	 */
	protected static function pageCounts( $title, $user ) {
		global $wgRCMaxAge, $wgDisableCounters;

		wfProfileIn( __METHOD__ );
		$id = $title->getArticleID();

		$dbr = wfGetDB( DB_SLAVE );
		$result = array();

		if ( !$wgDisableCounters ) {
			// Number of views
			$views = (int) $dbr->selectField(
				'page',
				'page_counter',
				array( 'page_id' => $id ),
				__METHOD__
			);
			$result['views'] = $views;
		}

		if ( $user->isAllowed( 'unwatchedpages' ) ) {
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

		// Subpages (if enabled)
		if ( MWNamespace::hasSubpages( $title->getNamespace() ) ) {
			$conds = array( 'page_namespace' => $title->getNamespace() );
			$conds[] = 'page_title ' . $dbr->buildLike( $title->getDBkey() . '/', $dbr->anyString() );

			// Subpages of this page (redirects)
			$conds['page_is_redirect'] = 1;
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
		}

		wfProfileOut( __METHOD__ );
		return $result;
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

	/**
	 * Returns the description that goes below the <h1> tag.
	 *
	 * @return string
	 */
	protected function getDescription() {
		return '';
	}
}
