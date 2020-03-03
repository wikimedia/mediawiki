<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContentSecurityPolicyDirectivesHook {
	/**
	 * Modify the content security policy
	 * directives. Use this only if ContentSecurityPolicyDefaultSource and
	 * ContentSecurityPolicyScriptSource do not meet your needs.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$directives Array of CSP directives
	 * @param ?mixed $policyConfig Current configuration for the CSP header
	 * @param ?mixed $mode ContentSecurityPolicy::REPORT_ONLY_MODE or
	 *   ContentSecurityPolicy::FULL_MODE depending on type of header
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentSecurityPolicyDirectives( &$directives, $policyConfig,
		$mode
	);
}
