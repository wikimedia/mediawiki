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

namespace MediaWiki\User;

use Language;
use LanguageConverter;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use NamespaceInfo;
use Skin;
use Wikimedia\Assert\Assert;

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

	/** @var ServiceOptions */
	private $serviceOptions;

	/** @var Language */
	private $contentLang;

	/** @var NamespaceInfo */
	protected $nsInfo;

	/** @var array|null Cached default options */
	private $defaultOptions = null;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @param ServiceOptions $options
	 * @param Language $contentLang
	 * @param HookContainer $hookContainer
	 * @param NamespaceInfo $nsInfo
	 */
	public function __construct(
		ServiceOptions $options,
		Language $contentLang,
		HookContainer $hookContainer,
		NamespaceInfo $nsInfo
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->serviceOptions = $options;
		$this->contentLang = $contentLang;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->nsInfo = $nsInfo;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultOptions(): array {
		if ( $this->defaultOptions !== null ) {
			return $this->defaultOptions;
		}

		$this->defaultOptions = $this->serviceOptions->get( MainConfigNames::DefaultUserOptions );

		// Default language setting
		// NOTE: don't use the content language code since the static default variant would
		//  NOT always be the same as the content language code.
		$contentLangCode = $this->contentLang->getCode();
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
	public function getOption(
		UserIdentity $user,
		string $oname,
		$defaultOverride = null,
		bool $ignoreHidden = false,
		int $queryFlags = self::READ_NORMAL
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
		int $queryFlags = self::READ_NORMAL
	): array {
		$this->verifyUsable( $user, __METHOD__ );
		if ( $flags & self::EXCLUDE_DEFAULTS ) {
			return [];
		}
		return $this->getDefaultOptions();
	}

	/**
	 * Checks if the DefaultOptionsLookup is usable as an instance of UserOptionsLookup.
	 * It only makes sense in an installer context when UserOptionsManager cannot be yet instantiated
	 * as the database is not available. Thus, this can only be called for an anon user,
	 * calling under different circumstances indicates a bug.
	 * @param UserIdentity $user
	 * @param string $fname
	 */
	private function verifyUsable( UserIdentity $user, string $fname ) {
		Assert::precondition( !$user->isRegistered(), "$fname called on a registered user " );
	}
}
