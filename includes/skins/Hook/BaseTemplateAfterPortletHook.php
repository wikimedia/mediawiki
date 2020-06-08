<?php

namespace MediaWiki\Hook;

use BaseTemplate;

/**
 * @deprecated since 1.35 Use SkinAfterPortlet instead
 * @ingroup Hooks
 */
interface BaseTemplateAfterPortletHook {
	/**
	 * This hook is called after output of portlets, allow injecting
	 * custom HTML after the section. Any uses of the hook need to handle escaping.
	 *
	 * @since 1.35
	 *
	 * @param BaseTemplate $template
	 * @param string $portlet Portlet name
	 * @param string &$html
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBaseTemplateAfterPortlet( $template, $portlet, &$html );
}
