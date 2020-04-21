<?php

namespace MediaWiki\Api\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiQuery__moduleManagerHook {
	/**
	 * Called when ApiQuery has finished initializing its
	 * module manager. Can be used to conditionally register API query modules.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $moduleManager ApiModuleManager Module manager instance
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQuery__moduleManager( $moduleManager );
}
