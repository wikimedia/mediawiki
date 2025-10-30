<?php

namespace MediaWiki\Logger;

use Wikimedia\ScopedCallback;

/**
 * A helper class for adding extra context to all logs, without the logging code having to be
 * aware. (Sometimes called a "diagnostic context".)
 *
 * Use LoggerFactory::getContext() to obtain the active instance of this class.
 * @since 1.44
 */
class LoggingContext {

	/** Extra context to add to all log events. */
	private array $baseContext = [];
	/** List of extra contexts added to all log events while in scope. */
	private array $scopedContexts = [];
	/** The sum of all the contexts. */
	private array $effectiveContext = [];

	/**
	 * Add extra information to the PSR-3 context. All future log events in the current request
	 * will include it. In case of key conflict, the context passed to the logger and scoped
	 * contexts take priority over this method, and later calls take priority over earlier calls.
	 *
	 * It is recommended to make sure the fields added this way have a sufficiently unique name,
	 * and to prefix them with 'context.' (e.g. 'context.special_page_name').
	 */
	public function add( array $context ): void {
		$this->baseContext = array_merge( $this->baseContext, $context );
		$this->update();
	}

	/**
	 * Add extra information to the PSR-3 context. All future log events within the scope will
	 * include it. In case of key conflict, the context passed to the logger takes priority over
	 * everything else, inner scopes take priority over outer scopes, and scopes take priority
	 * over addContext().
	 *
	 * The context is not inherited by callbacks which are scheduled in the scope but executed
	 * outside it (such as DeferrableUpdate).
	 *
	 * It is recommended to make sure the fields added this way have a sufficiently unique name,
	 * and to prefix them with 'context.' (e.g. 'context.special_page_name').
	 *
	 * @phan-side-effect-free
	 */
	public function addScoped( array $context ): ScopedCallback {
		$this->scopedContexts[] = $context;
		$scopeId = array_key_last( $this->scopedContexts );
		$this->update();
		return new ScopedCallback( function ( $scopeId ) {
			unset( $this->scopedContexts[$scopeId] );
			$this->update();
		}, [ $scopeId ] );
	}

	/**
	 * Get the current logging context.
	 * @internal To be used by loggers only.
	 */
	public function get(): array {
		return $this->effectiveContext;
	}

	private function update(): void {
		$this->effectiveContext = $this->baseContext;
		foreach ( $this->scopedContexts as $context ) {
			$this->effectiveContext = array_merge( $this->effectiveContext, $context );
		}
	}

}
