<?php

namespace MediaWiki\Services;

/**
 * Class VirtualRESTServiceFactory
 */
class VirtualRESTServiceFactory {
	/**
	 * @var array A map of VRS modules to VRS classes
	 */
	private $moduleClassMap = [
		'parsoid' => \ParsoidVirtualRESTService::class,
		'restbase' => \RestbaseVirtualRESTService::class,
		'swift' => \SwiftVirtualRESTService::class,
	];

	private $config;

	/**
	 * VirtualRESTServiceFactory constructor.
	 * @param \Config $config
	 */
	public function __construct( \Config $config ) {
		$this->config = $config;
	}

	/**
	 * Creates a new VirtualRESTService of a specific module and the passed parameters. The
	 * parameters will be automatically merged with the global ones before the VRS object will be
	 * created.
	 *
	 * If the passed module doesn't have a class associated, this function will throw a
	 * NoSuchServiceException. See @see VirtualRESTServiceFactory::$moduleClassMap for supported
	 * modules.
	 *
	 * @param $module string The requested module to build a service of (e.g. parsoid, restbase,
	 * ...)
	 * @return \VirtualRESTService
	 */
	public function makeService( $module ) {
		$config = $this->config->get( 'VirtualRestConfig' );
		if ( isset( $config['modules'] ) && isset( $config['modules'][$module] ) ) {
			$params = $config['modules'][$module];
		} else {
			return new \NoopVirtualRESTService( [] );
		}
		if ( $module === 'parsoid' ) {
			$params['restbaseCompat'] = true;
		} elseif ( $module === 'restbase' ) {
			$params['parsoidCompat'] = false;
		}

		if ( isset( $config['global'] ) ) {
			$params = array_merge( $config['global'], $params );
		}

		if ( $params['forwardCookies'] ) {
			$params['forwardCookies'] =
				\RequestContext::getMain()->getRequest()->getHeader( 'Cookie' );
		} else {
			$params['forwardCookies'] = false;
		}

		return new $this->moduleClassMap[$module]( $params );
	}
}
