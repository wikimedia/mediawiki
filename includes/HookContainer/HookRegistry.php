<?php

namespace MediaWiki\HookContainer;

interface HookRegistry {
	/**
	 * Get the current contents of the $wgHooks variable or a mocked substitute
	 * @return array
	 */
	public function getGlobalHooks();

	/**
	 * Get the current contents of the Hooks attribute in the ExtensionRegistry.
	 * The contents is extended and normalized from the value of the
	 * corresponding attribute in extension.json. It does not contain "legacy"
	 * handlers, those are extracted into $wgHooks.
	 *
	 * It is a three dimensional array:
	 *
	 *   - The outer level is an array of hooks keyed by hook name.
	 *   - The second level is an array of handlers, with integer indexes.
	 *   - The third level is an associative array with the following members:
	 *       - handler: An ObjectFactory spec, except that it also has an
	 *         element "name" which is a unique string identifying the handler,
	 *         for the purposes of sharing handler instances.
	 *       - deprecated: A boolean value indicating whether the extension
	 *         is acknowledging deprecation of the hook, to activate call
	 *         filtering.
	 *       - extensionPath: The path to the extension.json file in which the
	 *         handler was defined. This is only used for deprecation messages.
	 *
	 * @return array
	 */
	public function getExtensionHooks();

	/**
	 * @return DeprecatedHooks
	 */
	public function getDeprecatedHooks();
}
