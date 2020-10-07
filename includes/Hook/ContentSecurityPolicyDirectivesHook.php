<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ContentSecurityPolicyDirectivesHook {
	/**
	 * If ContentSecurityPolicyDefaultSource and ContentSecurityPolicyScriptSource
	 * do not meet your needs, use this hook to modify the content security policy directives.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$directives Array of CSP directives
	 * @param array $policyConfig Current configuration for the CSP header
	 * @param string $mode ContentSecurityPolicy::REPORT_ONLY_MODE or
	 *   ContentSecurityPolicy::FULL_MODE depending on type of header
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentSecurityPolicyDirectives( &$directives, $policyConfig,
		$mode
	);
}
