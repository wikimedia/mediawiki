<?php
/**
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

use MediaWiki\ExtensionInfo;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\ResourceLoader\SkinModule;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserOptionsLookup;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * A query action to return meta information about the wiki site.
 *
 * @ingroup API
 */
class ApiQuerySiteinfo extends ApiQueryBase {

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var UserGroupManager */
	private $userGroupManager;

	/** @var LanguageConverterFactory */
	private $languageConverterFactory;

	/** @var LanguageFactory */
	private $languageFactory;

	/** @var LanguageNameUtils */
	private $languageNameUtils;

	/** @var Language */
	private $contentLanguage;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var InterwikiLookup */
	private $interwikiLookup;

	/** @var Parser */
	private $parser;

	/** @var MagicWordFactory */
	private $magicWordFactory;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var SkinFactory */
	private $skinFactory;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/**
	 * @param ApiQuery $query
	 * @param string $moduleName
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param UserGroupManager $userGroupManager
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param LanguageFactory $languageFactory
	 * @param LanguageNameUtils $languageNameUtils
	 * @param Language $contentLanguage
	 * @param NamespaceInfo $namespaceInfo
	 * @param InterwikiLookup $interwikiLookup
	 * @param Parser $parser
	 * @param MagicWordFactory $magicWordFactory
	 * @param SpecialPageFactory $specialPageFactory
	 * @param SkinFactory $skinFactory
	 * @param ILoadBalancer $loadBalancer
	 * @param ReadOnlyMode $readOnlyMode
	 */
	public function __construct(
		ApiQuery $query,
		$moduleName,
		UserOptionsLookup $userOptionsLookup,
		UserGroupManager $userGroupManager,
		LanguageConverterFactory $languageConverterFactory,
		LanguageFactory $languageFactory,
		LanguageNameUtils $languageNameUtils,
		Language $contentLanguage,
		NamespaceInfo $namespaceInfo,
		InterwikiLookup $interwikiLookup,
		Parser $parser,
		MagicWordFactory $magicWordFactory,
		SpecialPageFactory $specialPageFactory,
		SkinFactory $skinFactory,
		ILoadBalancer $loadBalancer,
		ReadOnlyMode $readOnlyMode
	) {
		parent::__construct( $query, $moduleName, 'si' );
		$this->userOptionsLookup = $userOptionsLookup;
		$this->userGroupManager = $userGroupManager;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->languageFactory = $languageFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->contentLanguage = $contentLanguage;
		$this->namespaceInfo = $namespaceInfo;
		$this->interwikiLookup = $interwikiLookup;
		$this->parser = $parser;
		$this->magicWordFactory = $magicWordFactory;
		$this->specialPageFactory = $specialPageFactory;
		$this->skinFactory = $skinFactory;
		$this->loadBalancer = $loadBalancer;
		$this->readOnlyMode = $readOnlyMode;
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$done = [];
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
					$fit = $this->appendInterwikiMap( $p, $params['filteriw'] );
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
				case 'languagevariants':
					$fit = $this->appendLanguageVariants( $p );
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
				case 'uploaddialog':
					$fit = $this->appendUploadDialog( $p );
					break;
				default:
					ApiBase::dieDebug( __METHOD__, "Unknown prop=$p" ); // @codeCoverageIgnore
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
		$config = $this->getConfig();

		$data = [];
		$mainPage = Title::newMainPage();
		$data['mainpage'] = $mainPage->getPrefixedText();
		$data['base'] = wfExpandUrl( $mainPage->getFullURL(), PROTO_CURRENT );
		$data['sitename'] = $config->get( MainConfigNames::Sitename );
		$data['mainpageisdomainroot'] = (bool)$config->get( MainConfigNames::MainPageIsDomainRoot );

		// A logo can either be a relative or an absolute path
		// make sure we always return an absolute path
		$logo = SkinModule::getAvailableLogos( $config );
		$data['logo'] = wfExpandUrl( $logo['1x'], PROTO_RELATIVE );

		$data['generator'] = 'MediaWiki ' . MW_VERSION;

		$data['phpversion'] = PHP_VERSION;
		$data['phpsapi'] = PHP_SAPI;
		$data['dbtype'] = $config->get( MainConfigNames::DBtype );
		$data['dbversion'] = $this->getDB()->getServerVersion();

		$allowFrom = [ '' ];
		$allowException = true;
		if ( !$config->get( MainConfigNames::AllowExternalImages ) ) {
			$data['imagewhitelistenabled'] =
				(bool)$config->get( MainConfigNames::EnableImageWhitelist );
			$allowFrom = $config->get( MainConfigNames::AllowExternalImagesFrom );
			$allowException = !empty( $allowFrom );
		}
		if ( $allowException ) {
			$data['externalimages'] = (array)$allowFrom;
			ApiResult::setIndexedTagName( $data['externalimages'], 'prefix' );
		}

		$data['langconversion'] = !$this->languageConverterFactory->isConversionDisabled();
		$data['linkconversion'] = !$this->languageConverterFactory->isLinkConversionDisabled();
		// For backwards compatibility (soft deprecated since MW 1.36)
		$data['titleconversion'] = $data['linkconversion'];

		$contLangConverter = $this->languageConverterFactory->getLanguageConverter( $this->contentLanguage );
		if ( $this->contentLanguage->linkPrefixExtension() ) {
			$linkPrefixCharset = $this->contentLanguage->linkPrefixCharset();
			$data['linkprefixcharset'] = $linkPrefixCharset;
			// For backwards compatibility
			$data['linkprefix'] = "/^((?>.*[^$linkPrefixCharset]|))(.+)$/sDu";
		} else {
			$data['linkprefixcharset'] = '';
			$data['linkprefix'] = '';
		}

		$linktrail = $this->contentLanguage->linkTrail();
		$data['linktrail'] = $linktrail ?: '';

		$data['legaltitlechars'] = Title::legalChars();
		$data['invalidusernamechars'] = $config->get( MainConfigNames::InvalidUsernameCharacters );

		$data['allunicodefixes'] = (bool)$config->get( MainConfigNames::AllUnicodeFixes );
		$data['fixarabicunicode'] = true; // Config removed in 1.35, always true
		$data['fixmalayalamunicode'] = true; // Config removed in 1.35, always true

		$baseDir = $this->getConfig()->get( MainConfigNames::BaseDirectory );
		$git = SpecialVersion::getGitHeadSha1( $baseDir );
		if ( $git ) {
			$data['git-hash'] = $git;
			$data['git-branch'] =
				SpecialVersion::getGitCurrentBranch( $baseDir );
		}

		// 'case-insensitive' option is reserved for future
		$data['case'] =
			$config->get( MainConfigNames::CapitalLinks ) ? 'first-letter' : 'case-sensitive';
		$data['lang'] = $config->get( MainConfigNames::LanguageCode );

		$fallbacks = [];
		foreach ( $this->contentLanguage->getFallbackLanguages() as $code ) {
			$fallbacks[] = [ 'code' => $code ];
		}
		$data['fallback'] = $fallbacks;
		ApiResult::setIndexedTagName( $data['fallback'], 'lang' );

		if ( $contLangConverter->hasVariants() ) {
			$variants = [];
			foreach ( $contLangConverter->getVariants() as $code ) {
				$variants[] = [
					'code' => $code,
					'name' => $this->contentLanguage->getVariantname( $code ),
				];
			}
			$data['variants'] = $variants;
			ApiResult::setIndexedTagName( $data['variants'], 'lang' );
		}

		$data['rtl'] = $this->contentLanguage->isRTL();
		$data['fallback8bitEncoding'] = $this->contentLanguage->fallback8bitEncoding();

		$data['readonly'] = $this->readOnlyMode->isReadOnly();
		if ( $data['readonly'] ) {
			$data['readonlyreason'] = $this->readOnlyMode->getReason();
		}
		$data['writeapi'] = true; // Deprecated since MW 1.32

		$data['maxarticlesize'] = $config->get( MainConfigNames::MaxArticleSize ) * 1024;

		$tz = $config->get( MainConfigNames::Localtimezone );
		$offset = $config->get( MainConfigNames::LocalTZoffset );
		$data['timezone'] = $tz;
		$data['timeoffset'] = (int)$offset;
		$data['articlepath'] = $config->get( MainConfigNames::ArticlePath );
		$data['scriptpath'] = $config->get( MainConfigNames::ScriptPath );
		$data['script'] = $config->get( MainConfigNames::Script );
		$data['variantarticlepath'] = $config->get( MainConfigNames::VariantArticlePath );
		$data[ApiResult::META_BC_BOOLS][] = 'variantarticlepath';
		$data['server'] = $config->get( MainConfigNames::Server );
		$data['servername'] = $config->get( MainConfigNames::ServerName );
		$data['wikiid'] = WikiMap::getCurrentWikiId();
		$data['time'] = wfTimestamp( TS_ISO_8601, time() );

		$data['misermode'] = (bool)$config->get( MainConfigNames::MiserMode );

		$data['uploadsenabled'] = UploadBase::isEnabled();
		$data['maxuploadsize'] = UploadBase::getMaxUploadSize();
		$data['minuploadchunksize'] = ApiUpload::getMinUploadChunkSize( $config );

		$data['galleryoptions'] = $config->get( MainConfigNames::GalleryOptions );

		$data['thumblimits'] = $config->get( MainConfigNames::ThumbLimits );
		ApiResult::setArrayType( $data['thumblimits'], 'BCassoc' );
		ApiResult::setIndexedTagName( $data['thumblimits'], 'limit' );
		$data['imagelimits'] = [];
		ApiResult::setArrayType( $data['imagelimits'], 'BCassoc' );
		ApiResult::setIndexedTagName( $data['imagelimits'], 'limit' );
		foreach ( $config->get( MainConfigNames::ImageLimits ) as $k => $limit ) {
			$data['imagelimits'][$k] = [ 'width' => $limit[0], 'height' => $limit[1] ];
		}

		$favicon = $config->get( MainConfigNames::Favicon );
		if ( $favicon ) {
			// Expand any local path to full URL to improve API usability (T77093).
			$data['favicon'] = wfExpandUrl( $favicon );
		}

		$data['centralidlookupprovider'] =
			$config->get( MainConfigNames::CentralIdLookupProvider );
		$providerIds = array_keys( $config->get( MainConfigNames::CentralIdLookupProviders ) );
		$data['allcentralidlookupproviders'] = $providerIds;

		$data['interwikimagic'] = (bool)$config->get( MainConfigNames::InterwikiMagic );
		$data['magiclinks'] = $config->get( MainConfigNames::EnableMagicLinks );

		$data['categorycollation'] = $config->get( MainConfigNames::CategoryCollation );

		$data['nofollowlinks'] = $config->get( MainConfigNames::NoFollowLinks );
		$data['nofollownsexceptions'] = $config->get( MainConfigNames::NoFollowNsExceptions );
		$data['nofollowdomainexceptions'] = $config->get( MainConfigNames::NoFollowDomainExceptions );
		$data['externallinktarget'] = $config->get( MainConfigNames::ExternalLinkTarget );

		$this->getHookRunner()->onAPIQuerySiteInfoGeneralInfo( $this, $data );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendNamespaces( $property ) {
		$nsProtection = $this->getConfig()->get( MainConfigNames::NamespaceProtection );

		$data = [
			ApiResult::META_TYPE => 'assoc',
		];
		foreach (
			$this->contentLanguage->getFormattedNamespaces()
			as $ns => $title
		) {
			$data[$ns] = [
				'id' => (int)$ns,
				'case' => $this->namespaceInfo->isCapitalized( $ns ) ? 'first-letter' : 'case-sensitive',
			];
			ApiResult::setContentValue( $data[$ns], 'name', $title );
			$canonical = $this->namespaceInfo->getCanonicalName( $ns );

			$data[$ns]['subpages'] = $this->namespaceInfo->hasSubpages( $ns );

			if ( $canonical ) {
				$data[$ns]['canonical'] = strtr( $canonical, '_', ' ' );
			}

			$data[$ns]['content'] = $this->namespaceInfo->isContent( $ns );
			$data[$ns]['nonincludable'] = $this->namespaceInfo->isNonincludable( $ns );

			if ( isset( $nsProtection[$ns] ) ) {
				if ( is_array( $nsProtection[$ns] ) ) {
					$specificNs = implode( "|", array_filter( $nsProtection[$ns] ) );
				} elseif ( $nsProtection[$ns] !== '' ) {
					$specificNs = $nsProtection[$ns];
				}
				if ( isset( $specificNs ) && $specificNs !== '' ) {
					$data[$ns]['namespaceprotection'] = $specificNs;
				}
			}

			$contentmodel = $this->namespaceInfo->getNamespaceContentModel( $ns );
			if ( $contentmodel ) {
				$data[$ns]['defaultcontentmodel'] = $contentmodel;
			}
		}

		ApiResult::setArrayType( $data, 'assoc' );
		ApiResult::setIndexedTagName( $data, 'ns' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendNamespaceAliases( $property ) {
		$aliases = $this->contentLanguage->getNamespaceAliases();
		$namespaces = $this->contentLanguage->getNamespaces();
		$data = [];
		foreach ( $aliases as $title => $ns ) {
			if ( $namespaces[$ns] == $title ) {
				// Don't list duplicates
				continue;
			}
			$item = [
				'id' => (int)$ns
			];
			ApiResult::setContentValue( $item, 'alias', strtr( $title, '_', ' ' ) );
			$data[] = $item;
		}

		sort( $data );

		ApiResult::setIndexedTagName( $data, 'ns' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendSpecialPageAliases( $property ) {
		$data = [];
		$aliases = $this->contentLanguage->getSpecialPageAliases();
		foreach ( $this->specialPageFactory->getNames() as $specialpage ) {
			if ( isset( $aliases[$specialpage] ) ) {
				$arr = [ 'realname' => $specialpage, 'aliases' => $aliases[$specialpage] ];
				ApiResult::setIndexedTagName( $arr['aliases'], 'alias' );
				$data[] = $arr;
			}
		}
		ApiResult::setIndexedTagName( $data, 'specialpage' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendMagicWords( $property ) {
		$data = [];
		foreach (
			$this->contentLanguage->getMagicWords()
			as $magicword => $aliases
		) {
			$caseSensitive = array_shift( $aliases );
			$arr = [ 'name' => $magicword, 'aliases' => $aliases ];
			$arr['case-sensitive'] = (bool)$caseSensitive;
			ApiResult::setIndexedTagName( $arr['aliases'], 'alias' );
			$data[] = $arr;
		}
		ApiResult::setIndexedTagName( $data, 'magicword' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendInterwikiMap( $property, $filter ) {
		if ( $filter === 'local' ) {
			$local = true;
		} elseif ( $filter === '!local' ) {
			$local = false;
		} else {
			// $filter === null
			$local = null;
		}

		$params = $this->extractRequestParams();
		$langCode = $params['inlanguagecode'] ?? '';
		$interwikiMagic = $this->getConfig()->get( MainConfigNames::InterwikiMagic );

		if ( $interwikiMagic ) {
			$langNames = $this->languageNameUtils->getLanguageNames( $langCode );
		}

		$getPrefixes = $this->interwikiLookup->getAllPrefixes( $local );
		$extraLangPrefixes = $this->getConfig()->get( MainConfigNames::ExtraInterlanguageLinkPrefixes );
		$localInterwikis = $this->getConfig()->get( MainConfigNames::LocalInterwikis );
		$data = [];

		foreach ( $getPrefixes as $row ) {
			$prefix = $row['iw_prefix'];
			$val = [];
			$val['prefix'] = $prefix;
			if ( isset( $row['iw_local'] ) && $row['iw_local'] == '1' ) {
				$val['local'] = true;
			}
			if ( isset( $row['iw_trans'] ) && $row['iw_trans'] == '1' ) {
				$val['trans'] = true;
			}

			if ( $interwikiMagic && isset( $langNames[$prefix] ) ) {
				$val['language'] = $langNames[$prefix];
			}
			if ( in_array( $prefix, $localInterwikis ) ) {
				$val['localinterwiki'] = true;
			}
			if ( $interwikiMagic && in_array( $prefix, $extraLangPrefixes ) ) {
				$val['extralanglink'] = true;

				$linktext = $this->msg( "interlanguage-link-$prefix" );
				if ( !$linktext->isDisabled() ) {
					$val['linktext'] = $linktext->text();
				}

				$sitename = $this->msg( "interlanguage-link-sitename-$prefix" );
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
		$data = [];
		$showHostnames = $this->getConfig()->get( MainConfigNames::ShowHostnames );
		if ( $includeAll ) {
			if ( !$showHostnames ) {
				$this->dieWithError( 'apierror-siteinfo-includealldenied', 'includeAllDenied' );
			}

			$lags = $this->loadBalancer->getLagTimes();
			foreach ( $lags as $i => $lag ) {
				$data[] = [
					'host' => $this->loadBalancer->getServerName( $i ),
					'lag' => $lag
				];
			}
		} else {
			list( , $lag, $index ) = $this->loadBalancer->getMaxLag();
			$data[] = [
				'host' => $showHostnames
						? $this->loadBalancer->getServerName( $index )
						: '',
				'lag' => $lag
			];
		}

		ApiResult::setIndexedTagName( $data, 'db' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendStatistics( $property ) {
		$data = [];
		$data['pages'] = (int)SiteStats::pages();
		$data['articles'] = (int)SiteStats::articles();
		$data['edits'] = (int)SiteStats::edits();
		$data['images'] = (int)SiteStats::images();
		$data['users'] = (int)SiteStats::users();
		$data['activeusers'] = (int)SiteStats::activeUsers();
		$data['admins'] = (int)SiteStats::numberingroup( 'sysop' );
		$data['jobs'] = (int)SiteStats::jobs();

		$this->getHookRunner()->onAPIQuerySiteInfoStatisticsInfo( $data );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendUserGroups( $property, $numberInGroup ) {
		$config = $this->getConfig();

		$data = [];
		$result = $this->getResult();
		$allGroups = array_values( $this->userGroupManager->listAllGroups() );
		foreach ( $config->get( MainConfigNames::GroupPermissions ) as $group => $permissions ) {
			$arr = [
				'name' => $group,
				'rights' => array_keys( $permissions, true ),
			];

			if ( $numberInGroup ) {
				$autopromote = $config->get( MainConfigNames::Autopromote );

				if ( $group == 'user' ) {
					$arr['number'] = SiteStats::users();
				// '*' and autopromote groups have no size
				} elseif ( $group !== '*' && !isset( $autopromote[$group] ) ) {
					$arr['number'] = SiteStats::numberingroup( $group );
				}
			}

			$groupArr = [
				'add' => $config->get( MainConfigNames::AddGroups ),
				'remove' => $config->get( MainConfigNames::RemoveGroups ),
				'add-self' => $config->get( MainConfigNames::GroupsAddToSelf ),
				'remove-self' => $config->get( MainConfigNames::GroupsRemoveFromSelf )
			];

			foreach ( $groupArr as $type => $rights ) {
				if ( isset( $rights[$group] ) ) {
					if ( $rights[$group] === true ) {
						$groups = $allGroups;
					} else {
						$groups = array_intersect( $rights[$group], $allGroups );
					}
					if ( $groups ) {
						$arr[$type] = $groups;
						ApiResult::setArrayType( $arr[$type], 'BCarray' );
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
		$data = [];
		foreach (
			array_unique( $this->getConfig()->get( MainConfigNames::FileExtensions ) ) as $ext
		) {
			$data[] = [ 'ext' => $ext ];
		}
		ApiResult::setIndexedTagName( $data, 'fe' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendInstalledLibraries( $property ) {
		$baseDir = $this->getConfig()->get( MainConfigNames::BaseDirectory );
		$path = "$baseDir/vendor/composer/installed.json";
		if ( !file_exists( $path ) ) {
			return true;
		}

		$data = [];
		$installed = new ComposerInstalled( $path );
		foreach ( $installed->getInstalledDependencies() as $name => $info ) {
			if ( strpos( $info['type'], 'mediawiki-' ) === 0 ) {
				// Skip any extensions or skins since they'll be listed
				// in their proper section
				continue;
			}
			$data[] = [
				'name' => $name,
				'version' => $info['version'],
			];
		}
		ApiResult::setIndexedTagName( $data, 'library' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendExtensions( $property ) {
		$data = [];
		$credits = SpecialVersion::getCredits(
			ExtensionRegistry::getInstance(),
			$this->getConfig()
		);
		foreach ( $credits as $type => $extensions ) {
			foreach ( $extensions as $ext ) {
				$ret = [];
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
					// Can be a string or [ key, param1, param2, ... ]
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
					}

					if ( ExtensionInfo::getLicenseFileNames( $extensionPath ) ) {
						$ret['license-name'] = $ext['license-name'] ?? '';
						$ret['license'] = SpecialPage::getTitleFor(
							'Version',
							"License/{$ext['name']}"
						)->getLinkURL();
					}

					if ( ExtensionInfo::getAuthorsFileName( $extensionPath ) ) {
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
		$rightsPage = $config->get( MainConfigNames::RightsPage );
		// The default value is null, but the installer sets it to empty string
		if ( strlen( (string)$rightsPage ) ) {
			$title = Title::newFromText( $rightsPage );
			$url = wfExpandUrl( $title->getLinkURL(), PROTO_CURRENT );
		} else {
			$title = false;
			$url = $config->get( MainConfigNames::RightsUrl );
		}
		$text = $config->get( MainConfigNames::RightsText );
		if ( $title && !strlen( (string)$text ) ) {
			$text = $title->getPrefixedText();
		}

		$data = [
			'url' => (string)$url,
			'text' => (string)$text,
		];

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendRestrictions( $property ) {
		$config = $this->getConfig();
		$data = [
			'types' => $config->get( MainConfigNames::RestrictionTypes ),
			'levels' => $config->get( MainConfigNames::RestrictionLevels ),
			'cascadinglevels' => $config->get( MainConfigNames::CascadingRestrictionLevels ),
			'semiprotectedlevels' => $config->get( MainConfigNames::SemiprotectedRestrictionLevels ),
		];

		ApiResult::setArrayType( $data['types'], 'BCarray' );
		ApiResult::setArrayType( $data['levels'], 'BCarray' );
		ApiResult::setArrayType( $data['cascadinglevels'], 'BCarray' );
		ApiResult::setArrayType( $data['semiprotectedlevels'], 'BCarray' );

		ApiResult::setIndexedTagName( $data['types'], 'type' );
		ApiResult::setIndexedTagName( $data['levels'], 'level' );
		ApiResult::setIndexedTagName( $data['cascadinglevels'], 'level' );
		ApiResult::setIndexedTagName( $data['semiprotectedlevels'], 'level' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendLanguages( $property ) {
		$params = $this->extractRequestParams();
		$langCode = $params['inlanguagecode'] ?? '';
		$langNames = $this->languageNameUtils->getLanguageNames( $langCode );

		$data = [];

		foreach ( $langNames as $code => $name ) {
			$lang = [
				'code' => $code,
				'bcp47' => LanguageCode::bcp47( $code ),
			];
			ApiResult::setContentValue( $lang, 'name', $name );
			$data[] = $lang;
		}
		ApiResult::setIndexedTagName( $data, 'lang' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	// Export information about which page languages will trigger
	// language conversion. (T153341)
	public function appendLanguageVariants( $property ) {
		$langNames = LanguageConverter::$languagesWithVariants;
		if ( $this->languageConverterFactory->isConversionDisabled() ) {
			// Ensure result is empty if language conversion is disabled.
			$langNames = [];
		}
		sort( $langNames );

		$data = [];
		foreach ( $langNames as $langCode ) {
			$lang = $this->languageFactory->getLanguage( $langCode );
			$langConverter = $this->languageConverterFactory->getLanguageConverter( $lang );
			if ( !$langConverter->hasVariants() ) {
				// Only languages which have variants should be listed
				continue;
			}
			$data[$langCode] = [];
			ApiResult::setIndexedTagName( $data[$langCode], 'variant' );
			ApiResult::setArrayType( $data[$langCode], 'kvp', 'code' );

			$variants = $langConverter->getVariants();
			sort( $variants );
			foreach ( $variants as $v ) {
				$fallbacks = $langConverter->getVariantFallbacks( $v );
				if ( !is_array( $fallbacks ) ) {
					$fallbacks = [ $fallbacks ];
				}
				$data[$langCode][$v] = [
					'fallbacks' => $fallbacks,
				];
				ApiResult::setIndexedTagName(
					$data[$langCode][$v]['fallbacks'], 'variant'
				);
			}
		}
		ApiResult::setIndexedTagName( $data, 'lang' );
		ApiResult::setArrayType( $data, 'kvp', 'code' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function appendSkins( $property ) {
		$data = [];
		$allowed = $this->skinFactory->getAllowedSkins();
		$default = Skin::normalizeKey( 'default' );
		$skinNames = $this->skinFactory->getInstalledSkins();

		foreach ( $skinNames as $name => $displayName ) {
			$msg = $this->msg( "skinname-{$name}" );
			$code = $this->getParameter( 'inlanguagecode' );
			if ( $code && $this->languageNameUtils->isValidCode( $code ) ) {
				$msg->inLanguage( $code );
			} else {
				$msg->inContentLanguage();
			}
			if ( $msg->exists() ) {
				$displayName = $msg->text();
			}
			$skin = [ 'code' => $name ];
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
		$tags = array_map(
			static function ( $item ) {
				return "<$item>";
			},
			$this->parser->getTags()
		);
		ApiResult::setArrayType( $tags, 'BCarray' );
		ApiResult::setIndexedTagName( $tags, 't' );

		return $this->getResult()->addValue( 'query', $property, $tags );
	}

	public function appendFunctionHooks( $property ) {
		$hooks = $this->parser->getFunctionHooks();
		ApiResult::setArrayType( $hooks, 'BCarray' );
		ApiResult::setIndexedTagName( $hooks, 'h' );

		return $this->getResult()->addValue( 'query', $property, $hooks );
	}

	public function appendVariables( $property ) {
		$variables = $this->magicWordFactory->getVariableIDs();
		ApiResult::setArrayType( $variables, 'BCarray' );
		ApiResult::setIndexedTagName( $variables, 'v' );

		return $this->getResult()->addValue( 'query', $property, $variables );
	}

	public function appendProtocols( $property ) {
		// Make a copy of the global so we don't try to set the _element key of it - T47130
		$protocols = array_values( $this->getConfig()->get( MainConfigNames::UrlProtocols ) );
		ApiResult::setArrayType( $protocols, 'BCarray' );
		ApiResult::setIndexedTagName( $protocols, 'p' );

		return $this->getResult()->addValue( 'query', $property, $protocols );
	}

	public function appendDefaultOptions( $property ) {
		$options = $this->userOptionsLookup->getDefaultOptions();
		$options[ApiResult::META_BC_BOOLS] = array_keys( $options );
		return $this->getResult()->addValue( 'query', $property, $options );
	}

	public function appendUploadDialog( $property ) {
		$config = $this->getConfig()->get( MainConfigNames::UploadDialog );
		return $this->getResult()->addValue( 'query', $property, $config );
	}

	public function appendSubscribedHooks( $property ) {
		$hooks = $this->getConfig()->get( MainConfigNames::Hooks );
		$myWgHooks = $hooks;
		ksort( $myWgHooks );

		$data = [];
		foreach ( $myWgHooks as $name => $subscribers ) {
			$arr = [
				'name' => $name,
				'subscribers' => array_map( [ SpecialVersion::class, 'arrayToString' ], $subscribers ),
			];

			ApiResult::setArrayType( $arr['subscribers'], 'array' );
			ApiResult::setIndexedTagName( $arr['subscribers'], 's' );
			$data[] = $arr;
		}

		ApiResult::setIndexedTagName( $data, 'hook' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	public function getCacheMode( $params ) {
		// Messages for $wgExtraInterlanguageLinkPrefixes depend on user language
		if (
			count( $this->getConfig()->get( MainConfigNames::ExtraInterlanguageLinkPrefixes ) ) &&
			$params['prop'] !== null &&
			in_array( 'interwikimap', $params['prop'] )
		) {
			return 'anon-public-user-private';
		}

		return 'public';
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				ParamValidator::PARAM_DEFAULT => 'general',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
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
					'languagevariants',
					'skins',
					'extensiontags',
					'functionhooks',
					'showhooks',
					'variables',
					'protocols',
					'defaultoptions',
					'uploaddialog',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'filteriw' => [
				ParamValidator::PARAM_TYPE => [
					'local',
					'!local',
				]
			],
			'showalldb' => false,
			'numberingroup' => false,
			'inlanguagecode' => null,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&meta=siteinfo&siprop=general|namespaces|namespacealiases|statistics'
				=> 'apihelp-query+siteinfo-example-simple',
			'action=query&meta=siteinfo&siprop=interwikimap&sifilteriw=local'
				=> 'apihelp-query+siteinfo-example-interwiki',
			'action=query&meta=siteinfo&siprop=dbrepllag&sishowalldb='
				=> 'apihelp-query+siteinfo-example-replag',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Siteinfo';
	}
}
