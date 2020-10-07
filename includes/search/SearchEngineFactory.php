<?php

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MediaWikiServices;
use Wikimedia\ObjectFactory;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

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

	/** @var HookContainer */
	private $hookContainer;

	public function __construct( SearchEngineConfig $config, HookContainer $hookContainer ) {
		$this->config = $config;
		$this->hookContainer = $hookContainer;
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

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		if ( $type !== null && in_array( $type, $alternativesClasses ) ) {
			$class = $type;
		} elseif ( $configuredClass !== null ) {
			$class = $configuredClass;
		} else {
			$class = self::getSearchEngineClass( $lb );
		}

		$mappings = $this->config->getSearchMappings();

		if ( isset( $mappings[$class] ) ) {
			$spec = $mappings[$class];
		} else {
			// Convert non mapped classes to ObjectFactory spec
			$spec = [ 'class' => $class ];
		}

		$args = [];

		if ( isset( $spec['class'] ) && is_subclass_of( $spec['class'], SearchDatabase::class ) ) {
			$args['extraArgs'][] = $lb;
		}

		/** @var SearchEngine $engine */
		$engine = ObjectFactory::getObjectFromSpec( $spec, $args );
		$engine->setHookContainer( $this->hookContainer );
		return $engine;
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
