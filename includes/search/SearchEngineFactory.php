<?php

use MediaWiki\HookContainer\HookContainer;
use Wikimedia\ObjectFactory\ObjectFactory;
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

	/** @var ILoadBalancer */
	private $loadBalancer;

	/**
	 * @param SearchEngineConfig $config
	 * @param HookContainer $hookContainer
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		SearchEngineConfig $config,
		HookContainer $hookContainer,
		ILoadBalancer $loadBalancer
	) {
		$this->config = $config;
		$this->hookContainer = $hookContainer;
		$this->loadBalancer = $loadBalancer;
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
			$class = self::getSearchEngineClass( $this->loadBalancer );
		}

		$mappings = $this->config->getSearchMappings();

		// Convert non mapped classes to ObjectFactory spec
		$spec = $mappings[$class] ?? [ 'class' => $class ];

		$args = [];

		if ( isset( $spec['class'] ) && is_subclass_of( $spec['class'], SearchDatabase::class ) ) {
			$args['extraArgs'][] = $this->loadBalancer;
		}

		// ObjectFactory::getObjectFromSpec accepts an array, not just a callable (phan bug)
		// @phan-suppress-next-line PhanTypeInvalidCallableArraySize
		$engine = ObjectFactory::getObjectFromSpec( $spec, $args );
		/** @var SearchEngine $engine */
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
