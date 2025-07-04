<?php
/**
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

namespace MediaWiki\User\Options;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\MainConfigNames;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNameUtils;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * A service class to control default user options
 * @since 1.35
 */
class DefaultOptionsLookup extends UserOptionsLookup {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::DefaultSkin,
		MainConfigNames::DefaultUserOptions,
		MainConfigNames::NamespacesToBeSearchedDefault
	];

	private ServiceOptions $serviceOptions;
	private LanguageCode $contentLang;
	private NamespaceInfo $nsInfo;
	private ConditionalDefaultsLookup $conditionalDefaultsLookup;
	private UserIdentityLookup $userIdentityLookup;

	/** @var array Cache of default options by user */
	private $cache = [];

	/** @var array|null Cached default options */
	private $defaultOptions = null;

	private HookRunner $hookRunner;

	/**
	 * @param ServiceOptions $options
	 * @param LanguageCode $contentLang
	 * @param HookContainer $hookContainer
	 * @param NamespaceInfo $nsInfo
	 * @param ConditionalDefaultsLookup $conditionalUserOptionsDefaultsLookup
	 * @param UserIdentityLookup $userIdentityLookup
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		ServiceOptions $options,
		LanguageCode $contentLang,
		HookContainer $hookContainer,
		NamespaceInfo $nsInfo,
		ConditionalDefaultsLookup $conditionalUserOptionsDefaultsLookup,
		UserIdentityLookup $userIdentityLookup,
		UserNameUtils $userNameUtils
	) {
		parent::__construct( $userNameUtils );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->serviceOptions = $options;
		$this->contentLang = $contentLang;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->nsInfo = $nsInfo;
		$this->conditionalDefaultsLookup = $conditionalUserOptionsDefaultsLookup;
		$this->userIdentityLookup = $userIdentityLookup;
	}

	/**
	 * Get default user options from $wgDefaultUserOptions (ignoring any conditional defaults)
	 */
	private function getGenericDefaultOptions(): array {
		if ( $this->defaultOptions !== null ) {
			return $this->defaultOptions;
		}

		$this->defaultOptions = $this->serviceOptions->get( MainConfigNames::DefaultUserOptions );

		// Default language setting
		// NOTE: don't use the content language code since the static default variant would
		//  NOT always be the same as the content language code.
		$contentLangCode = $this->contentLang->toString();
		$LangsWithStaticDefaultVariant = LanguageConverter::$languagesWithStaticDefaultVariant;
		$staticDefaultVariant = $LangsWithStaticDefaultVariant[$contentLangCode] ?? $contentLangCode;
		$this->defaultOptions['language'] = $contentLangCode;
		$this->defaultOptions['variant'] = $staticDefaultVariant;
		foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
			$staticDefaultVariant = $LangsWithStaticDefaultVariant[$langCode] ?? $langCode;
			$this->defaultOptions["variant-$langCode"] = $staticDefaultVariant;
		}

		// NOTE: don't use SearchEngineConfig::getSearchableNamespaces here,
		// since extensions may change the set of searchable namespaces depending
		// on user groups/permissions.
		$nsSearchDefault = $this->serviceOptions->get( MainConfigNames::NamespacesToBeSearchedDefault );
		foreach ( $this->nsInfo->getValidNamespaces() as $n ) {
			$this->defaultOptions['searchNs' . $n] = ( $nsSearchDefault[$n] ?? false ) ? 1 : 0;
		}
		$this->defaultOptions['skin'] = Skin::normalizeKey(
			$this->serviceOptions->get( MainConfigNames::DefaultSkin ) );

		$this->hookRunner->onUserGetDefaultOptions( $this->defaultOptions );

		return $this->defaultOptions;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultOptions( ?UserIdentity $userIdentity = null ): array {
		$defaultOptions = $this->getGenericDefaultOptions();

		// If requested, process any conditional defaults
		if ( $userIdentity ) {
			$cacheKey = $this->getCacheKey( $userIdentity );
			if ( isset( $this->cache[$cacheKey] ) ) {
				return $this->cache[$cacheKey];
			}
			$conditionallyDefaultOptions = $this->conditionalDefaultsLookup->getConditionallyDefaultOptions();
			foreach ( $conditionallyDefaultOptions as $optionName ) {
				$conditionalDefault = $this->conditionalDefaultsLookup->getOptionDefaultForUser(
					$optionName, $userIdentity
				);
				if ( $conditionalDefault !== null ) {
					$defaultOptions[$optionName] = $conditionalDefault;
				}
			}
			$this->cache[$cacheKey] = $defaultOptions;
		}

		return $defaultOptions;
	}

	/**
	 * @inheritDoc
	 */
	public function getOption(
		UserIdentity $user,
		string $oname,
		$defaultOverride = null,
		bool $ignoreHidden = false,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	) {
		$this->verifyUsable( $user, __METHOD__ );
		return $this->getDefaultOption( $oname ) ?? $defaultOverride;
	}

	/**
	 * @inheritDoc
	 */
	public function getOptions(
		UserIdentity $user,
		int $flags = 0,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$this->verifyUsable( $user, __METHOD__ );
		if ( $flags & self::EXCLUDE_DEFAULTS ) {
			return [];
		}
		return $this->getDefaultOptions();
	}

	/**
	 * Checks if the DefaultOptionsLookup is usable as an instance of UserOptionsLookup.
	 *
	 * It only makes sense in an installer context when UserOptionsManager cannot be yet instantiated
	 * as the database is not available. Thus, this can only be called for an anon user,
	 * calling under different circumstances indicates a bug, or that a system user is being used.
	 *
	 * The only exception to this is database-less PHPUnit tests, where sometimes fake registered users are
	 * used and end up being passed to this class. This should not be considered a bug, and using the default
	 * preferences in this scenario is probably the intended behaviour.
	 *
	 * @param UserIdentity $user
	 * @param string $fname
	 */
	private function verifyUsable( UserIdentity $user, string $fname ) {
		if ( defined( 'MEDIAWIKI_INSTALL' ) ) {
			return;
		}
		Assert::precondition( !$user->isRegistered(), "$fname called on a registered user" );
	}

	/** @inheritDoc */
	public function getOptionBatchForUserNames( array $users, string $key ) {
		$genericDefault = $this->getGenericDefaultOptions()[$key] ?? '';
		$options = array_fill_keys( $users, $genericDefault );
		if ( $this->conditionalDefaultsLookup->hasConditionalDefault( $key ) ) {
			$userIdentities = $this->userIdentityLookup->newSelectQueryBuilder()
				->whereUserNames( $users )
				->caller( __METHOD__ )
				->fetchUserIdentities();
			foreach ( $userIdentities as $user ) {
				$options[$user->getName()] = $this->conditionalDefaultsLookup
					->getOptionDefaultForUser( $key, $user );
			}
		}
		return $options;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( DefaultOptionsLookup::class, 'MediaWiki\\User\\DefaultOptionsLookup' );
