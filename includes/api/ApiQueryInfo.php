<?php
/**
 *
 *
 * Created on Sep 25, 2006
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
 * A query module to show basic page information.
 *
 * @ingroup API
 */
class ApiQueryInfo extends ApiQueryBase {

	private $fld_protection = false, $fld_talkid = false,
		$fld_subjectid = false, $fld_url = false,
		$fld_readable = false, $fld_watched = false, $fld_watchers = false,
		$fld_notificationtimestamp = false,
		$fld_preload = false, $fld_displaytitle = false;

	private $params, $titles, $missing, $everything, $pageCounter;

	private $pageRestrictions, $pageIsRedir, $pageIsNew, $pageTouched,
		$pageLatest, $pageLength;

	private $protections, $watched, $watchers, $notificationtimestamps, $talkids, $subjectids, $displaytitles;
	private $showZeroWatchers = false;

	private $tokenFunctions;

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'in' );
	}

	/**
	 * @param $pageSet ApiPageSet
	 * @return void
	 */
	public function requestExtraData( $pageSet ) {
		global $wgDisableCounters, $wgContentHandlerUseDB;

		$pageSet->requestField( 'page_restrictions' );
		// when resolving redirects, no page will have this field
		if ( !$pageSet->isResolvingRedirects() ) {
			$pageSet->requestField( 'page_is_redirect' );
		}
		$pageSet->requestField( 'page_is_new' );
		if ( !$wgDisableCounters ) {
			$pageSet->requestField( 'page_counter' );
		}
		$pageSet->requestField( 'page_touched' );
		$pageSet->requestField( 'page_latest' );
		$pageSet->requestField( 'page_len' );
		if ( $wgContentHandlerUseDB ) {
			$pageSet->requestField( 'page_content_model' );
		}
	}

	/**
	 * Get an array mapping token names to their handler functions.
	 * The prototype for a token function is func($pageid, $title)
	 * it should return a token or false (permission denied)
	 * @return array array(tokenname => function)
	 */
	protected function getTokenFunctions() {
		// Don't call the hooks twice
		if ( isset( $this->tokenFunctions ) ) {
			return $this->tokenFunctions;
		}

		// If we're in JSON callback mode, no tokens can be obtained
		if ( !is_null( $this->getMain()->getRequest()->getVal( 'callback' ) ) ) {
			return array();
		}

		$this->tokenFunctions = array(
			'edit' => array( 'ApiQueryInfo', 'getEditToken' ),
			'delete' => array( 'ApiQueryInfo', 'getDeleteToken' ),
			'protect' => array( 'ApiQueryInfo', 'getProtectToken' ),
			'move' => array( 'ApiQueryInfo', 'getMoveToken' ),
			'block' => array( 'ApiQueryInfo', 'getBlockToken' ),
			'unblock' => array( 'ApiQueryInfo', 'getUnblockToken' ),
			'email' => array( 'ApiQueryInfo', 'getEmailToken' ),
			'import' => array( 'ApiQueryInfo', 'getImportToken' ),
			'watch' => array( 'ApiQueryInfo', 'getWatchToken' ),
		);
		wfRunHooks( 'APIQueryInfoTokens', array( &$this->tokenFunctions ) );
		return $this->tokenFunctions;
	}

	static $cachedTokens = array();

	public static function resetTokenCache() {
		ApiQueryInfo::$cachedTokens = array();
	}

	public static function getEditToken( $pageid, $title ) {
		// We could check for $title->userCan('edit') here,
		// but that's too expensive for this purpose
		// and would break caching
		global $wgUser;
		if ( !$wgUser->isAllowed( 'edit' ) ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['edit'] ) ) {
			ApiQueryInfo::$cachedTokens['edit'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['edit'];
	}

	public static function getDeleteToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->isAllowed( 'delete' ) ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['delete'] ) ) {
			ApiQueryInfo::$cachedTokens['delete'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['delete'];
	}

	public static function getProtectToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->isAllowed( 'protect' ) ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['protect'] ) ) {
			ApiQueryInfo::$cachedTokens['protect'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['protect'];
	}

	public static function getMoveToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->isAllowed( 'move' ) ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['move'] ) ) {
			ApiQueryInfo::$cachedTokens['move'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['move'];
	}

	public static function getBlockToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->isAllowed( 'block' ) ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['block'] ) ) {
			ApiQueryInfo::$cachedTokens['block'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['block'];
	}

	public static function getUnblockToken( $pageid, $title ) {
		// Currently, this is exactly the same as the block token
		return self::getBlockToken( $pageid, $title );
	}

	public static function getEmailToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->canSendEmail() || $wgUser->isBlockedFromEmailUser() ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['email'] ) ) {
			ApiQueryInfo::$cachedTokens['email'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['email'];
	}

	public static function getImportToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->isAllowedAny( 'import', 'importupload' ) ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['import'] ) ) {
			ApiQueryInfo::$cachedTokens['import'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['import'];
	}

	public static function getWatchToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->isLoggedIn() ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['watch'] ) ) {
			ApiQueryInfo::$cachedTokens['watch'] = $wgUser->getEditToken( 'watch' );
		}

		return ApiQueryInfo::$cachedTokens['watch'];
	}

	public static function getOptionsToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->isLoggedIn() ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['options'] ) ) {
			ApiQueryInfo::$cachedTokens['options'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['options'];
	}

	public function execute() {
		$this->params = $this->extractRequestParams();
		if ( !is_null( $this->params['prop'] ) ) {
			$prop = array_flip( $this->params['prop'] );
			$this->fld_protection = isset( $prop['protection'] );
			$this->fld_watched = isset( $prop['watched'] );
			$this->fld_watchers = isset( $prop['watchers'] );
			$this->fld_notificationtimestamp = isset( $prop['notificationtimestamp'] );
			$this->fld_talkid = isset( $prop['talkid'] );
			$this->fld_subjectid = isset( $prop['subjectid'] );
			$this->fld_url = isset( $prop['url'] );
			$this->fld_readable = isset( $prop['readable'] );
			$this->fld_preload = isset( $prop['preload'] );
			$this->fld_displaytitle = isset( $prop['displaytitle'] );
		}

		$pageSet = $this->getPageSet();
		$this->titles = $pageSet->getGoodTitles();
		$this->missing = $pageSet->getMissingTitles();
		$this->everything = $this->titles + $this->missing;
		$result = $this->getResult();

		uasort( $this->everything, array( 'Title', 'compare' ) );
		if ( !is_null( $this->params['continue'] ) ) {
			// Throw away any titles we're gonna skip so they don't
			// clutter queries
			$cont = explode( '|', $this->params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$conttitle = Title::makeTitleSafe( $cont[0], $cont[1] );
			foreach ( $this->everything as $pageid => $title ) {
				if ( Title::compare( $title, $conttitle ) >= 0 ) {
					break;
				}
				unset( $this->titles[$pageid] );
				unset( $this->missing[$pageid] );
				unset( $this->everything[$pageid] );
			}
		}

		$this->pageRestrictions = $pageSet->getCustomField( 'page_restrictions' );
		// when resolving redirects, no page will have this field
		$this->pageIsRedir = !$pageSet->isResolvingRedirects()
			? $pageSet->getCustomField( 'page_is_redirect' )
			: array();
		$this->pageIsNew = $pageSet->getCustomField( 'page_is_new' );

		global $wgDisableCounters;

		if ( !$wgDisableCounters ) {
			$this->pageCounter = $pageSet->getCustomField( 'page_counter' );
		}
		$this->pageTouched = $pageSet->getCustomField( 'page_touched' );
		$this->pageLatest = $pageSet->getCustomField( 'page_latest' );
		$this->pageLength = $pageSet->getCustomField( 'page_len' );

		// Get protection info if requested
		if ( $this->fld_protection ) {
			$this->getProtectionInfo();
		}

		if ( $this->fld_watched || $this->fld_notificationtimestamp ) {
			$this->getWatchedInfo();
		}

		if ( $this->fld_watchers ) {
			$this->getWatcherInfo();
		}

		// Run the talkid/subjectid query if requested
		if ( $this->fld_talkid || $this->fld_subjectid ) {
			$this->getTSIDs();
		}

		if ( $this->fld_displaytitle ) {
			$this->getDisplayTitle();
		}

		/** @var $title Title */
		foreach ( $this->everything as $pageid => $title ) {
			$pageInfo = $this->extractPageInfo( $pageid, $title );
			$fit = $result->addValue( array(
				'query',
				'pages'
			), $pageid, $pageInfo );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue',
						$title->getNamespace() . '|' .
						$title->getText() );
				break;
			}
		}
	}

	/**
	 * Get a result array with information about a title
	 * @param int $pageid Page ID (negative for missing titles)
	 * @param $title Title object
	 * @return array
	 */
	private function extractPageInfo( $pageid, $title ) {
		$pageInfo = array();
		$titleExists = $pageid > 0; //$title->exists() needs pageid, which is not set for all title objects
		$ns = $title->getNamespace();
		$dbkey = $title->getDBkey();

		$pageInfo['contentmodel'] = $title->getContentModel();
		$pageInfo['pagelanguage'] = $title->getPageLanguage()->getCode();

		if ( $titleExists ) {
			global $wgDisableCounters;

			$pageInfo['touched'] = wfTimestamp( TS_ISO_8601, $this->pageTouched[$pageid] );
			$pageInfo['lastrevid'] = intval( $this->pageLatest[$pageid] );
			$pageInfo['counter'] = $wgDisableCounters
				? ''
				: intval( $this->pageCounter[$pageid] );
			$pageInfo['length'] = intval( $this->pageLength[$pageid] );

			if ( isset( $this->pageIsRedir[$pageid] ) && $this->pageIsRedir[$pageid] ) {
				$pageInfo['redirect'] = '';
			}
			if ( $this->pageIsNew[$pageid] ) {
				$pageInfo['new'] = '';
			}
		}

		if ( !is_null( $this->params['token'] ) ) {
			$tokenFunctions = $this->getTokenFunctions();
			$pageInfo['starttimestamp'] = wfTimestamp( TS_ISO_8601, time() );
			foreach ( $this->params['token'] as $t ) {
				$val = call_user_func( $tokenFunctions[$t], $pageid, $title );
				if ( $val === false ) {
					$this->setWarning( "Action '$t' is not allowed for the current user" );
				} else {
					$pageInfo[$t . 'token'] = $val;
				}
			}
		}

		if ( $this->fld_protection ) {
			$pageInfo['protection'] = array();
			if ( isset( $this->protections[$ns][$dbkey] ) ) {
				$pageInfo['protection'] =
					$this->protections[$ns][$dbkey];
			}
			$this->getResult()->setIndexedTagName( $pageInfo['protection'], 'pr' );
		}

		if ( $this->fld_watched && isset( $this->watched[$ns][$dbkey] ) ) {
			$pageInfo['watched'] = '';
		}

		if ( $this->fld_watchers ) {
			if ( isset( $this->watchers[$ns][$dbkey] ) ) {
				$pageInfo['watchers'] = $this->watchers[$ns][$dbkey];
			} elseif ( $this->showZeroWatchers ) {
				$pageInfo['watchers'] = 0;
			}
		}

		if ( $this->fld_notificationtimestamp ) {
			$pageInfo['notificationtimestamp'] = '';
			if ( isset( $this->notificationtimestamps[$ns][$dbkey] ) ) {
				$pageInfo['notificationtimestamp'] = wfTimestamp( TS_ISO_8601, $this->notificationtimestamps[$ns][$dbkey] );
			}
		}

		if ( $this->fld_talkid && isset( $this->talkids[$ns][$dbkey] ) ) {
			$pageInfo['talkid'] = $this->talkids[$ns][$dbkey];
		}

		if ( $this->fld_subjectid && isset( $this->subjectids[$ns][$dbkey] ) ) {
			$pageInfo['subjectid'] = $this->subjectids[$ns][$dbkey];
		}

		if ( $this->fld_url ) {
			$pageInfo['fullurl'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
			$pageInfo['editurl'] = wfExpandUrl( $title->getFullURL( 'action=edit' ), PROTO_CURRENT );
		}
		if ( $this->fld_readable && $title->userCan( 'read', $this->getUser() ) ) {
			$pageInfo['readable'] = '';
		}

		if ( $this->fld_preload ) {
			if ( $titleExists ) {
				$pageInfo['preload'] = '';
			} else {
				$text = null;
				wfRunHooks( 'EditFormPreloadText', array( &$text, &$title ) );

				$pageInfo['preload'] = $text;
			}
		}

		if ( $this->fld_displaytitle ) {
			if ( isset( $this->displaytitles[$pageid] ) ) {
				$pageInfo['displaytitle'] = $this->displaytitles[$pageid];
			} else {
				$pageInfo['displaytitle'] = $title->getPrefixedText();
			}
		}

		return $pageInfo;
	}

	/**
	 * Get information about protections and put it in $protections
	 */
	private function getProtectionInfo() {
		global $wgContLang;
		$this->protections = array();
		$db = $this->getDB();

		// Get normal protections for existing titles
		if ( count( $this->titles ) ) {
			$this->resetQueryParams();
			$this->addTables( 'page_restrictions' );
			$this->addFields( array( 'pr_page', 'pr_type', 'pr_level',
					'pr_expiry', 'pr_cascade' ) );
			$this->addWhereFld( 'pr_page', array_keys( $this->titles ) );

			$res = $this->select( __METHOD__ );
			foreach ( $res as $row ) {
				/** @var $title Title */
				$title = $this->titles[$row->pr_page];
				$a = array(
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => $wgContLang->formatExpiry( $row->pr_expiry, TS_ISO_8601 )
				);
				if ( $row->pr_cascade ) {
					$a['cascade'] = '';
				}
				$this->protections[$title->getNamespace()][$title->getDBkey()][] = $a;
			}
			// Also check old restrictions
			foreach ( $this->titles as $pageId => $title ) {
				if ( $this->pageRestrictions[$pageId] ) {
					$namespace = $title->getNamespace();
					$dbKey = $title->getDBkey();
					$restrictions = explode( ':', trim( $this->pageRestrictions[$pageId] ) );
					foreach ( $restrictions as $restrict ) {
						$temp = explode( '=', trim( $restrict ) );
						if ( count( $temp ) == 1 ) {
							// old old format should be treated as edit/move restriction
							$restriction = trim( $temp[0] );

							if ( $restriction == '' ) {
								continue;
							}
							$this->protections[$namespace][$dbKey][] = array(
								'type' => 'edit',
								'level' => $restriction,
								'expiry' => 'infinity',
							);
							$this->protections[$namespace][$dbKey][] = array(
								'type' => 'move',
								'level' => $restriction,
								'expiry' => 'infinity',
							);
						} else {
							$restriction = trim( $temp[1] );
							if ( $restriction == '' ) {
								continue;
							}
							$this->protections[$namespace][$dbKey][] = array(
								'type' => $temp[0],
								'level' => $restriction,
								'expiry' => 'infinity',
							);
						}
					}
				}
			}
		}

		// Get protections for missing titles
		if ( count( $this->missing ) ) {
			$this->resetQueryParams();
			$lb = new LinkBatch( $this->missing );
			$this->addTables( 'protected_titles' );
			$this->addFields( array( 'pt_title', 'pt_namespace', 'pt_create_perm', 'pt_expiry' ) );
			$this->addWhere( $lb->constructSet( 'pt', $db ) );
			$res = $this->select( __METHOD__ );
			foreach ( $res as $row ) {
				$this->protections[$row->pt_namespace][$row->pt_title][] = array(
					'type' => 'create',
					'level' => $row->pt_create_perm,
					'expiry' => $wgContLang->formatExpiry( $row->pt_expiry, TS_ISO_8601 )
				);
			}
		}

		// Cascading protections
		$images = $others = array();
		foreach ( $this->everything as $title ) {
			if ( $title->getNamespace() == NS_FILE ) {
				$images[] = $title->getDBkey();
			} else {
				$others[] = $title;
			}
		}

		if ( count( $others ) ) {
			// Non-images: check templatelinks
			$lb = new LinkBatch( $others );
			$this->resetQueryParams();
			$this->addTables( array( 'page_restrictions', 'page', 'templatelinks' ) );
			$this->addFields( array( 'pr_type', 'pr_level', 'pr_expiry',
					'page_title', 'page_namespace',
					'tl_title', 'tl_namespace' ) );
			$this->addWhere( $lb->constructSet( 'tl', $db ) );
			$this->addWhere( 'pr_page = page_id' );
			$this->addWhere( 'pr_page = tl_from' );
			$this->addWhereFld( 'pr_cascade', 1 );

			$res = $this->select( __METHOD__ );
			foreach ( $res as $row ) {
				$source = Title::makeTitle( $row->page_namespace, $row->page_title );
				$this->protections[$row->tl_namespace][$row->tl_title][] = array(
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => $wgContLang->formatExpiry( $row->pr_expiry, TS_ISO_8601 ),
					'source' => $source->getPrefixedText()
				);
			}
		}

		if ( count( $images ) ) {
			// Images: check imagelinks
			$this->resetQueryParams();
			$this->addTables( array( 'page_restrictions', 'page', 'imagelinks' ) );
			$this->addFields( array( 'pr_type', 'pr_level', 'pr_expiry',
					'page_title', 'page_namespace', 'il_to' ) );
			$this->addWhere( 'pr_page = page_id' );
			$this->addWhere( 'pr_page = il_from' );
			$this->addWhereFld( 'pr_cascade', 1 );
			$this->addWhereFld( 'il_to', $images );

			$res = $this->select( __METHOD__ );
			foreach ( $res as $row ) {
				$source = Title::makeTitle( $row->page_namespace, $row->page_title );
				$this->protections[NS_FILE][$row->il_to][] = array(
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => $wgContLang->formatExpiry( $row->pr_expiry, TS_ISO_8601 ),
					'source' => $source->getPrefixedText()
				);
			}
		}
	}

	/**
	 * Get talk page IDs (if requested) and subject page IDs (if requested)
	 * and put them in $talkids and $subjectids
	 */
	private function getTSIDs() {
		$getTitles = $this->talkids = $this->subjectids = array();

		/** @var $t Title */
		foreach ( $this->everything as $t ) {
			if ( MWNamespace::isTalk( $t->getNamespace() ) ) {
				if ( $this->fld_subjectid ) {
					$getTitles[] = $t->getSubjectPage();
				}
			} elseif ( $this->fld_talkid ) {
				$getTitles[] = $t->getTalkPage();
			}
		}
		if ( !count( $getTitles ) ) {
			return;
		}

		$db = $this->getDB();

		// Construct a custom WHERE clause that matches
		// all titles in $getTitles
		$lb = new LinkBatch( $getTitles );
		$this->resetQueryParams();
		$this->addTables( 'page' );
		$this->addFields( array( 'page_title', 'page_namespace', 'page_id' ) );
		$this->addWhere( $lb->constructSet( 'page', $db ) );
		$res = $this->select( __METHOD__ );
		foreach ( $res as $row ) {
			if ( MWNamespace::isTalk( $row->page_namespace ) ) {
				$this->talkids[MWNamespace::getSubject( $row->page_namespace )][$row->page_title] =
						intval( $row->page_id );
			} else {
				$this->subjectids[MWNamespace::getTalk( $row->page_namespace )][$row->page_title] =
						intval( $row->page_id );
			}
		}
	}

	private function getDisplayTitle() {
		$this->displaytitles = array();

		$pageIds = array_keys( $this->titles );

		if ( !count( $pageIds ) ) {
			return;
		}

		$this->resetQueryParams();
		$this->addTables( 'page_props' );
		$this->addFields( array( 'pp_page', 'pp_value' ) );
		$this->addWhereFld( 'pp_page', $pageIds );
		$this->addWhereFld( 'pp_propname', 'displaytitle' );
		$res = $this->select( __METHOD__ );

		foreach ( $res as $row ) {
			$this->displaytitles[$row->pp_page] = $row->pp_value;
		}
	}

	/**
	 * Get information about watched status and put it in $this->watched
	 * and $this->notificationtimestamps
	 */
	private function getWatchedInfo() {
		$user = $this->getUser();

		if ( $user->isAnon() || count( $this->everything ) == 0
			|| !$user->isAllowed( 'viewmywatchlist' )
		) {
			return;
		}

		$this->watched = array();
		$this->notificationtimestamps = array();
		$db = $this->getDB();

		$lb = new LinkBatch( $this->everything );

		$this->resetQueryParams();
		$this->addTables( array( 'watchlist' ) );
		$this->addFields( array( 'wl_title', 'wl_namespace' ) );
		$this->addFieldsIf( 'wl_notificationtimestamp', $this->fld_notificationtimestamp );
		$this->addWhere( array(
			$lb->constructSet( 'wl', $db ),
			'wl_user' => $user->getID()
		) );

		$res = $this->select( __METHOD__ );

		foreach ( $res as $row ) {
			if ( $this->fld_watched ) {
				$this->watched[$row->wl_namespace][$row->wl_title] = true;
			}
			if ( $this->fld_notificationtimestamp ) {
				$this->notificationtimestamps[$row->wl_namespace][$row->wl_title] = $row->wl_notificationtimestamp;
			}
		}
	}

	/**
	 * Get the count of watchers and put it in $this->watchers
	 */
	private function getWatcherInfo() {
		global $wgUnwatchedPageThreshold;

		if ( count( $this->everything ) == 0 ) {
			return;
		}

		$user = $this->getUser();
		$canUnwatchedpages = $user->isAllowed( 'unwatchedpages' );
		if ( !$canUnwatchedpages && !is_int( $wgUnwatchedPageThreshold ) ) {
			return;
		}

		$this->watchers = array();
		$this->showZeroWatchers = $canUnwatchedpages;
		$db = $this->getDB();

		$lb = new LinkBatch( $this->everything );

		$this->resetQueryParams();
		$this->addTables( array( 'watchlist' ) );
		$this->addFields( array( 'wl_title', 'wl_namespace', 'count' => 'COUNT(*)' ) );
		$this->addWhere( array(
			$lb->constructSet( 'wl', $db )
		) );
		$this->addOption( 'GROUP BY', array( 'wl_namespace', 'wl_title' ) );
		if ( !$canUnwatchedpages ) {
			$this->addOption( 'HAVING', "COUNT(*) >= $wgUnwatchedPageThreshold" );
		}

		$res = $this->select( __METHOD__ );

		foreach ( $res as $row ) {
			$this->watchers[$row->wl_namespace][$row->wl_title] = (int)$row->count;
		}
	}

	public function getCacheMode( $params ) {
		$publicProps = array(
			'protection',
			'talkid',
			'subjectid',
			'url',
			'preload',
			'displaytitle',
		);
		if ( !is_null( $params['prop'] ) ) {
			foreach ( $params['prop'] as $prop ) {
				if ( !in_array( $prop, $publicProps ) ) {
					return 'private';
				}
			}
		}
		if ( !is_null( $params['token'] ) ) {
			return 'private';
		}
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'protection',
					'talkid',
					'watched', # private
					'watchers', # private
					'notificationtimestamp', # private
					'subjectid',
					'url',
					'readable', # private
					'preload',
					'displaytitle',
					// If you add more properties here, please consider whether they
					// need to be added to getCacheMode()
				) ),
			'token' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() )
			),
			'continue' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'prop' => array(
				'Which additional properties to get:',
				' protection            - List the protection level of each page',
				' talkid                - The page ID of the talk page for each non-talk page',
				' watched               - List the watched status of each page',
				' watchers              - The number of watchers, if allowed',
				' notificationtimestamp - The watchlist notification timestamp of each page',
				' subjectid             - The page ID of the parent page for each talk page',
				' url                   - Gives a full URL to the page, and also an edit URL',
				' readable              - Whether the user can read this page',
				' preload               - Gives the text returned by EditFormPreloadText',
				' displaytitle          - Gives the way the page title is actually displayed',
			),
			'token' => 'Request a token to perform a data-modifying action on a page',
			'continue' => 'When more results are available, use this to continue',
		);
	}

	public function getResultProperties() {
		$props = array(
			ApiBase::PROP_LIST => false,
			'' => array(
				'touched' => 'timestamp',
				'lastrevid' => 'integer',
				'counter' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'length' => 'integer',
				'redirect' => 'boolean',
				'new' => 'boolean',
				'starttimestamp' => array(
					ApiBase::PROP_TYPE => 'timestamp',
					ApiBase::PROP_NULLABLE => true
				),
				'contentmodel' => 'string',
			),
			'watched' => array(
				'watched' => 'boolean'
			),
			'watchers' => array(
				'watchers' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'notificationtimestamp' => array(
				'notificationtimestamp' => array(
					ApiBase::PROP_TYPE => 'timestamp',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'talkid' => array(
				'talkid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'subjectid' => array(
				'subjectid' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'url' => array(
				'fullurl' => 'string',
				'editurl' => 'string'
			),
			'readable' => array(
				'readable' => 'boolean'
			),
			'preload' => array(
				'preload' => 'string'
			),
			'displaytitle' => array(
				'displaytitle' => 'string'
			)
		);

		self::addTokenProperties( $props, $this->getTokenFunctions() );

		return $props;
	}

	public function getDescription() {
		return 'Get basic page information such as namespace, title, last touched date, ...';
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=info&titles=Main%20Page',
			'api.php?action=query&prop=info&inprop=protection&titles=Main%20Page'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Properties#info_.2F_in';
	}
}
