<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

namespace MediaWiki\ResourceLoader;

use MediaWiki\MainConfigNames;

/**
 * Module for site customizations.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class SiteModule extends WikiModule {
	/**
	 * Get list of pages used by this module
	 *
	 * @param Context $context
	 * @return array[]
	 */
	protected function getPages( Context $context ) {
		$pages = [];
		if ( $this->getConfig()->get( MainConfigNames::UseSiteJs ) ) {
			$skin = $context->getSkin();
			$pages['MediaWiki:Common.js'] = [ 'type' => 'script' ];
			$pages['MediaWiki:' . ucfirst( $skin ) . '.js'] = [ 'type' => 'script' ];
			$this->getHookRunner()->onResourceLoaderSiteModulePages( $skin, $pages );
		}
		return $pages;
	}

	/**
	 * @param Context|null $context
	 * @return array
	 */
	public function getDependencies( ?Context $context = null ) {
		return [ 'site.styles' ];
	}
}
