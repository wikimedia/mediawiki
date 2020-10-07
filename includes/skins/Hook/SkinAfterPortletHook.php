<?php

namespace MediaWiki\Skins\Hook;

use Skin;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SkinAfterPortletHook {
	/**
	 * This hook is called when generating portlets.
	 * It allows injecting custom HTML after the portlet.
	 *
	 * @since 1.35
	 *
	 * @param Skin $skin
	 * @param string $portlet
	 * @param string &$html
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinAfterPortlet( $skin, $portlet, &$html );
}
