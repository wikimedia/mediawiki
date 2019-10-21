<?php

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use MediaWiki\MediaWikiServices;

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
		$configuredClass = $this->config->getSearchType();
		$alternativesClasses = $this->config->getSearchTypes();

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		if ( $type !== null && in_array( $type, $alternativesClasses ) ) {
			$class = $type;
		} elseif ( $configuredClass !== null ) {
			$class = $configuredClass;
		} else {
			$class = self::getSearchEngineClass( $lb );
		}

		if ( is_subclass_of( $class, SearchDatabase::class ) ) {
			return new $class( $lb );
		} else {
			return new $class();
		}
	}

	/**
	 * @param IDatabase|ILoadBalancer $dbOrLb
	 * @return string SearchEngine subclass name
	 * @since 1.28
	 */
	public static function getSearchEngineClass( $dbOrLb ) {
		$type = ( $dbOrLb instanceof IDatabase )
			? $dbOrLb->getType()
			: $dbOrLb->getServerType( $dbOrLb->getWriterIndex() );

		switch ( $type ) {
			case 'sqlite':
				return SearchSqlite::class;
			case 'mysql':
				return SearchMySQL::class;
			case 'postgres':
				return SearchPostgres::class;
			default:
				return SearchEngineDummy::class;
		}
	}
}
