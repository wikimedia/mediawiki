<?php

namespace MediaWiki\Profiler;

/**
 * Class for tracking request-level classification information for profiling/stats/logging
 *
 * @note this avoids the use of MediaWikiServices so that shutdown functions can use it
 *
 * @since 1.40
 */
class ProfilingContext {
	private string $entryPoint = 'unknown';
	private string $handler = 'unknown';

	private bool $initialized = false;

	private static ?ProfilingContext $instance = null;

	public static function singleton(): ProfilingContext {
		self::$instance ??= new self();

		return self::$instance;
	}

	/**
	 * Set entry point name and principle handler name for this request
	 *
	 * @param string $entryPoint Entry point script name (alphanumeric characters)
	 * @param string $handler Handler name (printable ASCII characters)
	 */
	public function init( string $entryPoint, string $handler ) {
		// Ignore nested nesting (e.g. rest.php handlers that use ApiMain)
		if ( !$this->initialized ) {
			$this->initialized = true;
			$this->entryPoint = $entryPoint;
			$this->handler = $handler;
		}
	}

	/**
	 * @return bool Whether the context was initialized yet
	 */
	public function isInitialized() {
		return $this->initialized;
	}

	/**
	 * @return string Entry point name for this request (e.g. "index", "api", "load")
	 */
	public function getEntryPoint(): string {
		return $this->entryPoint;
	}

	/**
	 * @return string Handler name for this request (e.g. "edit", "recentchanges")
	 */
	public function getHandler(): string {
		return $this->handler;
	}

	/**
	 * @return string Statsd metric name prefix for this entry point and handler
	 */
	public function getHandlerMetricPrefix(): string {
		// Replace any characters that may have a special meaning in statsd/graphite
		return $this->entryPoint . '_' . strtr(
			$this->handler,
			[ '{' => '', '}' => '', ':' => '_', '/' => '_', '.' => '_' ]
		);
	}

	/**
	 * @internal For testing only
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}
}
