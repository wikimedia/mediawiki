<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ResourceLoader;

/**
 * Module for mediawiki.ForeignApi and mediawiki.ForeignRest that has dynamically
 * generated dependencies, via a hook usable by extensions.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class ForeignApiModule extends FileModule {
	/** @inheritDoc */
	public function getDependencies( ?Context $context = null ) {
		$dependencies = $this->dependencies;
		$this->getHookRunner()->onResourceLoaderForeignApiModules( $dependencies, $context );
		return $dependencies;
	}
}
