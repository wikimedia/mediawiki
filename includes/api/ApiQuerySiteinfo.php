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

	public function __construct( ApiQuery $query, $moduleName ) {
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
				case 'libraries':
					$fit = $this->appendInstalledLibraries( $p );
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
		global $wgContLang;

		$config = $this->getConfig();

		$data = array();
		$mainPage = Title::newMainPage();
		$data['mainpage'] = $mainPage->getPrefixedText();
		$data['base'] = wfExpandUrl( $mainPage->getFullURL(), PROTO_CURRENT );
		$data['sitename'] = $config->get( 'Sitename' );

		// wgLogo can either be a relative or an absolute path
		// make sure we always return an absolute path
		$data['logo'] = wfExpandUrl( $config->get( 'Logo' ), PROTO_RELATIVE );

		$data['generator'] = "MediaWiki {$config->get( 'Version' )}";

		$data['phpversion'] = PHP_VERSION;
		$data['phpsapi'] = PHP_SAPI;
		if ( defined( 'HHVM_VERSION' ) ) {
			$data['hhvmversion'] = HHVM_VERSION;
		}
		$data['dbtype'] = $config->get( 'DBtype' );
		$data['dbversion'] = $this->getDB()->getServerVersion();

		$allowFrom = array( '' );
		$allowException = true;
		if ( !$config->get( 'AllowExternalImages' ) ) {
			$data['imagewhitelistenabled'] = (bool)$config->get( 'EnableImageWhitelist' );
			$allowFrom = $config->get( 'AllowExternalImagesFrom' );
			$allowException = !empty( $allowFrom );
		}
		if ( $allowException ) {
			$data['externalimages'] = (array)$allowFrom;
			ApiResult::setIndexedTagName( $data['externalimages'], 'prefix' );
		}

		$data['langconversion'] = !$config->get( 'DisableLangConversion' );
		$data['titleconversion'] = !$config->get( 'DisableTitleConversion' );

		if ( $wgContLang->linkPrefixExtension() ) {
			$linkPrefixCharset = $wgContLang->linkPrefixCharset();
			$data['linkprefixcharset'] = $linkPrefixCharset;
			// For backwards compatibility
			$data['linkprefix'] = "/^((?>.*[^$linkPrefixCharset]|))(.+)$/sDu";
		} else {
			$data['linkprefixcharset'] = '';
			$data['linkprefix'] = '';
		}

		$linktrail = $wgContLang->linkTrail();
		$data['linktrail'] = $linktrail ?: '';

		$data['legaltitlechars'] = Title::legalChars();

		global $IP;
		$git = SpecialVersion::getGitHeadSha1( $IP );
		if ( $git ) {
			$data['git-hash'] = $git;
			$data['git-branch'] =
				SpecialVersion::getGitCurrentBranch( $GLOBALS['IP'] );
		} else {
			$svn = SpecialVersion::getSvnRevision( $IP );
			if ( $svn ) {
				$data['rev'] = $svn;
			}
		}

		// 'case-insensitive' option is reserved for future
		$data['case'] = $config->get( 'CapitalLinks' ) ? 'first-letter' : 'case-sensitive';
		$data['lang'] = $config->get( 'LanguageCode' );

		$fallbacks = array();
		foreach ( $wgContLang->getFallbackLanguages() as $code ) {
			$fallbacks[] = array( 'code' => $code );
		}
		$data['fallback'] = $fallbacks;
		ApiResult::setIndexedTagName( $data['fallback'], 'lang' );

		if ( $wgContLang->hasVariants() ) {
			$variants = array();
			foreach ( $wgContLang->getVariants() as $code ) {
				$variants[] = array(
					'code' => $code,
					'name' => $wgContLang->getVariantname( $code ),
				);
			}
			$data['variants'] = $variants;
			ApiResult::setIndexedTagName( $data['variants'], 'lang' );
		}

		$data['rtl'] = $wgContLang->isRTL();
		$data['fallback8bitEncoding'] = $wgContLang->fallback8bitEncoding();

		$data['readonly'] = wfReadOnly();
		if ( $data['readonly'] ) {
			$data['readonlyreason'] = wfReadOnlyReason();
		}
		$data['writeapi'] = (bool)$config->get( 'EnableWriteAPI' );

		$tz = $config->get( 'Localtimezone' );
		$offset = $config->get( 'LocalTZoffset' );
		if ( is_null( $tz ) ) {
			$tz = 'UTC';
			$offset = 0;
		} elseif ( is_null( $offset ) ) {
			$offset = 0;
		}
		$data['timezone'] = $tz;
		$data['timeoffset'] = intval( $offset );
		$data['articlepath'] = $config->get( 'ArticlePath' );
		$data['scriptpath'] = $config->get( 'ScriptPath' );
		$data['script'] = $config->get( 'Script' );
		$data['variantarticlepath'] = $config->get( 'VariantArticlePath' );
		$data[ApiResult::META_BC_BOOLS][] = 'variantarticlepath';
		$data['server'] = $config->get( 'Server' );
		$data['servername'] = $config->get( 'ServerName' );
		$data['wikiid'] = wfWikiID();
		$data['time'] = wfTimestamp( TS_ISO_8601, time() );

		$data['misermode'] = (bool)$config->get( 'MiserMode' );

		$data['maxuploadsize'] = UploadBase::getMaxUploadSize();

		$data['thumblimits'] = $config->get( 'ThumbLimits' );
		ApiResult::setArrayType( $data['thumblimits'], 'BCassoc' );
		ApiResult::setIndexedTagName( $data['thumblimits'], 'limit' );
		$data['imagelimits'] = array();
		ApiResult::setArrayType( $data['imagelimits'], 'BCassoc' );
		ApiResult::setIndexedTagName( $data['imagelimits'], 'limit' );
		foreach ( $config->get( 'ImageLimits' ) as $k => $limit ) {
			$data['imagelimits'][$k] = array( 'width' => $limit[0], 'height' => $limit[1] );
		}

		$favicon = $config->get( 'Favicon' );
		if ( !empty( $favicon ) ) {
			// wgFavicon can either be a relative or an absolute path
			// make sure we always return an absolute path
			$data['favicon'] = wfExpandUrl( $favicon, PROTO_RELATIVE );
		}

		Hooks::run( 'APIQuerySiteInfoGeneralInfo', array( $this, &$data ) );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendNamespaces( $property ) {
		global $wgContLang;
		$data = array(
			ApiResult::META_TYPE => 'assoc',
		);
		foreach ( $wgContLang->getFormattedNamespaces() as $ns => $title ) {
			$data[$ns] = array(
				'id' => intval( $ns ),
				'case' => MWNamespace::isCapitalized( $ns ) ? 'first-letter' : 'case-sensitive',
			);
			ApiResult::setContentValue( $data[$ns], 'name', $title );
			$canonical = MWNamespace::getCanonicalName( $ns );

			$data[$ns]['subpages'] = MWNamespace::hasSubpages( $ns );

			if ( $canonical ) {
				$data[$ns]['canonical'] = strtr( $canonical, '_', ' ' );
			}

			$data[$ns]['content'] = MWNamespace::isContent( $ns );
			$data[$ns]['nonincludable'] = MWNamespace::isNonincludable( $ns );

			$contentmodel = MWNamespace::getNamespaceContentModel( $ns );
			if ( $contentmodel ) {
				$data[$ns]['defaultcontentmodel'] = $contentmodel;
			}
		}

		ApiResult::setIndexedTagName( $data, 'ns' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendNamespaceAliases( $property ) {
		global $wgContLang;
		$aliases = array_merge( $this->getConfig()->get( 'NamespaceAliases' ),
			$wgContLang->getNamespaceAliases() );
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
			ApiResult::setContentValue( $item, 'alias', strtr( $title, '_', ' ' ) );
			$data[] = $item;
		}

		sort( $data );

		ApiResult::setIndexedTagName( $data, 'ns' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendSpecialPageAliases( $property ) {
		global $wgContLang;
		$data = array();
		$aliases = $wgContLang->getSpecialPageAliases();
		foreach ( SpecialPageFactory::getNames() as $specialpage ) {
			if ( isset( $aliases[$specialpage] ) ) {
				$arr = array( 'realname' => $specialpage, 'aliases' => $aliases[$specialpage] );
				ApiResult::setIndexedTagName( $arr['aliases'], 'alias' );
				$data[] = $arr;
			}
		}
		ApiResult::setIndexedTagName( $data, 'specialpage' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendMagicWords( $property ) {
		global $wgContLang;
		$data = array();
		foreach ( $wgContLang->getMagicWords() as $magicword => $aliases ) {
			$caseSensitive = array_shift( $aliases );
			$arr = array( 'name' => $magicword, 'aliases' => $aliases );
			$arr['case-sensitive'] = (bool)$caseSensitive;
			ApiResult::setIndexedTagName( $arr['aliases'], 'alias' );
			$data[] = $arr;
		}
		ApiResult::setIndexedTagName( $data, 'magicword' );

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
		$extraLangPrefixes = $this->getConfig()->get( 'ExtraInterlanguageLinkPrefixes' );
		$localInterwikis = $this->getConfig()->get( 'LocalInterwikis' );
		$data = array();

		foreach ( $getPrefixes as $row ) {
			$prefix = $row['iw_prefix'];
			$val = array();
			$val['prefix'] = $prefix;
			if ( isset( $row['iw_local'] ) && $row['iw_local'] == '1' ) {
				$val['local'] = true;
			}
			if ( isset( $row['iw_trans'] ) && $row['iw_trans'] == '1' ) {
				$val['trans'] = true;
			}

			if ( isset( $langNames[$prefix] ) ) {
				$val['language'] = $langNames[$prefix];
			}
			if ( in_array( $prefix, $localInterwikis ) ) {
				$val['localinterwiki'] = true;
			}
			if ( in_array( $prefix, $extraLangPrefixes ) ) {
				$val['extralanglink'] = true;

				$linktext = wfMessage( "interlanguage-link-$prefix" );
				if ( !$linktext->isDisabled() ) {
					$val['linktext'] = $linktext->text();
				}

				$sitename = wfMessage( "interlanguage-link-sitename-$prefix" );
				if ( !$sitename->isDisabled() ) {
					$val['sitename'] = $sitename->text();
				}
			}

			$val['url'] = wfExpandUrl( $row['iw_url'], PROTO_CURRENT );
			$val['protorel'] = substr( $row['iw_url'], 0, 2 ) == '//';
			if ( isset( $row['iw_wikiid'] ) && $row['iw_wikiid'] !== '' ) {
				$val['wikiid'] = $row['iw_wikiid'];
			}
			if ( isset( $row['iw_api'] ) && $row['iw_api'] !== '' ) {
				$val['api'] = $row['iw_api'];
			}

			$data[] = $val;
		}

		ApiResult::setIndexedTagName( $data, 'iw' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendDbReplLagInfo( $property, $includeAll ) {
		$data = array();
		$lb = wfGetLB();
		$showHostnames = $this->getConfig()->get( 'ShowHostnames' );
		if ( $includeAll ) {
			if ( !$showHostnames ) {
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
				'host' => $showHostnames
						? $lb->getServerName( $index )
						: '',
				'lag' => intval( $lag )
			);
		}

		$result = $this->getResult();
		ApiResult::setIndexedTagName( $data, 'db' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendStatistics( $property ) {
		$data = array();
		$data['pages'] = intval( SiteStats::pages() );
		$data['articles'] = intval( SiteStats::articles() );
		$data['edits'] = intval( SiteStats::edits() );
		$data['images'] = intval( SiteStats::images() );
		$data['users'] = intval( SiteStats::users() );
		$data['activeusers'] = intval( SiteStats::activeUsers() );
		$data['admins'] = intval( SiteStats::numberingroup( 'sysop' ) );
		$data['jobs'] = intval( SiteStats::jobs() );

		Hooks::run( 'APIQuerySiteInfoStatisticsInfo', array( &$data ) );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendUserGroups( $property, $numberInGroup ) {
		$config = $this->getConfig();

		$data = array();
		$result = $this->getResult();
		$allGroups = User::getAllGroups();
		foreach ( $config->get( 'GroupPermissions' ) as $group => $permissions ) {
			$arr = array(
				'name' => $group,
				'rights' => array_keys( $permissions, true ),
			);

			if ( $numberInGroup ) {
				$autopromote = $config->get( 'Autopromote' );

				if ( $group == 'user' ) {
					$arr['number'] = SiteStats::users();
				// '*' and autopromote groups have no size
				} elseif ( $group !== '*' && !isset( $autopromote[$group] ) ) {
					$arr['number'] = SiteStats::numberInGroup( $group );
				}
			}

			$groupArr = array(
				'add' => $config->get( 'AddGroups' ),
				'remove' => $config->get( 'RemoveGroups' ),
				'add-self' => $config->get( 'GroupsAddToSelf' ),
				'remove-self' => $config->get( 'GroupsRemoveFromSelf' )
			);

			foreach ( $groupArr as $type => $rights ) {
				if ( isset( $rights[$group] ) ) {
					$groups = array_intersect( $rights[$group], $allGroups );
					if ( $groups ) {
						$arr[$type] = $groups;
						ApiResult::setIndexedTagName( $arr[$type], 'group' );
					}
				}
			}

			ApiResult::setIndexedTagName( $arr['rights'], 'permission' );
			$data[] = $arr;
		}

		ApiResult::setIndexedTagName( $data, 'group' );

		return $result->addValue( 'query', $property, $data );
	}

	protected function appendFileExtensions( $property ) {
		$data = array();
		foreach ( array_unique( $this->getConfig()->get( 'FileExtensions' ) ) as $ext ) {
			$data[] = array( 'ext' => $ext );
		}
		ApiResult::setIndexedTagName( $data, 'fe' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendInstalledLibraries( $property ) {
		global $IP;
		$path = "$IP/composer.lock";
		if ( !file_exists( $path ) ) {
			// Maybe they're using mediawiki/vendor?
			$path = "$IP/vendor/composer.lock";
			if ( !file_exists( $path ) ) {
				return true;
			}
		}

		$data = array();
		$lock = new ComposerLock( $path );
		foreach ( $lock->getInstalledDependencies() as $name => $info ) {
			if ( strpos( $info['type'], 'mediawiki-' ) === 0 ) {
				// Skip any extensions or skins since they'll be listed
				// in their proper section
				continue;
			}
			$data[] = array(
				'name' => $name,
				'version' => $info['version'],
			);
		}
		ApiResult::setIndexedTagName( $data, 'library' );

		return $this->getResult()->addValue( 'query', $property, $data );

	}

	protected function appendExtensions( $property ) {
		$data = array();
		foreach ( $this->getConfig()->get( 'ExtensionCredits' ) as $type => $extensions ) {
			foreach ( $extensions as $ext ) {
				$ret = array();
				$ret['type'] = $type;
				if ( isset( $ext['name'] ) ) {
					$ret['name'] = $ext['name'];
				}
				if ( isset( $ext['namemsg'] ) ) {
					$ret['namemsg'] = $ext['namemsg'];
				}
				if ( isset( $ext['description'] ) ) {
					$ret['description'] = $ext['description'];
				}
				if ( isset( $ext['descriptionmsg'] ) ) {
					// Can be a string or array( key, param1, param2, ... )
					if ( is_array( $ext['descriptionmsg'] ) ) {
						$ret['descriptionmsg'] = $ext['descriptionmsg'][0];
						$ret['descriptionmsgparams'] = array_slice( $ext['descriptionmsg'], 1 );
						ApiResult::setIndexedTagName( $ret['descriptionmsgparams'], 'param' );
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

		ApiResult::setIndexedTagName( $data, 'ext' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendRightsInfo( $property ) {
		$config = $this->getConfig();
		$rightsPage = $config->get( 'RightsPage' );
		if ( is_string( $rightsPage ) ) {
			$title = Title::newFromText( $rightsPage );
			$url = wfExpandUrl( $title, PROTO_CURRENT );
		} else {
			$title = false;
			$url = $config->get( 'RightsUrl' );
		}
		$text = $config->get( 'RightsText' );
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
		$config = $this->getConfig();
		$data = array(
			'types' => $config->get( 'RestrictionTypes' ),
			'levels' => $config->get( 'RestrictionLevels' ),
			'cascadinglevels' => $config->get( 'CascadingRestrictionLevels' ),
			'semiprotectedlevels' => $config->get( 'SemiprotectedRestrictionLevels' ),
		);

		ApiResult::setIndexedTagName( $data['types'], 'type' );
		ApiResult::setIndexedTagName( $data['levels'], 'level' );
		ApiResult::setIndexedTagName( $data['cascadinglevels'], 'level' );
		ApiResult::setIndexedTagName( $data['semiprotectedlevels'], 'level' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendLanguages( $property ) {
		$params = $this->extractRequestParams();
		$langCode = isset( $params['inlanguagecode'] ) ? $params['inlanguagecode'] : '';
		$langNames = Language::fetchLanguageNames( $langCode );

		$data = array();

		foreach ( $langNames as $code => $name ) {
			$lang = array( 'code' => $code );
			ApiResult::setContentValue( $lang, 'name', $name );
			$data[] = $lang;
		}
		ApiResult::setIndexedTagName( $data, 'lang' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendSkins( $property ) {
		$data = array();
		$allowed = Skin::getAllowedSkins();
		$default = Skin::normalizeKey( 'default' );
		foreach ( Skin::getSkinNames() as $name => $displayName ) {
			$msg = $this->msg( "skinname-{$name}" );
			$code = $this->getParameter( 'inlanguagecode' );
			if ( $code && Language::isValidCode( $code ) ) {
				$msg->inLanguage( $code );
			} else {
				$msg->inContentLanguage();
			}
			if ( $msg->exists() ) {
				$displayName = $msg->text();
			}
			$skin = array( 'code' => $name );
			ApiResult::setContentValue( $skin, 'name', $displayName );
			if ( !isset( $allowed[$name] ) ) {
				$skin['unusable'] = true;
			}
			if ( $name === $default ) {
				$skin['default'] = true;
			}
			$data[] = $skin;
		}
		ApiResult::setIndexedTagName( $data, 'skin' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendExtensionTags( $property ) {
		global $wgParser;
		$wgParser->firstCallInit();
		$tags = array_map( array( $this, 'formatParserTags' ), $wgParser->getTags() );
		ApiResult::setIndexedTagName( $tags, 't' );

		return $this->getResult()->addValue( 'query', $property, $tags );
	}

	public function appendFunctionHooks( $property ) {
		global $wgParser;
		$wgParser->firstCallInit();
		$hooks = $wgParser->getFunctionHooks();
		ApiResult::setIndexedTagName( $hooks, 'h' );

		return $this->getResult()->addValue( 'query', $property, $hooks );
	}

	public function appendVariables( $property ) {
		$variables = MagicWord::getVariableIDs();
		ApiResult::setIndexedTagName( $variables, 'v' );

		return $this->getResult()->addValue( 'query', $property, $variables );
	}

	public function appendProtocols( $property ) {
		// Make a copy of the global so we don't try to set the _element key of it - bug 45130
		$protocols = array_values( $this->getConfig()->get( 'UrlProtocols' ) );
		ApiResult::setIndexedTagName( $protocols, 'p' );

		return $this->getResult()->addValue( 'query', $property, $protocols );
	}

	public function appendDefaultOptions( $property ) {
		$options = User::getDefaultOptions();
		$options[ApiResult::META_BC_BOOLS] = array_keys( $options );
		return $this->getResult()->addValue( 'query', $property, $options );
	}

	private function formatParserTags( $item ) {
		return "<{$item}>";
	}

	public function appendSubscribedHooks( $property ) {
		$hooks = $this->getConfig()->get( 'Hooks' );
		$myWgHooks = $hooks;
		ksort( $myWgHooks );

		$data = array();
		foreach ( $myWgHooks as $name => $subscribers ) {
			$arr = array(
				'name' => $name,
				'subscribers' => array_map( array( 'SpecialVersion', 'arrayToString' ), $subscribers ),
			);

			ApiResult::setIndexedTagName( $arr['subscribers'], 's' );
			$data[] = $arr;
		}

		ApiResult::setIndexedTagName( $data, 'hook' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function getCacheMode( $params ) {
		// Messages for $wgExtraInterlanguageLinkPrefixes depend on user language
		if (
			count( $this->getConfig()->get( 'ExtraInterlanguageLinkPrefixes' ) ) &&
			!is_null( $params['prop'] ) &&
			in_array( 'interwikimap', $params['prop'] )
		) {
			return 'anon-public-user-private';
		}

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
					'libraries',
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

	protected function getExamplesMessages() {
		return array(
			'action=query&meta=siteinfo&siprop=general|namespaces|namespacealiases|statistics'
				=> 'apihelp-query+siteinfo-example-simple',
			'action=query&meta=siteinfo&siprop=interwikimap&sifilteriw=local'
				=> 'apihelp-query+siteinfo-example-interwiki',
			'action=query&meta=siteinfo&siprop=dbrepllag&sishowalldb='
				=> 'apihelp-query+siteinfo-example-replag',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Meta#siteinfo_.2F_si';
	}
}
