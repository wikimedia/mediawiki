<?php

/**
 * Configuration handling class for SearchEngine.
 * Provides added service over plain configuration.
 *
 * @since 1.27
 */
class SearchEngineConfig {

	/**
	 * Config object from which the settings will be derived.
	 * @var Config
	 */
	private $config;

	/**
	 * Current language
	 * @var Language
	 */
	private $language;

	public function __construct( Config $config, Language $lang ) {
		$this->config = $config;
		$this->language = $lang;
	}

	/**
	 * Retrieve original config.
	 * @return Config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * Make a list of searchable namespaces and their canonical names.
	 * @return array Namespace ID => name
	 */
	public function searchableNamespaces() {
		$arr = [];
		foreach ( $this->language->getNamespaces() as $ns => $name ) {
			if ( $ns >= NS_MAIN ) {
				$arr[$ns] = $name;
			}
		}

		Hooks::run( 'SearchableNamespaces', [ &$arr ] );
		return $arr;
	}

	/**
	 * Extract default namespaces to search from the given user's
	 * settings, returning a list of index numbers.
	 *
	 * @param user $user
	 * @return int[]
	 */
	public function userNamespaces( $user ) {
		$arr = [];
		foreach ( $this->searchableNamespaces() as $ns => $name ) {
			if ( $user->getOption( 'searchNs' . $ns ) ) {
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
		return array_keys( $this->config->get( 'NamespacesToBeSearchedDefault' ), true );
	}

	/**
	 * Return the search engines we support. If only $wgSearchType
	 * is set, it'll be an array of just that one item.
	 *
	 * @return array
	 */
	public function getSearchTypes() {
		$alternatives = $this->config->get( 'SearchTypeAlternatives' ) ?: [];
		array_unshift( $alternatives, $this->config->get( 'SearchType' ) );

		return $alternatives;
	}

	/**
	 * Return the search engine configured in $wgSearchType, etc.
	 *
	 * @return string|null
	 */
	public function getSearchType() {
		return $this->config->get( 'SearchType' );
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
			if ( empty( $ns ) ) {
				$formatted[$key] = wfMessage( 'blanknamespace' )->text();
			}
		}
		return $formatted;
	}
}
