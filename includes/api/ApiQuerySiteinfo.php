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

namespace MediaWiki\Api;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\ResourceLoader\SkinModule;
use MediaWiki\SiteStats\SiteStats;
use MediaWiki\Skin\Skin;
use MediaWiki\Skin\SkinFactory;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Specials\SpecialVersion;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserGroupManager;
use MediaWiki\Utils\ExtensionInfo;
use MediaWiki\Utils\GitInfo;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\WikiMap\WikiMap;
use UploadBase;
use UploadFromUrl;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * A query action to return meta information about the wiki site.
 *
 * @ingroup API
 */
class ApiQuerySiteinfo extends ApiQueryBase {

	private UserOptionsLookup $userOptionsLookup;
	private UserGroupManager $userGroupManager;
	private HookContainer $hookContainer;
	private LanguageConverterFactory $languageConverterFactory;
	private LanguageFactory $languageFactory;
	private LanguageNameUtils $languageNameUtils;
	private Language $contentLanguage;
	private NamespaceInfo $namespaceInfo;
	private InterwikiLookup $interwikiLookup;
	private ParserFactory $parserFactory;
	private MagicWordFactory $magicWordFactory;
	private SpecialPageFactory $specialPageFactory;
	private SkinFactory $skinFactory;
	private ILoadBalancer $loadBalancer;
	private ReadOnlyMode $readOnlyMode;
	private UrlUtils $urlUtils;
	private TempUserConfig $tempUserConfig;
	private GroupPermissionsLookup $groupPermissionsLookup;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		UserOptionsLookup $userOptionsLookup,
		UserGroupManager $userGroupManager,
		HookContainer $hookContainer,
		LanguageConverterFactory $languageConverterFactory,
		LanguageFactory $languageFactory,
		LanguageNameUtils $languageNameUtils,
		Language $contentLanguage,
		NamespaceInfo $namespaceInfo,
		InterwikiLookup $interwikiLookup,
		ParserFactory $parserFactory,
		MagicWordFactory $magicWordFactory,
		SpecialPageFactory $specialPageFactory,
		SkinFactory $skinFactory,
		ILoadBalancer $loadBalancer,
		ReadOnlyMode $readOnlyMode,
		UrlUtils $urlUtils,
		TempUserConfig $tempUserConfig,
		GroupPermissionsLookup $groupPermissionsLookup
	) {
		parent::__construct( $query, $moduleName, 'si' );
		$this->userOptionsLookup = $userOptionsLookup;
		$this->userGroupManager = $userGroupManager;
		$this->hookContainer = $hookContainer;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->languageFactory = $languageFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->contentLanguage = $contentLanguage;
		$this->namespaceInfo = $namespaceInfo;
		$this->interwikiLookup = $interwikiLookup;
		$this->parserFactory = $parserFactory;
		$this->magicWordFactory = $magicWordFactory;
		$this->specialPageFactory = $specialPageFactory;
		$this->skinFactory = $skinFactory;
		$this->loadBalancer = $loadBalancer;
		$this->readOnlyMode = $readOnlyMode;
		$this->urlUtils = $urlUtils;
		$this->tempUserConfig = $tempUserConfig;
		$this->groupPermissionsLookup = $groupPermissionsLookup;
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$done = [];
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
				case 'autocreatetempuser':
					$fit = $this->appendAutoCreateTempUser( $p );
					break;
				case 'clientlibraries':
					$fit = $this->appendInstalledClientLibraries( $p );
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
				case 'autopromote':
					$fit = $this->appendAutoPromote( $p );
					break;
				case 'autopromoteonce':
					$fit = $this->appendAutoPromoteOnce( $p );
					break;
				case 'copyuploaddomains':
					$fit = $this->appendCopyUploadDomains( $p );
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

	protected function appendGeneralInfo( string $property ): bool {
		$config = $this->getConfig();
		$mainPage = Title::newMainPage();
		$logo = SkinModule::getAvailableLogos( $config, $this->getLanguage()->getCode() );

		$data = [
			'mainpage' => $mainPage->getPrefixedText(),
			'base' => (string)$this->urlUtils->expand( $mainPage->getFullURL(), PROTO_CURRENT ),
			'sitename' => $config->get( MainConfigNames::Sitename ),
			'mainpageisdomainroot' => (bool)$config->get( MainConfigNames::MainPageIsDomainRoot ),

			// A logo can either be a relative or an absolute path
			// make sure we always return an absolute path
			'logo' => (string)$this->urlUtils->expand( $logo['1x'], PROTO_RELATIVE ),

			'generator' => 'MediaWiki ' . MW_VERSION,

			'phpversion' => PHP_VERSION,
			'phpsapi' => PHP_SAPI,
			'dbtype' => $config->get( MainConfigNames::DBtype ),
			'dbversion' => $this->getDB()->getServerVersion(),
		];

		$allowFrom = [ '' ];
		$allowException = true;
		if ( !$config->get( MainConfigNames::AllowExternalImages ) ) {
			$data['imagewhitelistenabled'] =
				(bool)$config->get( MainConfigNames::EnableImageWhitelist );
			$allowFrom = $config->get( MainConfigNames::AllowExternalImagesFrom );
			$allowException = (bool)$allowFrom;
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

		$data['linktrail'] = $this->contentLanguage->linkTrail() ?: '';

		$data['legaltitlechars'] = Title::legalChars();
		$data['invalidusernamechars'] = $config->get( MainConfigNames::InvalidUsernameCharacters );

		$data['allunicodefixes'] = (bool)$config->get( MainConfigNames::AllUnicodeFixes );
		$data['fixarabicunicode'] = true; // Config removed in 1.35, always true
		$data['fixmalayalamunicode'] = true; // Config removed in 1.35, always true

		$git = GitInfo::repo()->getHeadSHA1();
		if ( $git ) {
			$data['git-hash'] = $git;
			$data['git-branch'] = GitInfo::repo()->getCurrentBranch();
		}

		// 'case-insensitive' option is reserved for future
		$data['case'] =
			$config->get( MainConfigNames::CapitalLinks ) ? 'first-letter' : 'case-sensitive';
		$data['lang'] = $config->get( MainConfigNames::LanguageCode );

		$data['fallback'] = [];
		foreach ( $this->contentLanguage->getFallbackLanguages() as $code ) {
			$data['fallback'][] = [ 'code' => $code ];
		}
		ApiResult::setIndexedTagName( $data['fallback'], 'lang' );

		if ( $contLangConverter->hasVariants() ) {
			$data['variants'] = [];
			foreach ( $contLangConverter->getVariants() as $code ) {
				$data['variants'][] = [
					'code' => $code,
					'name' => $this->contentLanguage->getVariantname( $code ),
				];
			}
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

		$data['timezone'] = $config->get( MainConfigNames::Localtimezone );
		$data['timeoffset'] = (int)( $config->get( MainConfigNames::LocalTZoffset ) );
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
		foreach ( $config->get( MainConfigNames::ImageLimits ) as $k => $limit ) {
			$data['imagelimits'][$k] = [ 'width' => $limit[0], 'height' => $limit[1] ];
		}
		ApiResult::setArrayType( $data['imagelimits'], 'BCassoc' );
		ApiResult::setIndexedTagName( $data['imagelimits'], 'limit' );

		$favicon = $config->get( MainConfigNames::Favicon );
		if ( $favicon ) {
			// Expand any local path to full URL to improve API usability (T77093).
			$data['favicon'] = (string)$this->urlUtils->expand( $favicon );
		}

		$data['centralidlookupprovider'] = $config->get( MainConfigNames::CentralIdLookupProvider );
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

	protected function appendNamespaces( string $property ): bool {
		$nsProtection = $this->getConfig()->get( MainConfigNames::NamespaceProtection );

		$data = [ ApiResult::META_TYPE => 'assoc' ];
		foreach ( $this->contentLanguage->getFormattedNamespaces() as $ns => $title ) {
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

			$specificNs = $nsProtection[$ns] ?? '';
			if ( is_array( $specificNs ) ) {
				$specificNs = implode( "|", array_filter( $specificNs ) );
			}
			if ( $specificNs !== '' ) {
				$data[$ns]['namespaceprotection'] = $specificNs;
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

	protected function appendNamespaceAliases( string $property ): bool {
		$aliases = $this->contentLanguage->getNamespaceAliases();
		$namespaces = $this->contentLanguage->getNamespaces();
		$data = [];
		foreach ( $aliases as $title => $ns ) {
			if ( $namespaces[$ns] == $title ) {
				// Don't list duplicates
				continue;
			}
			$item = [ 'id' => (int)$ns ];
			ApiResult::setContentValue( $item, 'alias', strtr( $title, '_', ' ' ) );
			$data[] = $item;
		}

		sort( $data );

		ApiResult::setIndexedTagName( $data, 'ns' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendSpecialPageAliases( string $property ): bool {
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

	protected function appendMagicWords( string $property ): bool {
		$data = [];
		foreach ( $this->contentLanguage->getMagicWords() as $name => $aliases ) {
			$caseSensitive = (bool)array_shift( $aliases );
			$arr = [
				'name' => $name,
				'aliases' => $aliases,
				'case-sensitive' => $caseSensitive,
			];
			ApiResult::setIndexedTagName( $arr['aliases'], 'alias' );
			$data[] = $arr;
		}
		ApiResult::setIndexedTagName( $data, 'magicword' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendInterwikiMap( string $property, ?string $filter ): bool {
		$local = $filter ? $filter === 'local' : null;

		$params = $this->extractRequestParams();
		$langCode = $params['inlanguagecode'] ?? '';
		$interwikiMagic = $this->getConfig()->get( MainConfigNames::InterwikiMagic );

		if ( $interwikiMagic ) {
			$langNames = $this->languageNameUtils->getLanguageNames( $langCode );
		}

		$extraLangPrefixes = $this->getConfig()->get( MainConfigNames::ExtraInterlanguageLinkPrefixes );
		$extraLangCodeMap = $this->getConfig()->get( MainConfigNames::InterlanguageLinkCodeMap );
		$localInterwikis = $this->getConfig()->get( MainConfigNames::LocalInterwikis );
		$data = [];

		foreach ( $this->interwikiLookup->getAllPrefixes( $local ) as $row ) {
			$prefix = $row['iw_prefix'];
			$val = [];
			$val['prefix'] = $prefix;
			if ( $row['iw_local'] ?? false ) {
				$val['local'] = true;
			}
			if ( $row['iw_trans'] ?? false ) {
				$val['trans'] = true;
			}

			if ( $interwikiMagic && isset( $langNames[$prefix] ) ) {
				$val['language'] = $langNames[$prefix];
				$standard = LanguageCode::replaceDeprecatedCodes( $prefix );
				if ( $standard !== $prefix ) {
					# Note that even if this code is deprecated, it should
					# only be remapped if extralanglink (set below) is false.
					$val['deprecated'] = $standard;
				}
				$val['bcp47'] = LanguageCode::bcp47( $standard );
			}
			if ( in_array( $prefix, $localInterwikis ) ) {
				$val['localinterwiki'] = true;
			}
			if ( $interwikiMagic && in_array( $prefix, $extraLangPrefixes ) ) {
				$val['extralanglink'] = true;
				$val['code'] = $extraLangCodeMap[$prefix] ?? $prefix;
				$val['bcp47'] = LanguageCode::bcp47( $val['code'] );

				$linktext = $this->msg( "interlanguage-link-$prefix" );
				if ( !$linktext->isDisabled() ) {
					$val['linktext'] = $linktext->text();
				}

				$sitename = $this->msg( "interlanguage-link-sitename-$prefix" );
				if ( !$sitename->isDisabled() ) {
					$val['sitename'] = $sitename->text();
				}
			}

			$val['url'] = (string)$this->urlUtils->expand( $row['iw_url'], PROTO_CURRENT );
			$val['protorel'] = str_starts_with( $row['iw_url'], '//' );
			if ( ( $row['iw_wikiid'] ?? '' ) !== '' ) {
				$val['wikiid'] = $row['iw_wikiid'];
			}
			if ( ( $row['iw_api'] ?? '' ) !== '' ) {
				$val['api'] = $row['iw_api'];
			}

			$data[] = $val;
		}

		ApiResult::setIndexedTagName( $data, 'iw' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendDbReplLagInfo( string $property, bool $includeAll ): bool {
		$data = [];
		$showHostnames = $this->getConfig()->get( MainConfigNames::ShowHostnames );
		if ( $includeAll ) {
			if ( !$showHostnames ) {
				$this->dieWithError( 'apierror-siteinfo-includealldenied', 'includeAllDenied' );
			}

			foreach ( $this->loadBalancer->getLagTimes() as $i => $lag ) {
				$data[] = [
					'host' => $this->loadBalancer->getServerName( $i ),
					'lag' => $lag
				];
			}
		} else {
			[ , $lag, $index ] = $this->loadBalancer->getMaxLag();
			$data[] = [
				'host' => $showHostnames ? $this->loadBalancer->getServerName( $index ) : '',
				'lag' => $lag
			];
		}

		ApiResult::setIndexedTagName( $data, 'db' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendStatistics( string $property ): bool {
		$data = [
			'pages' => SiteStats::pages(),
			'articles' => SiteStats::articles(),
			'edits' => SiteStats::edits(),
			'images' => SiteStats::images(),
			'users' => SiteStats::users(),
			'activeusers' => SiteStats::activeUsers(),
			'admins' => SiteStats::numberingroup( 'sysop' ),
			'jobs' => SiteStats::jobs(),
		];

		$this->getHookRunner()->onAPIQuerySiteInfoStatisticsInfo( $data );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendUserGroups( string $property, bool $numberInGroup ): bool {
		$config = $this->getConfig();

		$data = [];
		$result = $this->getResult();
		$allGroups = $this->userGroupManager->listAllGroups();
		$allImplicitGroups = $this->userGroupManager->listAllImplicitGroups();
		foreach ( array_merge( $allImplicitGroups, $allGroups ) as $group ) {
			$arr = [
				'name' => $group,
				'rights' => $this->groupPermissionsLookup->getGrantedPermissions( $group ),
				// TODO: Also expose the list of revoked permissions somehow.
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

			$groupArr = $this->userGroupManager->getGroupsChangeableByGroup( $group );

			foreach ( $groupArr as $type => $groups ) {
				$groups = array_values( array_intersect( $groups, $allGroups ) );
				if ( $groups ) {
					$arr[$type] = $groups;
					ApiResult::setArrayType( $arr[$type], 'BCarray' );
					ApiResult::setIndexedTagName( $arr[$type], 'group' );
				}
			}

			ApiResult::setIndexedTagName( $arr['rights'], 'permission' );
			$data[] = $arr;
		}

		ApiResult::setIndexedTagName( $data, 'group' );

		return $result->addValue( 'query', $property, $data );
	}

	protected function appendAutoCreateTempUser( string $property ): bool {
		$data = [ 'enabled' => $this->tempUserConfig->isEnabled() ];
		if ( $this->tempUserConfig->isKnown() ) {
			$data['matchPatterns'] = $this->tempUserConfig->getMatchPatterns();
		}
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendFileExtensions( string $property ): bool {
		$data = [];
		foreach (
			array_unique( $this->getConfig()->get( MainConfigNames::FileExtensions ) ) as $ext
		) {
			$data[] = [ 'ext' => $ext ];
		}
		ApiResult::setIndexedTagName( $data, 'fe' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendInstalledClientLibraries( string $property ): bool {
		$data = [];
		foreach ( SpecialVersion::parseForeignResources() as $name => $info ) {
			$data[] = [
				// Can't use $name as it is version suffixed (as multiple versions
				// of a library may exist, provided by different skins/extensions)
				'name' => $info['name'],
				'version' => $info['version'],
			];
		}
		ApiResult::setIndexedTagName( $data, 'library' );
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendInstalledLibraries( string $property ): bool {
		$credits = SpecialVersion::getCredits(
			ExtensionRegistry::getInstance(),
			$this->getConfig()
		);
		$data = [];
		foreach ( SpecialVersion::parseComposerInstalled( $credits ) as $name => $info ) {
			if ( str_starts_with( $info['type'], 'mediawiki-' ) ) {
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

	protected function appendExtensions( string $property ): bool {
		$data = [];
		$credits = SpecialVersion::getCredits(
			ExtensionRegistry::getInstance(),
			$this->getConfig()
		);
		foreach ( $credits as $type => $extensions ) {
			foreach ( $extensions as $ext ) {
				$ret = [ 'type' => $type ];
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

	protected function appendRightsInfo( string $property ): bool {
		$config = $this->getConfig();
		$title = Title::newFromText( $config->get( MainConfigNames::RightsPage ) );
		if ( $title ) {
			$url = $this->urlUtils->expand( $title->getLinkURL(), PROTO_CURRENT );
		} else {
			$url = $config->get( MainConfigNames::RightsUrl );
		}
		$text = $config->get( MainConfigNames::RightsText ) ?? '';
		if ( $text === '' && $title ) {
			$text = $title->getPrefixedText();
		}

		$data = [
			'url' => (string)$url,
			'text' => (string)$text,
		];

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	protected function appendRestrictions( string $property ): bool {
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

	public function appendLanguages( string $property ): bool {
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
	public function appendLanguageVariants( string $property ): bool {
		$langNames = $this->languageConverterFactory->isConversionDisabled() ? [] :
			LanguageConverter::$languagesWithVariants;
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
				$data[$langCode][$v] = [
					'fallbacks' => (array)$langConverter->getVariantFallbacks( $v ),
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

	public function appendSkins( string $property ): bool {
		$data = [];
		$allowed = $this->skinFactory->getAllowedSkins();
		$default = Skin::normalizeKey( 'default' );

		foreach ( $this->skinFactory->getInstalledSkins() as $name => $displayName ) {
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

	public function appendExtensionTags( string $property ): bool {
		$tags = array_map(
			static function ( $item ) {
				return "<$item>";
			},
			$this->parserFactory->getMainInstance()->getTags()
		);
		ApiResult::setArrayType( $tags, 'BCarray' );
		ApiResult::setIndexedTagName( $tags, 't' );

		return $this->getResult()->addValue( 'query', $property, $tags );
	}

	public function appendFunctionHooks( string $property ): bool {
		$hooks = $this->parserFactory->getMainInstance()->getFunctionHooks();
		ApiResult::setArrayType( $hooks, 'BCarray' );
		ApiResult::setIndexedTagName( $hooks, 'h' );

		return $this->getResult()->addValue( 'query', $property, $hooks );
	}

	public function appendVariables( string $property ): bool {
		$variables = $this->magicWordFactory->getVariableIDs();
		ApiResult::setArrayType( $variables, 'BCarray' );
		ApiResult::setIndexedTagName( $variables, 'v' );

		return $this->getResult()->addValue( 'query', $property, $variables );
	}

	public function appendProtocols( string $property ): bool {
		// Make a copy of the global so we don't try to set the _element key of it - T47130
		$protocols = array_values( $this->getConfig()->get( MainConfigNames::UrlProtocols ) );
		ApiResult::setArrayType( $protocols, 'BCarray' );
		ApiResult::setIndexedTagName( $protocols, 'p' );

		return $this->getResult()->addValue( 'query', $property, $protocols );
	}

	public function appendDefaultOptions( string $property ): bool {
		$options = $this->userOptionsLookup->getDefaultOptions( null );
		$options[ApiResult::META_BC_BOOLS] = array_keys( $options );
		return $this->getResult()->addValue( 'query', $property, $options );
	}

	public function appendUploadDialog( string $property ): bool {
		$config = $this->getConfig()->get( MainConfigNames::UploadDialog );
		return $this->getResult()->addValue( 'query', $property, $config );
	}

	private function getAutoPromoteConds(): array {
		$allowedConditions = [];
		foreach ( get_defined_constants() as $constantName => $constantValue ) {
			if ( strpos( $constantName, 'APCOND_' ) !== false ) {
				$allowedConditions[$constantName] = $constantValue;
			}
		}
		return $allowedConditions;
	}

	private function processAutoPromote( array $input, array $allowedConditions ): array {
		$data = [];
		foreach ( $input as $groupName => $conditions ) {
			$row = $this->recAutopromote( $conditions, $allowedConditions );
			if ( !isset( $row[0] ) || is_string( $row ) ) {
				$row = [ $row ];
			}
			$data[$groupName] = $row;
		}
		return $data;
	}

	private function appendAutoPromote( string $property ): bool {
		return $this->getResult()->addValue(
			'query',
			$property,
			$this->processAutoPromote(
				$this->getConfig()->get( MainConfigNames::Autopromote ),
				$this->getAutoPromoteConds()
			)
		);
	}

	private function appendAutoPromoteOnce( string $property ): bool {
		$allowedConditions = $this->getAutoPromoteConds();
		$data = [];
		foreach ( $this->getConfig()->get( MainConfigNames::AutopromoteOnce ) as $key => $value ) {
			$data[$key] = $this->processAutoPromote( $value, $allowedConditions );
		}
		return $this->getResult()->addValue( 'query', $property, $data );
	}

	private function appendCopyUploadDomains( string $property ): bool {
		$allowedHosts = UploadFromUrl::getAllowedHosts();
		ApiResult::setIndexedTagName( $allowedHosts, 'domain' );
		return $this->getResult()->addValue(
			'query',
			$property,
			$allowedHosts
		);
	}

	/**
	 * @param array|int|string $cond
	 * @param array $allowedConditions
	 * @return array|string
	 */
	private function recAutopromote( $cond, $allowedConditions ) {
		$config = [];
		// First, checks if $cond is an array
		if ( is_array( $cond ) ) {
			// Checks if $cond[0] is a valid operand
			if ( in_array( $cond[0], UserGroupManager::VALID_OPS, true ) ) {
				$config['operand'] = $cond[0];
				// Traversal checks conditions
				foreach ( array_slice( $cond, 1 ) as $value ) {
					$config[] = $this->recAutopromote( $value, $allowedConditions );
				}
			} elseif ( is_string( $cond[0] ) ) {
				// Returns $cond directly, if $cond[0] is a string
				$config = $cond;
			} else {
				// When $cond is equal to an APCOND_ constant value
				$params = array_slice( $cond, 1 );
				if ( $params === [ null ] ) {
					// Special casing for these conditions and their default of null,
					// to replace their values with $wgAutoConfirmCount/$wgAutoConfirmAge as appropriate
					if ( $cond[0] === APCOND_EDITCOUNT ) {
						$params = [ $this->getConfig()->get( MainConfigNames::AutoConfirmCount ) ];
					} elseif ( $cond[0] === APCOND_AGE ) {
						$params = [ $this->getConfig()->get( MainConfigNames::AutoConfirmAge ) ];
					}
				}
				$config = [
					'condname' => array_search( $cond[0], $allowedConditions ),
					'params' => $params
				];
				ApiResult::setIndexedTagName( $config, 'params' );
			}
		} elseif ( is_string( $cond ) ) {
			$config = $cond;
		} else {
			// When $cond is equal to an APCOND_ constant value
			$config = [
				'condname' => array_search( $cond, $allowedConditions ),
				'params' => []
			];
			ApiResult::setIndexedTagName( $config, 'params' );
		}

		return $config;
	}

	public function appendSubscribedHooks( string $property ): bool {
		$hookNames = $this->hookContainer->getHookNames();
		sort( $hookNames );

		$data = [];
		foreach ( $hookNames as $name ) {
			$arr = [
				'name' => $name,
				'subscribers' => $this->hookContainer->getHandlerDescriptions( $name ),
			];

			ApiResult::setArrayType( $arr['subscribers'], 'array' );
			ApiResult::setIndexedTagName( $arr['subscribers'], 's' );
			$data[] = $arr;
		}

		ApiResult::setIndexedTagName( $data, 'hook' );

		return $this->getResult()->addValue( 'query', $property, $data );
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		// Messages for $wgExtraInterlanguageLinkPrefixes depend on user language
		if ( $this->getConfig()->get( MainConfigNames::ExtraInterlanguageLinkPrefixes ) &&
			in_array( 'interwikimap', $params['prop'] ?? [] )
		) {
			return 'anon-public-user-private';
		}

		return 'public';
	}

	/** @inheritDoc */
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
					'autocreatetempuser',
					'clientlibraries',
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
					'autopromote',
					'autopromoteonce',
					'copyuploaddomains',
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

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Siteinfo';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQuerySiteinfo::class, 'ApiQuerySiteinfo' );
