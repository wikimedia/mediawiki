<?php

namespace MediaWiki\Api\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiLogFeatureUsage" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiLogFeatureUsageHook {
	/**
	 * This hook is called after calling the logFeatureUsage() method of an API module.
	 * Use this hook to extend core API modules.
	 *
	 * @since 1.43
	 *
	 * @param string $feature
	 * @param array<string,mixed> $clientInfo
	 * @phan-param array{userName:string,userAgent:string,ipAddress:string} $clientInfo
	 * @return void
	 */
	public function onApiLogFeatureUsage( $feature, array $clientInfo ): void;
}
