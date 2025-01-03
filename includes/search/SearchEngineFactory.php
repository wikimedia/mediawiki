<?php

use MediaWiki\HookContainer\HookContainer;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Factory class for SearchEngine.
 * Allows to create engine of the specific type.
 */
class SearchEngineFactory {

	private SearchEngineConfig $config;
	private HookContainer $hookContainer;
	private IConnectionProvider $dbProvider;

	public function __construct(
		SearchEngineConfig $config,
		HookContainer $hookContainer,
		IConnectionProvider $dbProvider
	) {
		$this->config = $config;
		$this->hookContainer = $hookContainer;
		$this->dbProvider = $dbProvider;
	}

	/**
	 * Create SearchEngine of the given type.
	 *
	 * @param string|null $type
	 * @return SearchEngine
	 */
	public function create( $type = null ) {
		$configuredClass = $this->config->getSearchType();
		$alternativesClasses = $this->config->getSearchTypes();

		if ( $type !== null && in_array( $type, $alternativesClasses ) ) {
			$class = $type;
		} elseif ( $configuredClass !== null ) {
			$class = $configuredClass;
		} else {
			$class = self::getSearchEngineClass( $this->dbProvider );
		}

		$mappings = $this->config->getSearchMappings();

		// Convert non mapped classes to ObjectFactory spec
		$spec = $mappings[$class] ?? [ 'class' => $class ];

		$args = [];

		if ( isset( $spec['class'] ) && is_subclass_of( $spec['class'], SearchDatabase::class ) ) {
			$args['extraArgs'][] = $this->dbProvider;
		}

		// ObjectFactory::getObjectFromSpec accepts an array, not just a callable (phan bug)
		// @phan-suppress-next-line PhanTypeInvalidCallableArraySize
		$engine = ObjectFactory::getObjectFromSpec( $spec, $args );
		/** @var SearchEngine $engine */
		$engine->setHookContainer( $this->hookContainer );
		return $engine;
	}

	/**
	 * @param IConnectionProvider $dbProvider
	 * @return string SearchEngine subclass name
	 * @since 1.28
	 */
	public static function getSearchEngineClass( IConnectionProvider $dbProvider ) {
		$type = $dbProvider->getReplicaDatabase()->getType();

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
