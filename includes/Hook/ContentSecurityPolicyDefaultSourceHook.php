<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ContentSecurityPolicyDefaultSource" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ContentSecurityPolicyDefaultSourceHook {
	/**
	 * Use this hook to modify the allowed CSP load sources. This affects all
	 * directives except for the script directive. To add a script source, see
	 * ContentSecurityPolicyScriptSource hook.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$defaultSrc Array of Content-Security-Policy allowed sources
	 * @param array $policyConfig Current configuration for the Content-Security-Policy header
	 * @param string $mode ContentSecurityPolicy::REPORT_ONLY_MODE or
	 *   ContentSecurityPolicy::FULL_MODE depending on type of header
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentSecurityPolicyDefaultSource( &$defaultSrc,
		$policyConfig, $mode
	);
}
