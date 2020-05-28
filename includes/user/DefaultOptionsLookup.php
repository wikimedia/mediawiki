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
use Skin;
use Wikimedia\Assert\Assert;

/**
 * A service class to control default user options
 * @since 1.35
 */
class DefaultOptionsLookup extends UserOptionsLookup {

	public const CONSTRUCTOR_OPTIONS = [
		'DefaultSkin',
		'DefaultUserOptions',
		'NamespacesToBeSearchedDefault'
	];

	/** @var ServiceOptions */
	private $serviceOptions;

	/** @var Language */
	private $contentLang;

	/** @var array|null Cached default options */
	private $defaultOptions = null;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @param ServiceOptions $options
	 * @param Language $contentLang
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		Language $contentLang,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->serviceOptions = $options;
		$this->contentLang = $contentLang;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultOptions(): array {
		if ( $this->defaultOptions !== null ) {
			return $this->defaultOptions;
		}

		$this->defaultOptions = $this->serviceOptions->get( 'DefaultUserOptions' );

		// Default language setting
		$this->defaultOptions['language'] = $this->contentLang->getCode();
		foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
			if ( $langCode === $this->contentLang->getCode() ) {
				$this->defaultOptions['variant'] = $langCode;
			} else {
				$this->defaultOptions["variant-$langCode"] = $langCode;
			}
		}

		// NOTE: don't use SearchEngineConfig::getSearchableNamespaces here,
		// since extensions may change the set of searchable namespaces depending
		// on user groups/permissions.
		foreach ( $this->serviceOptions->get( 'NamespacesToBeSearchedDefault' ) as $nsnum => $val ) {
			$this->defaultOptions['searchNs' . $nsnum] = (bool)$val;
		}
		$this->defaultOptions['skin'] = Skin::normalizeKey( $this->serviceOptions->get( 'DefaultSkin' ) );

		$this->hookRunner->onUserGetDefaultOptions( $this->defaultOptions );

		return $this->defaultOptions;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultOption( string $opt ) {
		$defOpts = $this->getDefaultOptions();
		return $defOpts[$opt] ?? null;
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
