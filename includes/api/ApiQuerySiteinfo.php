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
		$fit = false;
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
				case 'restrictions':
					$fit = $this->appendRestrictions( $p );
					break;
				case 'languages':
					$fit = $this->appendLanguages( $p );
					break;
				case 'skins':
					$fit = $this->appendSkins( $p );
					break;
				case 'extensiontags':
					$fit = $this->appendExtensionTags( $p );
					break;
				case 'functionhooks':
					$fit = $this->appendFunctionHooks( $p );
					break;
				case 'showhooks':
					$fit = $this->appendSubscribedHooks( $p );
					break;
				case 'variables':
					$fit = $this->appendVariables( $p );
					break;
				case 'protocols':
					$fit = $this->appendProtocols( $p );
					break;
				case 'defaultoptions':
					$fit = $this->appendDefaultOptions( $p );
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
		global $wgContLang, $wgDisableLangConversion, $wgDisableTitleConversion;

		$data = array();
		$mainPage = Title::newMainPage();
		$data['mainpage'] = $mainPage->getPrefixedText();
		$data['base'] = wfExpandUrl( $mainPage->getFullURL(), PROTO_CURRENT );
		$data['sitename'] = $GLOBALS['wgSitename'];

		// wgLogo can either be a relative or an absolute path
		// make sure we always return an absolute path
		$data['logo'] = wfExpandUrl( $GLOBALS['wgLogo'], PROTO_RELATIVE );

		$data['generator'] = "MediaWiki {$GLOBALS['wgVersion']}";
		$data['phpversion'] = phpversion();
		$data['phpsapi'] = PHP_SAPI;
		$data['dbtype'] = $GLOBALS['wgDBtype'];
		$data['dbversion'] = $this->getDB()->getServerVersion();

		$allowFrom = array( '' );
		$allowException = true;
		if ( !$GLOBALS['wgAllowExternalImages'] ) {
			if ( $GLOBALS['wgEnableImageWhitelist'] ) {
				$data['imagewhitelistenabled'] = '';
			}
			$allowFrom = $GLOBALS['wgAllowExternalImagesFrom'];
			$allowException = !empty( $allowFrom );
		}
		if ( $allowException ) {
			$data['externalimages'] = (array)$allowFrom;
			$this->getResult()->setIndexedTagName( $data['externalimages'], 'prefix' );
		}

		if ( !$wgDisableLangConversion ) {
			$data['langconversion'] = '';
		}

		if ( !$wgDisableTitleConversion ) {
			$data['titleconversion'] = '';
		}

		if ( $wgContLang->linkPrefixExtension() ) {
			$linkPrefixCharset = $wgContLang->linkPrefixCharset();
			$data['linkprefixcharset'] = $linkPrefixCharset;
			// For backwards compatability
			$data['linkprefix'] = "/^((?>.*[^$linkPrefixCharset]|))(.+)$/sDu";
		} else {
			$data['linkprefixcharset'] = '';
			$data['linkprefix'] = '';
		}

		$linktrail = $wgContLang->linkTrail();
		if ( $linktrail ) {
			$data['linktrail'] = $linktrail;
		} else {
			$data['linktrail'] = '';
		}

		$git = SpecialVersion::getGitHeadSha1( $GLOBALS['IP'] );
		if ( $git ) {
			$data['git-hash'] = $git;
		} else {
			$svn = SpecialVersion::getSvnRevision( $GLOBALS['IP'] );
			if ( $svn ) {
				$data['rev'] = $svn;
			}
		}

		// 'case-insensitive' option is reserved for future
		$data['case'] = $GLOBALS['wgCapitalLinks'] ? 'first-letter' : 'case-sensitive';

		$data['lang'] = $GLOBALS['wgLanguageCode'];

		$fallbacks = array();
		foreach ( $wgContLang->getFallbackLanguages() as $code ) {
			$fallbacks[] = array( 'code' => $code );
		}
		$data['fallback'] = $fallbacks;
		$this->getResult()->setIndexedTagName( $data['fallback'], 'lang' );

		if ( $wgContLang->hasVariants() ) {
			$variants = array();
			foreach ( $wgContLang->getVariants() as $code ) {
				$variants[] = array(
					'code' => $code,
					'name' => $wgContLang->getVariantname( $code ),
				);
			}
			$data['variants'] = $variants;
			$this->getResult()->setIndexedTagName( $data['variants'], 'lang' );
		}

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

		if ( $GLOBALS['wgMiserMode'] ) {
			$data['misermode'] = '';
		}

		$data['maxuploadsize'] = UploadBase::getMaxUploadSize();
		$data['minuploadchunksize'] = (int)$this->getConfig()->get( 'MinUploadChunkSize' );

		$data['thumblimits'] = $GLOBALS['wgThumbLimits'];
		$this->getResult()->setIndexedTagName( $data['thumblimits'], 'limit' );
		$data['imagelimits'] = array();
		$this->getResult()->setIndexedTagName( $data['imagelimits'], 'limit' );
		foreach ( $GLOBALS['wgImageLimits'] as $k => $limit ) {
			$data['imagelimits'][$k] = array( 'width' => $limit[0], 'height' => $limit[1] );
		}

		if ( !empty( $GLOBALS['wgFavicon'] ) ) {
			// wgFavicon can either be a relative or an absolute path
			// make sure we always return an absolute path
			$data['favicon'] = wfExpandUrl( $GLOBALS['wgFavicon'], PROTO_RELATIVE );
		}

		wfRunHooks( 'APIQuerySiteInfoGeneralInfo', array( $this, &$data ) );

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

			if ( MWNamespace::isNonincludable( $ns ) ) {
				$data[$ns]['nonincludable'] = '';
			}

			$contentmodel = MWNamespace::getNamespaceContentModel( $ns );
			if ( $contentmodel ) {
				$data[$ns]['defaultcontentmodel'] = $contentmodel;
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

		sort( $data );

		$this->getResult()->setIndexedTagName( $data, 'ns' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendSpecialPageAliases( $property ) {
		global $wgContLang;
		$data = array();
		$aliases = $wgContLang->getSpecialPageAliases();
		foreach ( SpecialPageFactory::getList() as $specialpage => $stuff ) {
			if ( isset( $aliases[$specialpage] ) ) {
				$arr = array( 'realname' => $specialpage, 'aliases' => $aliases[$specialpage] );
				$this->getResult()->setIndexedTagName( $arr['aliases'], 'alias' );
				$data[] = $arr;
			}
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
		$local = null;
		if ( $filter === 'local' ) {
			$local = 1;
		} elseif ( $filter === '!local' ) {
			$local = 0;
		} elseif ( $filter ) {
			ApiBase::dieDebug( __METHOD__, "Unknown filter=$filter" );
		}

		$params = $this->extractRequestParams();
		$langCode = isset( $params['inlanguagecode'] ) ? $params['inlanguagecode'] : '';
		$langNames = Language::fetchLanguageNames( $langCode );

		$getPrefixes = Interwiki::getAllPrefixes( $local );
		$data = array();

		foreach ( $getPrefixes as $row ) {
			$prefix = $row['iw_prefix'];
			$val = array();
			$val['prefix'] = $prefix;
			if ( $row['iw_local'] == '1' ) {
				$val['local'] = '';
			}
			if ( $row['iw_trans'] == '1' ) {
				$val['trans'] = '';
			}
			if ( isset( $langNames[$prefix] ) ) {
				$val['language'] = $langNames[$prefix];
			}
			$val['url'] = wfExpandUrl( $row['iw_url'], PROTO_CURRENT );
			if ( isset( $row['iw_wikiid'] ) ) {
				$val['wikiid'] = $row['iw_wikiid'];
			}
			if ( isset( $row['iw_api'] ) ) {
				$val['api'] = $row['iw_api'];
			}

			$data[] = $val;
		}

		$this->getResult()->setIndexedTagName( $data, 'iw' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendDbReplLagInfo( $property, $includeAll ) {
		global $wgShowHostnames;
		$data = array();
		$lb = wfGetLB();
		if ( $includeAll ) {
			if ( !$wgShowHostnames ) {
				$this->dieUsage(
					'Cannot view all servers info unless $wgShowHostnames is true',
					'includeAllDenied'
				);
			}

			$lags = $lb->getLagTimes();
			foreach ( $lags as $i => $lag ) {
				$data[] = array(
					'host' => $lb->getServerName( $i ),
					'lag' => $lag
				);
			}
		} else {
			list( , $lag, $index ) = $lb->getMaxLag();
			$data[] = array(
				'host' => $wgShowHostnames
						? $lb->getServerName( $index )
						: '',
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

		wfRunHooks( 'APIQuerySiteInfoStatisticsInfo', array( &$data ) );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendUserGroups( $property, $numberInGroup ) {
		global $wgGroupPermissions, $wgAddGroups, $wgRemoveGroups;
		global $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf;

		$data = array();
		$result = $this->getResult();
		foreach ( $wgGroupPermissions as $group => $permissions ) {
			$arr = array(
				'name' => $group,
				'rights' => array_keys( $permissions, true ),
			);

			if ( $numberInGroup ) {
				global $wgAutopromote;

				if ( $group == 'user' ) {
					$arr['number'] = SiteStats::users();
				// '*' and autopromote groups have no size
				} elseif ( $group !== '*' && !isset( $wgAutopromote[$group] ) ) {
					$arr['number'] = SiteStats::numberInGroup( $group );
				}
			}

			$groupArr = array(
				'add' => $wgAddGroups,
				'remove' => $wgRemoveGroups,
				'add-self' => $wgGroupsAddToSelf,
				'remove-self' => $wgGroupsRemoveFromSelf
			);

			foreach ( $groupArr as $type => $rights ) {
				if ( isset( $rights[$group] ) ) {
					$arr[$type] = $rights[$group];
					$result->setIndexedTagName( $arr[$type], 'group' );
				}
			}

			$result->setIndexedTagName( $arr['rights'], 'permission' );
			$data[] = $arr;
		}

		$result->setIndexedTagName( $data, 'group' );

		return $result->addValue( 'query', $property, $data );
	}

	protected function appendFileExtensions( $property ) {
		global $wgFileExtensions;

		$data = array();
		foreach ( array_unique( $wgFileExtensions ) as $ext ) {
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
						implode( ', ', $ext['author'] ) : $ext['author'];
				}
				if ( isset( $ext['url'] ) ) {
					$ret['url'] = $ext['url'];
				}
				if ( isset( $ext['version'] ) ) {
					$ret['version'] = $ext['version'];
				} elseif ( isset( $ext['svn-revision'] ) &&
					preg_match( '/\$(?:Rev|LastChangedRevision|Revision): *(\d+)/',
						$ext['svn-revision'], $m )
				) {
					$ret['version'] = 'r' . $m[1];
				}
				if ( isset( $ext['path'] ) ) {
					$extensionPath = dirname( $ext['path'] );
					$gitInfo = new GitInfo( $extensionPath );
					$vcsVersion = $gitInfo->getHeadSHA1();
					if ( $vcsVersion !== false ) {
						$ret['vcs-system'] = 'git';
						$ret['vcs-version'] = $vcsVersion;
						$ret['vcs-url'] = $gitInfo->getHeadViewUrl();
						$vcsDate = $gitInfo->getHeadCommitDate();
						if ( $vcsDate !== false ) {
							$ret['vcs-date'] = wfTimestamp( TS_ISO_8601, $vcsDate );
						}
					} else {
						$svnInfo = SpecialVersion::getSvnInfo( $extensionPath );
						if ( $svnInfo !== false ) {
							$ret['vcs-system'] = 'svn';
							$ret['vcs-version'] = $svnInfo['checkout-rev'];
							$ret['vcs-url'] = isset( $svnInfo['viewvc-url'] ) ? $svnInfo['viewvc-url'] : '';
						}
					}

					if ( SpecialVersion::getExtLicenseFileName( $extensionPath ) ) {
						$ret['license-name'] = isset( $ext['license-name'] ) ? $ext['license-name'] : '';
						$ret['license'] = SpecialPage::getTitleFor(
							'Version',
							"License/{$ext['name']}"
						)->getLinkURL();
					}

					if ( SpecialVersion::getExtAuthorsFileName( $extensionPath ) ) {
						$ret['credits'] = SpecialPage::getTitleFor(
							'Version',
							"Credits/{$ext['name']}"
						)->getLinkURL();
					}
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
		$url = $title ? wfExpandUrl( $title->getFullURL(), PROTO_CURRENT ) : $wgRightsUrl;
		$text = $wgRightsText;
		if ( !$text && $title ) {
			$text = $title->getPrefixedText();
		}

		$data = array(
			'url' => $url ? $url : '',
			'text' => $text ? $text : ''
		);

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendRestrictions( $property ) {
		global $wgRestrictionTypes, $wgRestrictionLevels,
			$wgCascadingRestrictionLevels, $wgSemiprotectedRestrictionLevels;

		$data = array(
			'types' => $wgRestrictionTypes,
			'levels' => $wgRestrictionLevels,
			'cascadinglevels' => $wgCascadingRestrictionLevels,
			'semiprotectedlevels' => $wgSemiprotectedRestrictionLevels,
		);

		$this->getResult()->setIndexedTagName( $data['types'], 'type' );
		$this->getResult()->setIndexedTagName( $data['levels'], 'level' );
		$this->getResult()->setIndexedTagName( $data['cascadinglevels'], 'level' );
		$this->getResult()->setIndexedTagName( $data['semiprotectedlevels'], 'level' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendLanguages( $property ) {
		$params = $this->extractRequestParams();
		$langCode = isset( $params['inlanguagecode'] ) ? $params['inlanguagecode'] : '';
		$langNames = Language::fetchLanguageNames( $langCode );

		$data = array();

		foreach ( $langNames as $code => $name ) {
			$lang = array( 'code' => $code );
			ApiResult::setContent( $lang, $name );
			$data[] = $lang;
		}
		$this->getResult()->setIndexedTagName( $data, 'lang' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendSkins( $property ) {
		$data = array();
		$allowed = Skin::getAllowedSkins();
		$default = Skin::normalizeKey( 'default' );
		foreach ( Skin::getSkinNames() as $name => $displayName ) {
			$skin = array( 'code' => $name );
			ApiResult::setContent( $skin, $displayName );
			if ( !isset( $allowed[$name] ) ) {
				$skin['unusable'] = '';
			}
			if ( $name === $default ) {
				$skin['default'] = '';
			}
			$data[] = $skin;
		}
		$this->getResult()->setIndexedTagName( $data, 'skin' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendExtensionTags( $property ) {
		global $wgParser;
		$wgParser->firstCallInit();
		$tags = array_map( array( $this, 'formatParserTags' ), $wgParser->getTags() );
		$this->getResult()->setIndexedTagName( $tags, 't' );

		return $this->getResult()->addValue( 'query', $property, $tags );
	}

	public function appendFunctionHooks( $property ) {
		global $wgParser;
		$wgParser->firstCallInit();
		$hooks = $wgParser->getFunctionHooks();
		$this->getResult()->setIndexedTagName( $hooks, 'h' );

		return $this->getResult()->addValue( 'query', $property, $hooks );
	}

	public function appendVariables( $property ) {
		$variables = MagicWord::getVariableIDs();
		$this->getResult()->setIndexedTagName( $variables, 'v' );

		return $this->getResult()->addValue( 'query', $property, $variables );
	}

	public function appendProtocols( $property ) {
		global $wgUrlProtocols;
		// Make a copy of the global so we don't try to set the _element key of it - bug 45130
		$protocols = array_values( $wgUrlProtocols );
		$this->getResult()->setIndexedTagName( $protocols, 'p' );

		return $this->getResult()->addValue( 'query', $property, $protocols );
	}

	public function appendDefaultOptions( $property ) {
		return $this->getResult()->addValue( 'query', $property, User::getDefaultOptions() );
	}

	private function formatParserTags( $item ) {
		return "<{$item}>";
	}

	public function appendSubscribedHooks( $property ) {
		global $wgHooks;
		$myWgHooks = $wgHooks;
		ksort( $myWgHooks );

		$data = array();
		foreach ( $myWgHooks as $hook => $hooks ) {
			$arr = array(
				'name' => $hook,
				'subscribers' => array_map( array( 'SpecialVersion', 'arrayToString' ), $hooks ),
			);

			$this->getResult()->setIndexedTagName( $arr['subscribers'], 's' );
			$data[] = $arr;
		}

		$this->getResult()->setIndexedTagName( $data, 'hook' );

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
					'restrictions',
					'languages',
					'skins',
					'extensiontags',
					'functionhooks',
					'showhooks',
					'variables',
					'protocols',
					'defaultoptions',
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
			'inlanguagecode' => null,
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();

		return array(
			'prop' => array(
				'Which sysinfo properties to get:',
				' general               - Overall system information',
				' namespaces            - List of registered namespaces and their canonical names',
				' namespacealiases      - List of registered namespace aliases',
				' specialpagealiases    - List of special page aliases',
				' magicwords            - List of magic words and their aliases',
				' statistics            - Returns site statistics',
				' interwikimap          - Returns interwiki map ' .
					"(optionally filtered, (optionally localised by using {$p}inlanguagecode))",
				' dbrepllag             - Returns database server with the highest replication lag',
				' usergroups            - Returns user groups and the associated permissions',
				' extensions            - Returns extensions installed on the wiki',
				' fileextensions        - Returns list of file extensions allowed to be uploaded',
				' rightsinfo            - Returns wiki rights (license) information if available',
				' restrictions          - Returns information on available restriction (protection) types',
				' languages             - Returns a list of languages MediaWiki supports' .
					"(optionally localised by using {$p}inlanguagecode)",
				' skins                 - Returns a list of all enabled skins',
				' extensiontags         - Returns a list of parser extension tags',
				' functionhooks         - Returns a list of parser function hooks',
				' showhooks             - Returns a list of all subscribed hooks (contents of $wgHooks)',
				' variables             - Returns a list of variable IDs',
				' protocols             - Returns a list of protocols that are allowed in external links.',
				' defaultoptions        - Returns the default values for user preferences.',
			),
			'filteriw' => 'Return only local or only nonlocal entries of the interwiki map',
			'showalldb' => 'List all database servers, not just the one lagging the most',
			'numberingroup' => 'Lists the number of users in user groups',
			'inlanguagecode' => 'Language code for localised language names ' .
				'(best effort, use CLDR extension)',
		);
	}

	public function getDescription() {
		return 'Return general information about the site.';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array( array(
			'code' => 'includeAllDenied',
			'info' => 'Cannot view all servers info unless $wgShowHostnames is true'
		), ) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&meta=siteinfo&siprop=general|namespaces|namespacealiases|statistics',
			'api.php?action=query&meta=siteinfo&siprop=interwikimap&sifilteriw=local',
			'api.php?action=query&meta=siteinfo&siprop=dbrepllag&sishowalldb=',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Meta#siteinfo_.2F_si';
	}
}
