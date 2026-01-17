<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ResourceLoader;

use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\Profiler\Profiler;

/**
 * Entry point implementation for @ref ResourceLoader, which serves static CSS/JavaScript
 * via @ref MediaWiki\ResourceLoader\Module Module subclasses.
 *
 * @see load.php
 * @ingroup ResourceLoader
 * @ingroup entrypoint
 */
class ResourceLoaderEntryPoint extends MediaWikiEntryPoint {

	/**
	 * Main entry point
	 */
	public function execute() {
		$services = $this->getServiceContainer();

		// Disable ChronologyProtector so that we don't wait for unrelated MediaWiki
		// writes when getting database connections for ResourceLoader. (T192611)
		$services->getChronologyProtector()->setEnabled( false );

		$resourceLoader = $services->getResourceLoader();
		$context = new Context(
			$resourceLoader,
			$this->getRequest(),
			array_keys( $services->getSkinFactory()->getInstalledSkins() )
		);

		// T390929
		$extraHeaders = [];
		$hookRunner = new HookRunner( $services->getHookContainer() );
		$hookRunner->onResourceLoaderBeforeResponse( $context, $extraHeaders );

		// Respond to ResourceLoader request
		$resourceLoader->respond( $context, $extraHeaders );

		// Append any visible profiling data in a manner appropriate for the Content-Type
		$profiler = Profiler::instance();
		$profiler->setAllowOutput();
		$profiler->logDataPageOutputOnly();
	}

	protected function doPrepareForOutput() {
		// No-op.
		// Do not call parent::doPrepareForOutput() to avoid
		// commitMainTransaction() getting called.
	}
}
