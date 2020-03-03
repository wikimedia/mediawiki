<?php

namespace MediaWiki\Api\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiMain__moduleManagerHook {
	/**
	 * Called when ApiMain has finished initializing its
	 * module manager. Can be used to conditionally register API modules.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $moduleManager ApiModuleManager Module manager instance
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiMain__moduleManager( $moduleManager );
}
