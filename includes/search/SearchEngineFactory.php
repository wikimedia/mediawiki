<?php

use Wikimedia\Rdbms\IDatabase;

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
	 * @param string|null $type
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
			$dbr = wfGetDB( DB_REPLICA );
			$class = self::getSearchEngineClass( $dbr );
		}

		$search = new $class( $dbr );
		return $search;
	}

	/**
	 * @param IDatabase $db
	 * @return string SearchEngine subclass name
	 * @since 1.28
	 */
	public static function getSearchEngineClass( IDatabase $db ) {
		switch ( $db->getType() ) {
			case 'sqlite':
				return SearchSqlite::class;
			case 'mysql':
				return SearchMySQL::class;
			case 'postgres':
				return SearchPostgres::class;
			case 'mssql':
				return SearchMssql::class;
			case 'oracle':
				return SearchOracle::class;
			default:
				return SearchEngineDummy::class;
		}
	}
}
