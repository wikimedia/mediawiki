<?php

/**
 * Created on Sep 25, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

/**
 * A query action to return meta information about the wiki site.
 *
 * @ingroup API
 */
class ApiQuerySiteinfo extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'si' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$done = array();
		foreach ( $params['prop'] as $p ) {
			switch ( $p ) {
				case 'general':
					$fit = $this->appendGeneralInfo( $p );
					break;
				case 'namespaces':
					$fit = $this->appendNamespaces( $p );
					break;
				case 'namespacealiases':
					$fit = $this->appendNamespaceAliases( $p );
					break;
				case 'specialpagealiases':
					$fit = $this->appendSpecialPageAliases( $p );
					break;
				case 'magicwords':
					$fit = $this->appendMagicWords( $p );
					break;
				case 'interwikimap':
					$filteriw = isset( $params['filteriw'] ) ? $params['filteriw'] : false;
					$fit = $this->appendInterwikiMap( $p, $filteriw );
					break;
				case 'dbrepllag':
					$fit = $this->appendDbReplLagInfo( $p, $params['showalldb'] );
					break;
				case 'statistics':
					$fit = $this->appendStatistics( $p );
					break;
				case 'usergroups':
					$fit = $this->appendUserGroups( $p, $params['numberingroup'] );
					break;
				case 'extensions':
					$fit = $this->appendExtensions( $p );
					break;
				case 'fileextensions':
					$fit = $this->appendFileExtensions( $p );
					break;
				case 'rightsinfo':
					$fit = $this->appendRightsInfo( $p );
					break;
				case 'languages':
					$fit = $this->appendLanguages( $p );
					break;
				default:
					ApiBase::dieDebug( __METHOD__, "Unknown prop=$p" );
			}
			if ( !$fit ) {
				// Abuse siprop as a query-continue parameter
				// and set it to all unprocessed props
				$this->setContinueEnumParameter( 'prop', implode( '|',
						array_diff( $params['prop'], $done ) ) );
				break;
			}
			$done[] = $p;
		}
	}

	protected function appendGeneralInfo( $property ) {
		global $wgContLang;

		$data = array();
		$mainPage = Title::newMainPage();
		$data['mainpage'] = $mainPage->getPrefixedText();
		$data['base'] = $mainPage->getFullUrl();
		$data['sitename'] = $GLOBALS['wgSitename'];
		$data['generator'] = "MediaWiki {$GLOBALS['wgVersion']}";
		$data['phpversion'] = phpversion();
		$data['phpsapi'] = php_sapi_name();
		$data['dbtype'] = $GLOBALS['wgDBtype'];
		$data['dbversion'] = $this->getDB()->getServerVersion();

		$svn = SpecialVersion::getSvnRevision( $GLOBALS['IP'] );
		if ( $svn ) {
			$data['rev'] = $svn;
		}

		// 'case-insensitive' option is reserved for future
		$data['case'] = $GLOBALS['wgCapitalLinks'] ? 'first-letter' : 'case-sensitive';

		if ( isset( $GLOBALS['wgRightsCode'] ) ) {
			$data['rightscode'] = $GLOBALS['wgRightsCode'];
		}
		$data['rights'] = $GLOBALS['wgRightsText'];
		$data['lang'] = $GLOBALS['wgLanguageCode'];
		if ( $wgContLang->isRTL() ) {
			$data['rtl'] = '';
		}
		$data['fallback8bitEncoding'] = $wgContLang->fallback8bitEncoding();

		if ( wfReadOnly() ) {
			$data['readonly'] = '';
			$data['readonlyreason'] = wfReadOnlyReason();
		}
		if ( $GLOBALS['wgEnableWriteAPI'] ) {
			$data['writeapi'] = '';
		}

		$tz = $GLOBALS['wgLocaltimezone'];
		$offset = $GLOBALS['wgLocalTZoffset'];
		if ( is_null( $tz ) ) {
			$tz = 'UTC';
			$offset = 0;
		} elseif ( is_null( $offset ) ) {
			$offset = 0;
		}
		$data['timezone'] = $tz;
		$data['timeoffset'] = intval( $offset );
		$data['articlepath'] = $GLOBALS['wgArticlePath'];
		$data['scriptpath'] = $GLOBALS['wgScriptPath'];
		$data['script'] = $GLOBALS['wgScript'];
		$data['variantarticlepath'] = $GLOBALS['wgVariantArticlePath'];
		$data['server'] = $GLOBALS['wgServer'];
		$data['wikiid'] = wfWikiID();
		$data['time'] = wfTimestamp( TS_ISO_8601, time() );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendNamespaces( $property ) {
		global $wgContLang;
		$data = array();
		foreach ( $wgContLang->getFormattedNamespaces() as $ns => $title ) {
			$data[$ns] = array(
				'id' => intval( $ns ),
				'case' => MWNamespace::isCapitalized( $ns ) ? 'first-letter' : 'case-sensitive',
			);
			ApiResult::setContent( $data[$ns], $title );
			$canonical = MWNamespace::getCanonicalName( $ns );

			if ( MWNamespace::hasSubpages( $ns ) ) {
				$data[$ns]['subpages'] = '';
			}

			if ( $canonical ) {
				$data[$ns]['canonical'] = strtr( $canonical, '_', ' ' );
			}

			if ( MWNamespace::isContent( $ns ) ) {
				$data[$ns]['content'] = '';
			}
		}

		$this->getResult()->setIndexedTagName( $data, 'ns' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendNamespaceAliases( $property ) {
		global $wgNamespaceAliases, $wgContLang;
		$aliases = array_merge( $wgNamespaceAliases, $wgContLang->getNamespaceAliases() );
		$namespaces = $wgContLang->getNamespaces();
		$data = array();
		foreach ( $aliases as $title => $ns ) {
			if ( $namespaces[$ns] == $title ) {
				// Don't list duplicates
				continue;
			}
			$item = array(
				'id' => intval( $ns )
			);
			ApiResult::setContent( $item, strtr( $title, '_', ' ' ) );
			$data[] = $item;
		}

		$this->getResult()->setIndexedTagName( $data, 'ns' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendSpecialPageAliases( $property ) {
		global $wgContLang;
		$data = array();
		foreach ( $wgContLang->getSpecialPageAliases() as $specialpage => $aliases )
		{
			$arr = array( 'realname' => $specialpage, 'aliases' => $aliases );
			$this->getResult()->setIndexedTagName( $arr['aliases'], 'alias' );
			$data[] = $arr;
		}
		$this->getResult()->setIndexedTagName( $data, 'specialpage' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendMagicWords( $property ) {
		global $wgContLang;
		$data = array();
		foreach ( $wgContLang->getMagicWords() as $magicword => $aliases ) {
			$caseSensitive = array_shift( $aliases );
			$arr = array( 'name' => $magicword, 'aliases' => $aliases );
			if ( $caseSensitive ) {
				$arr['case-sensitive'] = '';
			}
			$this->getResult()->setIndexedTagName( $arr['aliases'], 'alias' );
			$data[] = $arr;
		}
		$this->getResult()->setIndexedTagName( $data, 'magicword' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendInterwikiMap( $property, $filter ) {
		$this->resetQueryParams();
		$this->addTables( 'interwiki' );
		$this->addFields( array( 'iw_prefix', 'iw_local', 'iw_url' ) );

		if ( $filter === 'local' ) {
			$this->addWhere( 'iw_local = 1' );
		} elseif ( $filter === '!local' ) {
			$this->addWhere( 'iw_local = 0' );
		} elseif ( $filter ) {
			ApiBase::dieDebug( __METHOD__, "Unknown filter=$filter" );
		}

		$this->addOption( 'ORDER BY', 'iw_prefix' );

		$res = $this->select( __METHOD__ );

		$data = array();
		$langNames = Language::getLanguageNames();
		foreach ( $res as $row ) {
			$val = array();
			$val['prefix'] = $row->iw_prefix;
			if ( $row->iw_local == '1' ) {
				$val['local'] = '';
			}
			// $val['trans'] = intval( $row->iw_trans ); // should this be exposed?
			if ( isset( $langNames[$row->iw_prefix] ) ) {
				$val['language'] = $langNames[$row->iw_prefix];
			}
			$val['url'] = $row->iw_url;

			$data[] = $val;
		}

		$this->getResult()->setIndexedTagName( $data, 'iw' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendDbReplLagInfo( $property, $includeAll ) {
		global $wgShowHostnames;
		$data = array();
		if ( $includeAll ) {
			if ( !$wgShowHostnames ) {
				$this->dieUsage( 'Cannot view all servers info unless $wgShowHostnames is true', 'includeAllDenied' );
			}

			$lb = wfGetLB();
			$lags = $lb->getLagTimes();
			foreach ( $lags as $i => $lag ) {
				$data[] = array(
					'host' => $lb->getServerName( $i ),
					'lag' => $lag
				);
			}
		} else {
			list( $host, $lag ) = wfGetLB()->getMaxLag();
			$data[] = array(
				'host' => $wgShowHostnames ? $host : '',
				'lag' => intval( $lag )
			);
		}

		$result = $this->getResult();
		$result->setIndexedTagName( $data, 'db' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendStatistics( $property ) {
		global $wgDisableCounters;
		$data = array();
		$data['pages'] = intval( SiteStats::pages() );
		$data['articles'] = intval( SiteStats::articles() );
		if ( !$wgDisableCounters ) {
			$data['views'] = intval( SiteStats::views() );
		}
		$data['edits'] = intval( SiteStats::edits() );
		$data['images'] = intval( SiteStats::images() );
		$data['users'] = intval( SiteStats::users() );
		$data['activeusers'] = intval( SiteStats::activeUsers() );
		$data['admins'] = intval( SiteStats::numberingroup( 'sysop' ) );
		$data['jobs'] = intval( SiteStats::jobs() );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendUserGroups( $property, $numberInGroup ) {
		global $wgGroupPermissions;
		$data = array();
		foreach ( $wgGroupPermissions as $group => $permissions ) {
			$arr = array(
				'name' => $group,
				'rights' => array_keys( $permissions, true ),
			);
			if ( $numberInGroup ) {
				$arr['number'] = SiteStats::numberInGroup( $group );
			}

			$this->getResult()->setIndexedTagName( $arr['rights'], 'permission' );
			$data[] = $arr;
		}

		$this->getResult()->setIndexedTagName( $data, 'group' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendFileExtensions( $property ) {
		global $wgFileExtensions;

		$data = array();
		foreach ( $wgFileExtensions as $ext ) {
			$data[] = array( 'ext' => $ext );
		}
		$this->getResult()->setIndexedTagName( $data, 'fe' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendExtensions( $property ) {
		global $wgExtensionCredits;
		$data = array();
		foreach ( $wgExtensionCredits as $type => $extensions ) {
			foreach ( $extensions as $ext ) {
				$ret = array();
				$ret['type'] = $type;
				if ( isset( $ext['name'] ) ) {
					$ret['name'] = $ext['name'];
				}
				if ( isset( $ext['description'] ) ) {
					$ret['description'] = $ext['description'];
				}
				if ( isset( $ext['descriptionmsg'] ) ) {
					// Can be a string or array( key, param1, param2, ... )
					if ( is_array( $ext['descriptionmsg'] ) ) {
						$ret['descriptionmsg'] = $ext['descriptionmsg'][0];
						$ret['descriptionmsgparams'] = array_slice( $ext['descriptionmsg'], 1 );
						$this->getResult()->setIndexedTagName( $ret['descriptionmsgparams'], 'param' );
					} else {
						$ret['descriptionmsg'] = $ext['descriptionmsg'];
					}
				}
				if ( isset( $ext['author'] ) ) {
					$ret['author'] = is_array( $ext['author'] ) ?
						implode( ', ', $ext['author' ] ) : $ext['author'];
				}
				if ( isset( $ext['url'] ) ) {
					$ret['url'] = $ext['url'];
				}
				if ( isset( $ext['version'] ) ) {
						$ret['version'] = $ext['version'];
				} elseif ( isset( $ext['svn-revision'] ) &&
					preg_match( '/\$(?:Rev|LastChangedRevision|Revision): *(\d+)/',
						$ext['svn-revision'], $m ) )
				{
						$ret['version'] = 'r' . $m[1];
				}
				$data[] = $ret;
			}
		}

		$this->getResult()->setIndexedTagName( $data, 'ext' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}


	protected function appendRightsInfo( $property ) {
		global $wgRightsPage, $wgRightsUrl, $wgRightsText;
		$title = Title::newFromText( $wgRightsPage );
		$url = $title ? $title->getFullURL() : $wgRightsUrl;
		$text = $wgRightsText;
		if ( !$text && $title ) {
			$text = $title->getPrefixedText();
		}

		$data = array(
			'url' => $url ? $url : '',
			'text' => $text ?  $text : ''
		);

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendLanguages( $property ) {
		$data = array();
		foreach ( Language::getLanguageNames() as $code => $name ) {
			$lang = array( 'code' => $code );
			ApiResult::setContent( $lang, $name );
			$data[] = $lang;
		}
		$this->getResult()->setIndexedTagName( $data, 'lang' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_DFLT => 'general',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'general',
					'namespaces',
					'namespacealiases',
					'specialpagealiases',
					'magicwords',
					'interwikimap',
					'dbrepllag',
					'statistics',
					'usergroups',
					'extensions',
					'fileextensions',
					'rightsinfo',
					'languages',
				)
			),
			'filteriw' => array(
				ApiBase::PARAM_TYPE => array(
					'local',
					'!local',
				)
			),
			'showalldb' => false,
			'numberingroup' => false,
		);
	}

	public function getParamDescription() {
		return array(
			'prop' => array(
				'Which sysinfo properties to get:',
				' general      - Overall system information',
				' namespaces   - List of registered namespaces and their canonical names',
				' namespacealiases - List of registered namespace aliases',
				' specialpagealiases - List of special page aliases',
				' magicwords   - List of magic words and their aliases',
				' statistics   - Returns site statistics',
				' interwikimap - Returns interwiki map (optionally filtered)',
				' dbrepllag    - Returns database server with the highest replication lag',
				' usergroups   - Returns user groups and the associated permissions',
				' extensions   - Returns extensions installed on the wiki',
				' fileextensions - Returns list of file extensions allowed to be uploaded',
				' rightsinfo   - Returns wiki rights (license) information if available',
				' languages    - Returns a list of languages MediaWiki supports',
			),
			'filteriw' =>  'Return only local or only nonlocal entries of the interwiki map',
			'showalldb' => 'List all database servers, not just the one lagging the most',
			'numberingroup' => 'Lists the number of users in user groups',
		);
	}

	public function getDescription() {
		return 'Return general information about the site';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'includeAllDenied', 'info' => 'Cannot view all servers info unless $wgShowHostnames is true' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&meta=siteinfo&siprop=general|namespaces|namespacealiases|statistics',
			'api.php?action=query&meta=siteinfo&siprop=interwikimap&sifilteriw=local',
			'api.php?action=query&meta=siteinfo&siprop=dbrepllag&sishowalldb',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
