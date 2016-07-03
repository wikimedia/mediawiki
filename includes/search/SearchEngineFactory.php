<?php

/**
 * Factory class for SearchEngine.
 * Allows to create engine of the specific type.
 */
class SearchEngineFactory {

	/**
	 * Configuration for SearchEngine classes.
	 * @var SearchEngineConfig
	 */
	private $config;

	public function __construct( SearchEngineConfig $config ) {
		$this->config = $config;
	}

	/**
	 * Create SearchEngine of the given type.
	 * @param string $type
	 * @return SearchEngine
	 */
	public function create( $type = null ) {
		$dbr = null;

		$configType = $this->config->getSearchType();
		$alternatives = $this->config->getSearchTypes();

		if ( $type && in_array( $type, $alternatives ) ) {
			$class = $type;
		} elseif ( $configType !== null ) {
			$class = $configType;
		} else {
			$dbr = wfGetDB( DB_SLAVE );
			$class = $dbr->getSearchEngine();
		}

		$search = new $class( $dbr );
		return $search;
	}
}
