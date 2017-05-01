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
use MediaWiki\MediaWikiServices;
use MediaWiki\Linker\LinkTarget;

/**
 * A query module to show basic page information.
 *
 * @ingroup API
 */
class ApiQueryInfo extends ApiQueryBase {

	private $fld_protection = false, $fld_talkid = false,
		$fld_subjectid = false, $fld_url = false,
		$fld_readable = false, $fld_watched = false,
		$fld_watchers = false, $fld_visitingwatchers = false,
		$fld_notificationtimestamp = false,
		$fld_preload = false, $fld_displaytitle = false;

	private $params;

	/** @var Title[] */
	private $titles;
	/** @var Title[] */
	private $missing;
	/** @var Title[] */
	private $everything;

	private $pageRestrictions, $pageIsRedir, $pageIsNew, $pageTouched,
		$pageLatest, $pageLength;

	private $protections, $restrictionTypes, $watched, $watchers, $visitingwatchers,
		$notificationtimestamps, $talkids, $subjectids, $displaytitles;
	private $showZeroWatchers = false;

	private $tokenFunctions;

	private $countTestedActions = 0;

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'in' );
	}

	/**
	 * @param ApiPageSet $pageSet
	 * @return void
	 */
	public function requestExtraData( $pageSet ) {
		$pageSet->requestField( 'page_restrictions' );
		// If the pageset is resolving redirects we won't get page_is_redirect.
		// But we can't know for sure until the pageset is executed (revids may
		// turn it off), so request it unconditionally.
		$pageSet->requestField( 'page_is_redirect' );
		$pageSet->requestField( 'page_is_new' );
		$config = $this->getConfig();
		$pageSet->requestField( 'page_touched' );
		$pageSet->requestField( 'page_latest' );
		$pageSet->requestField( 'page_len' );
		if ( $config->get( 'ContentHandlerUseDB' ) ) {
			$pageSet->requestField( 'page_content_model' );
		}
		if ( $config->get( 'PageLanguageUseDB' ) ) {
			$pageSet->requestField( 'page_lang' );
		}
	}

	/**
	 * Get an array mapping token names to their handler functions.
	 * The prototype for a token function is func($pageid, $title)
	 * it should return a token or false (permission denied)
	 * @deprecated since 1.24
	 * @return array [ tokenname => function ]
	 */
	protected function getTokenFunctions() {
		// Don't call the hooks twice
		if ( isset( $this->tokenFunctions ) ) {
			return $this->tokenFunctions;
		}

		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ) {
			return [];
		}

		$this->tokenFunctions = [
			'edit' => [ 'ApiQueryInfo', 'getEditToken' ],
			'delete' => [ 'ApiQueryInfo', 'getDeleteToken' ],
			'protect' => [ 'ApiQueryInfo', 'getProtectToken' ],
			'move' => [ 'ApiQueryInfo', 'getMoveToken' ],
			'block' => [ 'ApiQueryInfo', 'getBlockToken' ],
			'unblock' => [ 'ApiQueryInfo', 'getUnblockToken' ],
			'email' => [ 'ApiQueryInfo', 'getEmailToken' ],
			'import' => [ 'ApiQueryInfo', 'getImportToken' ],
			'watch' => [ 'ApiQueryInfo', 'getWatchToken' ],
		];
		Hooks::run( 'APIQueryInfoTokens', [ &$this->tokenFunctions ] );

		return $this->tokenFunctions;
	}

	static protected $cachedTokens = [];

	/**
	 * @deprecated since 1.24
	 */
	public static function resetTokenCache() {
		ApiQueryInfo::$cachedTokens = [];
	}

	/**
	 * @deprecated since 1.24
	 */
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

	/**
	 * @deprecated since 1.24
	 */
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

	/**
	 * @deprecated since 1.24
	 */
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

	/**
	 * @deprecated since 1.24
	 */
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

	/**
	 * @deprecated since 1.24
	 */
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

	/**
	 * @deprecated since 1.24
	 */
	public static function getUnblockToken( $pageid, $title ) {
		// Currently, this is exactly the same as the block token
		return self::getBlockToken( $pageid, $title );
	}

	/**
	 * @deprecated since 1.24
	 */
	public static function getEmailToken( $pageid, $title ) {
		global $wgUser;
		if ( !$wgUser->canSendEmail() || $wgUser->isBlockedFromEmailuser() ) {
			return false;
		}

		// The token is always the same, let's exploit that
		if ( !isset( ApiQueryInfo::$cachedTokens['email'] ) ) {
			ApiQueryInfo::$cachedTokens['email'] = $wgUser->getEditToken();
		}

		return ApiQueryInfo::$cachedTokens['email'];
	}

	/**
	 * @deprecated since 1.24
	 */
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

	/**
	 * @deprecated since 1.24
	 */
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

	/**
	 * @deprecated since 1.24
	 */
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
			$this->fld_visitingwatchers = isset( $prop['visitingwatchers'] );
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

		uasort( $this->everything, [ 'Title', 'compare' ] );
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
			: [];
		$this->pageIsNew = $pageSet->getCustomField( 'page_is_new' );

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

		if ( $this->fld_visitingwatchers ) {
			$this->getVisitingWatcherInfo();
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
			$fit = $pageInfo !== null && $result->addValue( [
				'query',
				'pages'
			], $pageid, $pageInfo );
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
	 * @param Title $title
	 * @return array|null
	 */
	private function extractPageInfo( $pageid, $title ) {
		$pageInfo = [];
		// $title->exists() needs pageid, which is not set for all title objects
		$titleExists = $pageid > 0;
		$ns = $title->getNamespace();
		$dbkey = $title->getDBkey();

		$pageInfo['contentmodel'] = $title->getContentModel();

		$pageLanguage = $title->getPageLanguage();
		$pageInfo['pagelanguage'] = $pageLanguage->getCode();
		$pageInfo['pagelanguagehtmlcode'] = $pageLanguage->getHtmlCode();
		$pageInfo['pagelanguagedir'] = $pageLanguage->getDir();

		if ( $titleExists ) {
			$pageInfo['touched'] = wfTimestamp( TS_ISO_8601, $this->pageTouched[$pageid] );
			$pageInfo['lastrevid'] = intval( $this->pageLatest[$pageid] );
			$pageInfo['length'] = intval( $this->pageLength[$pageid] );

			if ( isset( $this->pageIsRedir[$pageid] ) && $this->pageIsRedir[$pageid] ) {
				$pageInfo['redirect'] = true;
			}
			if ( $this->pageIsNew[$pageid] ) {
				$pageInfo['new'] = true;
			}
		}

		if ( !is_null( $this->params['token'] ) ) {
			$tokenFunctions = $this->getTokenFunctions();
			$pageInfo['starttimestamp'] = wfTimestamp( TS_ISO_8601, time() );
			foreach ( $this->params['token'] as $t ) {
				$val = call_user_func( $tokenFunctions[$t], $pageid, $title );
				if ( $val === false ) {
					$this->addWarning( [ 'apiwarn-tokennotallowed', $t ] );
				} else {
					$pageInfo[$t . 'token'] = $val;
				}
			}
		}

		if ( $this->fld_protection ) {
			$pageInfo['protection'] = [];
			if ( isset( $this->protections[$ns][$dbkey] ) ) {
				$pageInfo['protection'] =
					$this->protections[$ns][$dbkey];
			}
			ApiResult::setIndexedTagName( $pageInfo['protection'], 'pr' );

			$pageInfo['restrictiontypes'] = [];
			if ( isset( $this->restrictionTypes[$ns][$dbkey] ) ) {
				$pageInfo['restrictiontypes'] =
					$this->restrictionTypes[$ns][$dbkey];
			}
			ApiResult::setIndexedTagName( $pageInfo['restrictiontypes'], 'rt' );
		}

		if ( $this->fld_watched && $this->watched !== null ) {
			$pageInfo['watched'] = $this->watched[$ns][$dbkey];
		}

		if ( $this->fld_watchers ) {
			if ( $this->watchers !== null && $this->watchers[$ns][$dbkey] !== 0 ) {
				$pageInfo['watchers'] = $this->watchers[$ns][$dbkey];
			} elseif ( $this->showZeroWatchers ) {
				$pageInfo['watchers'] = 0;
			}
		}

		if ( $this->fld_visitingwatchers ) {
			if ( $this->visitingwatchers !== null && $this->visitingwatchers[$ns][$dbkey] !== 0 ) {
				$pageInfo['visitingwatchers'] = $this->visitingwatchers[$ns][$dbkey];
			} elseif ( $this->showZeroWatchers ) {
				$pageInfo['visitingwatchers'] = 0;
			}
		}

		if ( $this->fld_notificationtimestamp ) {
			$pageInfo['notificationtimestamp'] = '';
			if ( $this->notificationtimestamps[$ns][$dbkey] ) {
				$pageInfo['notificationtimestamp'] =
					wfTimestamp( TS_ISO_8601, $this->notificationtimestamps[$ns][$dbkey] );
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
			$pageInfo['canonicalurl'] = wfExpandUrl( $title->getFullURL(), PROTO_CANONICAL );
		}
		if ( $this->fld_readable ) {
			$pageInfo['readable'] = $title->userCan( 'read', $this->getUser() );
		}

		if ( $this->fld_preload ) {
			if ( $titleExists ) {
				$pageInfo['preload'] = '';
			} else {
				$text = null;
				Hooks::run( 'EditFormPreloadText', [ &$text, &$title ] );

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

		if ( $this->params['testactions'] ) {
			$limit = $this->getMain()->canApiHighLimits() ? self::LIMIT_SML1 : self::LIMIT_SML2;
			if ( $this->countTestedActions >= $limit ) {
				return null; // force a continuation
			}

			$user = $this->getUser();
			$pageInfo['actions'] = [];
			foreach ( $this->params['testactions'] as $action ) {
				$this->countTestedActions++;
				$pageInfo['actions'][$action] = $title->userCan( $action, $user );
			}
		}

		return $pageInfo;
	}

	/**
	 * Get information about protections and put it in $protections
	 */
	private function getProtectionInfo() {
		$this->protections = [];
		$db = $this->getDB();

		// Get normal protections for existing titles
		if ( count( $this->titles ) ) {
			$this->resetQueryParams();
			$this->addTables( 'page_restrictions' );
			$this->addFields( [ 'pr_page', 'pr_type', 'pr_level',
				'pr_expiry', 'pr_cascade' ] );
			$this->addWhereFld( 'pr_page', array_keys( $this->titles ) );

			$res = $this->select( __METHOD__ );
			foreach ( $res as $row ) {
				/** @var $title Title */
				$title = $this->titles[$row->pr_page];
				$a = [
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => ApiResult::formatExpiry( $row->pr_expiry )
				];
				if ( $row->pr_cascade ) {
					$a['cascade'] = true;
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
							$this->protections[$namespace][$dbKey][] = [
								'type' => 'edit',
								'level' => $restriction,
								'expiry' => 'infinity',
							];
							$this->protections[$namespace][$dbKey][] = [
								'type' => 'move',
								'level' => $restriction,
								'expiry' => 'infinity',
							];
						} else {
							$restriction = trim( $temp[1] );
							if ( $restriction == '' ) {
								continue;
							}
							$this->protections[$namespace][$dbKey][] = [
								'type' => $temp[0],
								'level' => $restriction,
								'expiry' => 'infinity',
							];
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
			$this->addFields( [ 'pt_title', 'pt_namespace', 'pt_create_perm', 'pt_expiry' ] );
			$this->addWhere( $lb->constructSet( 'pt', $db ) );
			$res = $this->select( __METHOD__ );
			foreach ( $res as $row ) {
				$this->protections[$row->pt_namespace][$row->pt_title][] = [
					'type' => 'create',
					'level' => $row->pt_create_perm,
					'expiry' => ApiResult::formatExpiry( $row->pt_expiry )
				];
			}
		}

		// Separate good and missing titles into files and other pages
		// and populate $this->restrictionTypes
		$images = $others = [];
		foreach ( $this->everything as $title ) {
			if ( $title->getNamespace() == NS_FILE ) {
				$images[] = $title->getDBkey();
			} else {
				$others[] = $title;
			}
			// Applicable protection types
			$this->restrictionTypes[$title->getNamespace()][$title->getDBkey()] =
				array_values( $title->getRestrictionTypes() );
		}

		if ( count( $others ) ) {
			// Non-images: check templatelinks
			$lb = new LinkBatch( $others );
			$this->resetQueryParams();
			$this->addTables( [ 'page_restrictions', 'page', 'templatelinks' ] );
			$this->addFields( [ 'pr_type', 'pr_level', 'pr_expiry',
				'page_title', 'page_namespace',
				'tl_title', 'tl_namespace' ] );
			$this->addWhere( $lb->constructSet( 'tl', $db ) );
			$this->addWhere( 'pr_page = page_id' );
			$this->addWhere( 'pr_page = tl_from' );
			$this->addWhereFld( 'pr_cascade', 1 );

			$res = $this->select( __METHOD__ );
			foreach ( $res as $row ) {
				$source = Title::makeTitle( $row->page_namespace, $row->page_title );
				$this->protections[$row->tl_namespace][$row->tl_title][] = [
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => ApiResult::formatExpiry( $row->pr_expiry ),
					'source' => $source->getPrefixedText()
				];
			}
		}

		if ( count( $images ) ) {
			// Images: check imagelinks
			$this->resetQueryParams();
			$this->addTables( [ 'page_restrictions', 'page', 'imagelinks' ] );
			$this->addFields( [ 'pr_type', 'pr_level', 'pr_expiry',
				'page_title', 'page_namespace', 'il_to' ] );
			$this->addWhere( 'pr_page = page_id' );
			$this->addWhere( 'pr_page = il_from' );
			$this->addWhereFld( 'pr_cascade', 1 );
			$this->addWhereFld( 'il_to', $images );

			$res = $this->select( __METHOD__ );
			foreach ( $res as $row ) {
				$source = Title::makeTitle( $row->page_namespace, $row->page_title );
				$this->protections[NS_FILE][$row->il_to][] = [
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => ApiResult::formatExpiry( $row->pr_expiry ),
					'source' => $source->getPrefixedText()
				];
			}
		}
	}

	/**
	 * Get talk page IDs (if requested) and subject page IDs (if requested)
	 * and put them in $talkids and $subjectids
	 */
	private function getTSIDs() {
		$getTitles = $this->talkids = $this->subjectids = [];

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
		$this->addFields( [ 'page_title', 'page_namespace', 'page_id' ] );
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
		$this->displaytitles = [];

		$pageIds = array_keys( $this->titles );

		if ( !count( $pageIds ) ) {
			return;
		}

		$this->resetQueryParams();
		$this->addTables( 'page_props' );
		$this->addFields( [ 'pp_page', 'pp_value' ] );
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

		$this->watched = [];
		$this->notificationtimestamps = [];

		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$timestamps = $store->getNotificationTimestampsBatch( $user, $this->everything );

		if ( $this->fld_watched ) {
			foreach ( $timestamps as $namespaceId => $dbKeys ) {
				$this->watched[$namespaceId] = array_map(
					function( $x ) {
						return $x !== false;
					},
					$dbKeys
				);
			}
		}
		if ( $this->fld_notificationtimestamp ) {
			$this->notificationtimestamps = $timestamps;
		}
	}

	/**
	 * Get the count of watchers and put it in $this->watchers
	 */
	private function getWatcherInfo() {
		if ( count( $this->everything ) == 0 ) {
			return;
		}

		$user = $this->getUser();
		$canUnwatchedpages = $user->isAllowed( 'unwatchedpages' );
		$unwatchedPageThreshold = $this->getConfig()->get( 'UnwatchedPageThreshold' );
		if ( !$canUnwatchedpages && !is_int( $unwatchedPageThreshold ) ) {
			return;
		}

		$this->showZeroWatchers = $canUnwatchedpages;

		$countOptions = [];
		if ( !$canUnwatchedpages ) {
			$countOptions['minimumWatchers'] = $unwatchedPageThreshold;
		}

		$this->watchers = MediaWikiServices::getInstance()->getWatchedItemStore()->countWatchersMultiple(
			$this->everything,
			$countOptions
		);
	}

	/**
	 * Get the count of watchers who have visited recent edits and put it in
	 * $this->visitingwatchers
	 *
	 * Based on InfoAction::pageCounts
	 */
	private function getVisitingWatcherInfo() {
		$config = $this->getConfig();
		$user = $this->getUser();
		$db = $this->getDB();

		$canUnwatchedpages = $user->isAllowed( 'unwatchedpages' );
		$unwatchedPageThreshold = $this->getConfig()->get( 'UnwatchedPageThreshold' );
		if ( !$canUnwatchedpages && !is_int( $unwatchedPageThreshold ) ) {
			return;
		}

		$this->showZeroWatchers = $canUnwatchedpages;

		$titlesWithThresholds = [];
		if ( $this->titles ) {
			$lb = new LinkBatch( $this->titles );

			// Fetch last edit timestamps for pages
			$this->resetQueryParams();
			$this->addTables( [ 'page', 'revision' ] );
			$this->addFields( [ 'page_namespace', 'page_title', 'rev_timestamp' ] );
			$this->addWhere( [
				'page_latest = rev_id',
				$lb->constructSet( 'page', $db ),
			] );
			$this->addOption( 'GROUP BY', [ 'page_namespace', 'page_title' ] );
			$timestampRes = $this->select( __METHOD__ );

			$age = $config->get( 'WatchersMaxAge' );
			$timestamps = [];
			foreach ( $timestampRes as $row ) {
				$revTimestamp = wfTimestamp( TS_UNIX, (int)$row->rev_timestamp );
				$timestamps[$row->page_namespace][$row->page_title] = $revTimestamp - $age;
			}
			$titlesWithThresholds = array_map(
				function( LinkTarget $target ) use ( $timestamps ) {
					return [
						$target, $timestamps[$target->getNamespace()][$target->getDBkey()]
					];
				},
				$this->titles
			);
		}

		if ( $this->missing ) {
			$titlesWithThresholds = array_merge(
				$titlesWithThresholds,
				array_map(
					function( LinkTarget $target ) {
						return [ $target, null ];
					},
					$this->missing
				)
			);
		}
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$this->visitingwatchers = $store->countVisitingWatchersMultiple(
			$titlesWithThresholds,
			!$canUnwatchedpages ? $unwatchedPageThreshold : null
		);
	}

	public function getCacheMode( $params ) {
		// Other props depend on something about the current user
		$publicProps = [
			'protection',
			'talkid',
			'subjectid',
			'url',
			'preload',
			'displaytitle',
		];
		if ( array_diff( (array)$params['prop'], $publicProps ) ) {
			return 'private';
		}

		// testactions also depends on the current user
		if ( $params['testactions'] ) {
			return 'private';
		}

		if ( !is_null( $params['token'] ) ) {
			return 'private';
		}

		return 'public';
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'protection',
					'talkid',
					'watched', # private
					'watchers', # private
					'visitingwatchers', # private
					'notificationtimestamp', # private
					'subjectid',
					'url',
					'readable', # private
					'preload',
					'displaytitle',
					// If you add more properties here, please consider whether they
					// need to be added to getCacheMode()
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'testactions' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_ISMULTI => true,
			],
			'token' => [
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_keys( $this->getTokenFunctions() )
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=info&titles=Main%20Page'
				=> 'apihelp-query+info-example-simple',
			'action=query&prop=info&inprop=protection&titles=Main%20Page'
				=> 'apihelp-query+info-example-protection',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Info';
	}
}
