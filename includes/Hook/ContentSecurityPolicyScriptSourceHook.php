<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ContentSecurityPolicyScriptSourceHook {
	/**
	 * Use this hook to modify the allowed CSP script sources.
	 * Note that you also have to use ContentSecurityPolicyDefaultSource if you
	 * want non-script sources to be loaded from whatever you add.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$scriptSrc Array of CSP directives
	 * @param array $policyConfig Current configuration for the CSP header
	 * @param string $mode ContentSecurityPolicy::REPORT_ONLY_MODE or
	 *   ContentSecurityPolicy::FULL_MODE depending on type of header
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentSecurityPolicyScriptSource( &$scriptSrc,
		$policyConfig, $mode
	);
}
