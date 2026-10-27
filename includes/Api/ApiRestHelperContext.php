<?php

namespace MediaWiki\Api;

/**
 * Context object for ApiRestHelper, injected via ApiRestHybrid::initRestMode().
 * It provides a way to evolve the information passed into the helpers without
 * breaking the ApiRestHybrid interface.
 *
 * @unstable since 1.47
 */
class ApiRestHelperContext {

	/**
	 * @param array $adapterConfig The adapter configuration of the REST handler.
	 * @param array $routeConfig The route configuration of the REST handler.
	 */
	public function __construct(
		private readonly array $adapterConfig,
		private readonly array $routeConfig,
	) {
	}

	/**
	 * @return array The structure under the 'adapter' key in the REST route
	 *         definition.
	 */
	public function getAdapterConfig(): array {
		return $this->adapterConfig;
	}

	/**
	 * @return array The REST handler config, as returned by Handler::getConfig().
	 */
	public function getRouteConfig(): array {
		return $this->routeConfig;
	}

}
