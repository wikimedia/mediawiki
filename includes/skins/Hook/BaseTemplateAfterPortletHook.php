<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BaseTemplateAfterPortletHook {
	/**
	 * After output of portlets, allow injecting
	 * custom HTML after the section. Any uses of the hook need to handle escaping.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $template BaseTemplate
	 * @param ?mixed $portlet string portlet name
	 * @param ?mixed &$html string
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBaseTemplateAfterPortlet( $template, $portlet, &$html );
}
