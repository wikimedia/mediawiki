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

/**
 * Displays information about a page.
 *
 * @ingroup Actions
 */
class InfoAction extends FormlessAction {
	const CACHE_VERSION = '2013-03-17';

	/**
	 * Returns the name of the action this object responds to.
	 *
	 * @return string Lowercase name
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
	 * Clear the info cache for a given Title.
	 *
	 * @since 1.22
	 * @param Title $title Title to clear cache for
	 */
	public static function invalidateCache( Title $title ) {
		global $wgMemc;
		// Clear page info.
		$revision = WikiPage::factory( $title )->getRevision();
		if ( $revision !== null ) {
			$key = wfMemcKey( 'infoaction', sha1( $title->getPrefixedText() ), $revision->getId() );
			$wgMemc->delete( $key );
		}
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
			'.mw-hiddenCategoriesExplanation { display: none; }' ) . "\n";

		// Hide "Templates used on this page" explanation
		$content .= Html::element( 'style', array(),
			'.mw-templatesUsedExplanation { display: none; }' ) . "\n";

		// Get page information
		$pageInfo = $this->pageInfo();

		// Allow extensions to add additional information
		wfRunHooks( 'InfoAction', array( $this->getContext(), &$pageInfo ) );

		// Render page information
		foreach ( $pageInfo as $header => $infoTable ) {
			// Messages:
			// pageinfo-header-basic, pageinfo-header-edits, pageinfo-header-restrictions,
			// pageinfo-header-properties, pageinfo-category-info
			$content .= $this->makeHeader( $this->msg( "pageinfo-${header}" )->escaped() ) . "\n";
			$table = "\n";
			foreach ( $infoTable as $infoRow ) {
				$name = ( $infoRow[0] instanceof Message ) ? $infoRow[0]->escaped() : $infoRow[0];
				$value = ( $infoRow[1] instanceof Message ) ? $infoRow[1]->escaped() : $infoRow[1];
				$id = ( $infoRow[0] instanceof Message ) ? $infoRow[0]->getKey() : null;
				$table = $this->addRow( $table, $name, $value, $id ) . "\n";
			}
			$content = $this->addTable( $content, $table ) . "\n";
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
	 * @param string $header The header text.
	 * @return string The HTML.
	 */
	protected function makeHeader( $header ) {
		$spanAttribs = array( 'class' => 'mw-headline', 'id' => Sanitizer::escapeId( $header ) );

		return Html::rawElement( 'h2', array(), Html::element( 'span', $spanAttribs, $header ) );
	}

	/**
	 * Adds a row to a table that will be added to the content.
	 *
	 * @param string $table The table that will be added to the content
	 * @param string $name The name of the row
	 * @param string $value The value of the row
	 * @param string $id The ID to use for the 'tr' element
	 * @return string The table with the row added
	 */
	protected function addRow( $table, $name, $value, $id ) {
		return $table . Html::rawElement( 'tr', $id === null ? array() : array( 'id' => 'mw-' . $id ),
			Html::rawElement( 'td', array( 'style' => 'vertical-align: top;' ), $name ) .
			Html::rawElement( 'td', array(), $value )
		);
	}

	/**
	 * Adds a table to the content that will be added to the output.
	 *
	 * @param string $content The content that will be added to the output
	 * @param string $table The table
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
	 *
	 * @return array
	 */
	protected function pageInfo() {
		global $wgContLang, $wgRCMaxAge, $wgMemc, $wgMiserMode,
			$wgUnwatchedPageThreshold, $wgPageInfoTransclusionLimit;

		$user = $this->getUser();
		$lang = $this->getLanguage();
		$title = $this->getTitle();
		$id = $title->getArticleID();

		$memcKey = wfMemcKey( 'infoaction',
			sha1( $title->getPrefixedText() ), $this->page->getLatest() );
		$pageCounts = $wgMemc->get( $memcKey );
		$version = isset( $pageCounts['cacheversion'] ) ? $pageCounts['cacheversion'] : false;
		if ( $pageCounts === false || $version !== self::CACHE_VERSION ) {
			// Get page information that would be too "expensive" to retrieve by normal means
			$pageCounts = self::pageCounts( $title );
			$pageCounts['cacheversion'] = self::CACHE_VERSION;

			$wgMemc->set( $memcKey, $pageCounts );
		}

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

		// Is it a redirect? If so, where to?
		if ( $title->isRedirect() ) {
			$pageInfo['header-basic'][] = array(
				$this->msg( 'pageinfo-redirectsto' ),
				Linker::link( $this->page->getRedirectTarget() ) .
				$this->msg( 'word-separator' )->text() .
				$this->msg( 'parentheses', Linker::link(
					$this->page->getRedirectTarget(),
					$this->msg( 'pageinfo-redirectsto-info' )->escaped(),
					array(),
					array( 'action' => 'info' )
				) )->text()
			);
		}

		// Default sort key
		$sortKey = $title->getCategorySortkey();
		if ( !empty( $pageProperties['defaultsort'] ) ) {
			$sortKey = $pageProperties['defaultsort'];
		}

		$sortKey = htmlspecialchars( $sortKey );
		$pageInfo['header-basic'][] = array( $this->msg( 'pageinfo-default-sort' ), $sortKey );

		// Page length (in bytes)
		$pageInfo['header-basic'][] = array(
			$this->msg( 'pageinfo-length' ), $lang->formatNum( $title->getLength() )
		);

		// Page ID (number not localised, as it's a database ID)
		$pageInfo['header-basic'][] = array( $this->msg( 'pageinfo-article-id' ), $id );

		// Language in which the page content is (supposed to be) written
		$pageLang = $title->getPageLanguage()->getCode();
		$pageInfo['header-basic'][] = array( $this->msg( 'pageinfo-language' ),
			Language::fetchLanguageName( $pageLang, $lang->getCode() )
			. ' ' . $this->msg( 'parentheses', $pageLang ) );

		// Content model of the page
		$pageInfo['header-basic'][] = array(
			$this->msg( 'pageinfo-content-model' ),
			ContentHandler::getLocalizedName( $title->getContentModel() )
		);

		// Search engine status
		$pOutput = new ParserOutput();
		if ( isset( $pageProperties['noindex'] ) ) {
			$pOutput->setIndexPolicy( 'noindex' );
		}
		if ( isset( $pageProperties['index'] ) ) {
			$pOutput->setIndexPolicy( 'index' );
		}

		// Use robot policy logic
		$policy = $this->page->getRobotPolicy( 'view', $pOutput );
		$pageInfo['header-basic'][] = array(
			// Messages: pageinfo-robot-index, pageinfo-robot-noindex
			$this->msg( 'pageinfo-robot-policy' ), $this->msg( "pageinfo-robot-${policy['index']}" )
		);

		if ( isset( $pageCounts['views'] ) ) {
			// Number of views
			$pageInfo['header-basic'][] = array(
				$this->msg( 'pageinfo-views' ), $lang->formatNum( $pageCounts['views'] )
			);
		}

		if (
			$user->isAllowed( 'unwatchedpages' ) ||
			( $wgUnwatchedPageThreshold !== false &&
				$pageCounts['watchers'] >= $wgUnwatchedPageThreshold )
		) {
			// Number of page watchers
			$pageInfo['header-basic'][] = array(
				$this->msg( 'pageinfo-watchers' ), $lang->formatNum( $pageCounts['watchers'] )
			);
		} elseif ( $wgUnwatchedPageThreshold !== false ) {
			$pageInfo['header-basic'][] = array(
				$this->msg( 'pageinfo-watchers' ),
				$this->msg( 'pageinfo-few-watchers' )->numParams( $wgUnwatchedPageThreshold )
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

		// Is it counted as a content page?
		if ( $this->page->isCountable() ) {
			$pageInfo['header-basic'][] = array(
				$this->msg( 'pageinfo-contentpage' ),
				$this->msg( 'pageinfo-contentpage-yes' )
			);
		}

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

		if ( $title->inNamespace( NS_CATEGORY ) ) {
			$category = Category::newFromTitle( $title );
			$pageInfo['category-info'] = array(
				array(
					$this->msg( 'pageinfo-category-pages' ),
					$lang->formatNum( $category->getPageCount() )
				),
				array(
					$this->msg( 'pageinfo-category-subcats' ),
					$lang->formatNum( $category->getSubcatCount() )
				),
				array(
					$this->msg( 'pageinfo-category-files' ),
					$lang->formatNum( $category->getFileCount() )
				)
			);
		}

		// Page protection
		$pageInfo['header-restrictions'] = array();

		// Is this page effected by the cascading protection of something which includes it?
		if ( $title->isCascadeProtected() ) {
			$cascadingFrom = '';
			$sources = $title->getCascadeProtectionSources(); // Array deferencing is in PHP 5.4 :(

			foreach ( $sources[0] as $sourceTitle ) {
				$cascadingFrom .= Html::rawElement( 'li', array(), Linker::linkKnown( $sourceTitle ) );
			}

			$cascadingFrom = Html::rawElement( 'ul', array(), $cascadingFrom );
			$pageInfo['header-restrictions'][] = array(
				$this->msg( 'pageinfo-protect-cascading-from' ),
				$cascadingFrom
			);
		}

		// Is out protection set to cascade to other pages?
		if ( $title->areRestrictionsCascading() ) {
			$pageInfo['header-restrictions'][] = array(
				$this->msg( 'pageinfo-protect-cascading' ),
				$this->msg( 'pageinfo-protect-cascading-yes' )
			);
		}

		// Page protection
		foreach ( $title->getRestrictionTypes() as $restrictionType ) {
			$protectionLevel = implode( ', ', $title->getRestrictions( $restrictionType ) );

			if ( $protectionLevel == '' ) {
				// Allow all users
				$message = $this->msg( 'protect-default' )->escaped();
			} else {
				// Administrators only
				// Messages: protect-level-autoconfirmed, protect-level-sysop
				$message = $this->msg( "protect-level-$protectionLevel" );
				if ( $message->isDisabled() ) {
					// Require "$1" permission
					$message = $this->msg( "protect-fallback", $protectionLevel )->parse();
				} else {
					$message = $message->escaped();
				}
			}

			// Messages: restriction-edit, restriction-move, restriction-create,
			// restriction-upload
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
		$lastRev = $this->page->getRevision();
		$batch = new LinkBatch;

		if ( $firstRev ) {
			$firstRevUser = $firstRev->getUserText( Revision::FOR_THIS_USER );
			if ( $firstRevUser !== '' ) {
				$batch->add( NS_USER, $firstRevUser );
				$batch->add( NS_USER_TALK, $firstRevUser );
			}
		}

		if ( $lastRev ) {
			$lastRevUser = $lastRev->getUserText( Revision::FOR_THIS_USER );
			if ( $lastRevUser !== '' ) {
				$batch->add( NS_USER, $lastRevUser );
				$batch->add( NS_USER_TALK, $lastRevUser );
			}
		}

		$batch->execute();

		if ( $firstRev ) {
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
		}

		if ( $lastRev ) {
			// Latest editor
			$pageInfo['header-edits'][] = array(
				$this->msg( 'pageinfo-lastuser' ),
				Linker::revUserTools( $lastRev )
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
		}

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

		if (
			count( $listItems ) > 0 ||
			count( $hiddenCategories ) > 0 ||
			$pageCounts['transclusion']['from'] > 0 ||
			$pageCounts['transclusion']['to'] > 0
		) {
			$options = array( 'LIMIT' => $wgPageInfoTransclusionLimit );
			$transcludedTemplates = $title->getTemplateLinksFrom( $options );
			if ( $wgMiserMode ) {
				$transcludedTargets = array();
			} else {
				$transcludedTargets = $title->getTemplateLinksTo( $options );
			}

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
			if ( $pageCounts['transclusion']['from'] > 0 ) {
				if ( $pageCounts['transclusion']['from'] > count( $transcludedTemplates ) ) {
					$more = $this->msg( 'morenotlisted' )->escaped();
				} else {
					$more = null;
				}

				$pageInfo['header-properties'][] = array(
					$this->msg( 'pageinfo-templates' )
						->numParams( $pageCounts['transclusion']['from'] ),
					Linker::formatTemplates(
						$transcludedTemplates,
						false,
						false,
						$more )
				);
			}

			if ( !$wgMiserMode && $pageCounts['transclusion']['to'] > 0 ) {
				if ( $pageCounts['transclusion']['to'] > count( $transcludedTargets ) ) {
					$more = Linker::link(
						$whatLinksHere,
						$this->msg( 'moredotdotdot' )->escaped(),
						array(),
						array( 'hidelinks' => 1, 'hideredirs' => 1 )
					);
				} else {
					$more = null;
				}

				$pageInfo['header-properties'][] = array(
					$this->msg( 'pageinfo-transclusions' )
						->numParams( $pageCounts['transclusion']['to'] ),
					Linker::formatTemplates(
						$transcludedTargets,
						false,
						false,
						$more )
				);
			}
		}

		return $pageInfo;
	}

	/**
	 * Returns page counts that would be too "expensive" to retrieve by normal means.
	 *
	 * @param Title $title Title to get counts for
	 * @return array
	 */
	protected static function pageCounts( Title $title ) {
		global $wgRCMaxAge, $wgDisableCounters, $wgMiserMode;

		wfProfileIn( __METHOD__ );
		$id = $title->getArticleID();

		$dbr = wfGetDB( DB_SLAVE );
		$result = array();

		if ( !$wgDisableCounters ) {
			// Number of views
			$views = (int)$dbr->selectField(
				'page',
				'page_counter',
				array( 'page_id' => $id ),
				__METHOD__
			);
			$result['views'] = $views;
		}

		// Number of page watchers
		$watchers = (int)$dbr->selectField(
			'watchlist',
			'COUNT(*)',
			array(
				'wl_namespace' => $title->getNamespace(),
				'wl_title' => $title->getDBkey(),
			),
			__METHOD__
		);
		$result['watchers'] = $watchers;

		// Total number of edits
		$edits = (int)$dbr->selectField(
			'revision',
			'COUNT(rev_page)',
			array( 'rev_page' => $id ),
			__METHOD__
		);
		$result['edits'] = $edits;

		// Total number of distinct authors
		$authors = (int)$dbr->selectField(
			'revision',
			'COUNT(DISTINCT rev_user_text)',
			array( 'rev_page' => $id ),
			__METHOD__
		);
		$result['authors'] = $authors;

		// "Recent" threshold defined by $wgRCMaxAge
		$threshold = $dbr->timestamp( time() - $wgRCMaxAge );

		// Recent number of edits
		$edits = (int)$dbr->selectField(
			'revision',
			'COUNT(rev_page)',
			array(
				'rev_page' => $id,
				"rev_timestamp >= " . $dbr->addQuotes( $threshold )
			),
			__METHOD__
		);
		$result['recent_edits'] = $edits;

		// Recent number of distinct authors
		$authors = (int)$dbr->selectField(
			'revision',
			'COUNT(DISTINCT rev_user_text)',
			array(
				'rev_page' => $id,
				"rev_timestamp >= " . $dbr->addQuotes( $threshold )
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
			$result['subpages']['redirects'] = (int)$dbr->selectField(
				'page',
				'COUNT(page_id)',
				$conds,
				__METHOD__ );

			// Subpages of this page (non-redirects)
			$conds['page_is_redirect'] = 0;
			$result['subpages']['nonredirects'] = (int)$dbr->selectField(
				'page',
				'COUNT(page_id)',
				$conds,
				__METHOD__
			);

			// Subpages of this page (total)
			$result['subpages']['total'] = $result['subpages']['redirects']
				+ $result['subpages']['nonredirects'];
		}

		// Counts for the number of transclusion links (to/from)
		if ( $wgMiserMode ) {
			$result['transclusion']['to'] = 0;
		} else {
			$result['transclusion']['to'] = (int)$dbr->selectField(
				'templatelinks',
				'COUNT(tl_from)',
				array(
					'tl_namespace' => $title->getNamespace(),
					'tl_title' => $title->getDBkey()
				),
				__METHOD__
			);
		}

		$result['transclusion']['from'] = (int)$dbr->selectField(
			'templatelinks',
			'COUNT(*)',
			array( 'tl_from' => $title->getArticleID() ),
			__METHOD__
		);

		wfProfileOut( __METHOD__ );

		return $result;
	}

	/**
	 * Returns the name that goes in the "<h1>" page title.
	 *
	 * @return string
	 */
	protected function getPageTitle() {
		return $this->msg( 'pageinfo-title', $this->getTitle()->getPrefixedText() )->text();
	}

	/**
	 * Get a list of contributors of $article
	 * @return string Html
	 */
	protected function getContributors() {
		global $wgHiddenPrefs;

		$contributors = $this->page->getContributors();
		$real_names = array();
		$user_names = array();
		$anon_ips = array();

		# Sift for real versus user names
		/** @var $user User */
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
	 * Returns the description that goes below the "<h1>" tag.
	 *
	 * @return string
	 */
	protected function getDescription() {
		return '';
	}
}
