<?php

namespace MediaWiki\Hook;

use BaseTemplate;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BaseTemplateAfterPortlet" to register handlers implementing this interface.
 *
 * @deprecated since 1.35 (emits deprecation warnings since 1.37), Use SkinAfterPortlet instead
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
