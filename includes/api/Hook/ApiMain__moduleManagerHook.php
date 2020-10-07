<?php

namespace MediaWiki\Api\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use ApiModuleManager;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiMain__moduleManagerHook {
	/**
	 * This hook is called when ApiMain has finished initializing its
	 * module manager. Use this hook to conditionally register API modules.
	 *
	 * @since 1.35
	 *
	 * @param ApiModuleManager $moduleManager
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiMain__moduleManager( $moduleManager );
}
