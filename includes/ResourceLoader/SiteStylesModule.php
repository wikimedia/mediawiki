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
 * Module for site style customizations.
 *
 * @ingroup ResourceLoader
 * @internal
 */
class SiteStylesModule extends WikiModule {

	/**
	 * Get list of pages used by this module
	 *
	 * @param Context $context
	 * @return array[]
	 */
	protected function getPages( Context $context ) {
		$pages = [];
		if ( $this->getConfig()->get( MainConfigNames::UseSiteCss ) ) {
			$skin = $context->getSkin();
			$pages['MediaWiki:Common.css'] = [ 'type' => 'style' ];
			$pages['MediaWiki:' . ucfirst( $skin ) . '.css'] = [ 'type' => 'style' ];
			$pages['MediaWiki:Print.css'] = [ 'type' => 'style', 'media' => 'print' ];
			$this->getHookRunner()->onResourceLoaderSiteStylesModulePages( $skin, $pages );
		}
		return $pages;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return self::LOAD_STYLES;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return self::GROUP_SITE;
	}
}
