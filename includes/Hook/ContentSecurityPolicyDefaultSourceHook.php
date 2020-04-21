<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContentSecurityPolicyDefaultSourceHook {
	/**
	 * Modify the allowed CSP load sources. This
	 * affects all directives except for the script directive. If you want to add a
	 * script source, see ContentSecurityPolicyScriptSource hook.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$defaultSrc Array of Content-Security-Policy allowed sources
	 * @param ?mixed $policyConfig Current configuration for the Content-Security-Policy header
	 * @param ?mixed $mode ContentSecurityPolicy::REPORT_ONLY_MODE or
	 *   ContentSecurityPolicy::FULL_MODE depending on type of header
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentSecurityPolicyDefaultSource( &$defaultSrc,
		$policyConfig, $mode
	);
}
