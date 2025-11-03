<?php

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserIdentity;

/**
 * Configuration handling class for SearchEngine.
 * Provides added service over plain configuration.
 *
 * @since 1.27
 */
class SearchEngineConfig {

	/** @internal For use by ServiceWiring.php ONLY */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::NamespacesToBeSearchedDefault,
		MainConfigNames::SearchTypeAlternatives,
		MainConfigNames::SearchType,
	];

	/**
	 * Config object from which the settings will be derived.
	 * @var Config
	 */
	private $config;

	/**
	 * Search Engine Mappings
	 *
	 * Key is the canonical name (used in $wgSearchType and $wgSearchTypeAlternatives).
	 * Value is a specification for ObjectFactory.
	 *
	 * @var array
	 */
	private $engineMappings;

	private ServiceOptions $options;
	private Language $language;
	private HookRunner $hookRunner;
	private UserOptionsLookup $userOptionsLookup;

	public function __construct(
		ServiceOptions $options,
		Language $language,
		HookContainer $hookContainer,
		array $engineMappings,
		UserOptionsLookup $userOptionsLookup
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->language = $language;
		$this->engineMappings = $engineMappings;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userOptionsLookup = $userOptionsLookup;
	}

	/**
	 * Retrieve original config.
	 * @deprecated since 1.43, use ServiceOptions instead with DI.
	 * @return Config
	 */
	public function getConfig() {
		wfDeprecated( __METHOD__, '1.43' );
		return $this->config;
	}

	/**
	 * List searchable namespaces and their localized names (with underscores, without considering
	 * language variants).
	 *
	 * NOTE: This is not suitable for UI text, as language variants of namespace names defined via
	 * system messages are ignored. Use {@see LanguageConverter::convertNamespace} instead.
	 *
	 * @return array<int,string> Numeric namespace id => localized name (without language variants)
	 */
	public function searchableNamespaces() {
		$arr = [];
		foreach ( $this->language->getNamespaces() as $ns => $name ) {
			if ( $ns >= NS_MAIN ) {
				$arr[$ns] = $name;
			}
		}

		$this->hookRunner->onSearchableNamespaces( $arr );
		return $arr;
	}

	/**
	 * Extract default namespaces to search from the given user's
	 * settings, returning a list of index numbers.
	 *
	 * @param UserIdentity $user
	 * @return int[]
	 */
	public function userNamespaces( $user ) {
		$arr = [];
		foreach ( $this->searchableNamespaces() as $ns => $_ ) {
			if ( $this->userOptionsLookup->getOption( $user, 'searchNs' . $ns ) ) {
				$arr[] = $ns;
			}
		}

		return $arr;
	}

	/**
	 * An array of namespaces indexes to be searched by default
	 *
	 * @return int[] Namespace IDs
	 */
	public function defaultNamespaces() {
		return array_keys( $this->options->get( MainConfigNames::NamespacesToBeSearchedDefault ),
			true );
	}

	/**
	 * Return the search engines we support. If only $wgSearchType
	 * is set, it'll be an array of just that one item.
	 *
	 * @return array
	 */
	public function getSearchTypes() {
		$alternatives = $this->options->get( MainConfigNames::SearchTypeAlternatives ) ?: [];
		array_unshift( $alternatives, $this->options->get( MainConfigNames::SearchType ) );

		return $alternatives;
	}

	/**
	 * Return the search engine configured in $wgSearchType, etc.
	 *
	 * @return string|null
	 */
	public function getSearchType() {
		return $this->options->get( MainConfigNames::SearchType );
	}

	/**
	 * Returns the mappings between canonical search name and underlying PHP class
	 *
	 * Key is the canonical name (used in $wgSearchType and $wgSearchTypeAlternatives).
	 * Value is a specification for ObjectFactory.
	 *
	 * For example to be able to use 'foobarsearch' in $wgSearchType and
	 * $wgSearchTypeAlternatives but the PHP class for 'foobarsearch'
	 * is 'MediaWiki\Extension\FoobarSearch\FoobarSearch' set:
	 *
	 * @par extension.json Example:
	 * @code
	 * "SearchMappings": {
	 * 	"foobarsearch": { "class": "MediaWiki\\Extension\\FoobarSearch\\FoobarSearch" }
	 * }
	 * @endcode
	 *
	 * @since 1.35
	 * @return array
	 */
	public function getSearchMappings() {
		return $this->engineMappings;
	}

	/**
	 * Get a list of namespace names useful for showing in tooltips
	 * and preferences.
	 *
	 * @param int[] $namespaces
	 * @return string[] List of names
	 */
	public function namespacesAsText( $namespaces ) {
		$formatted = array_map( [ $this->language, 'getFormattedNsText' ], $namespaces );
		foreach ( $formatted as $key => $ns ) {
			if ( !$ns ) {
				$formatted[$key] = wfMessage( 'blanknamespace' )->text();
			}
		}
		return $formatted;
	}
}
