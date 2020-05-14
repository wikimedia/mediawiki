<?php

namespace MediaWiki\HookContainer;

use ExtensionRegistry;

/**
 * A HookRegistry which sources its data from dynamically changing sources:
 * $wgHooks and an ExtensionRegistry.
 */
class GlobalHookRegistry implements HookRegistry {
	/** @var ExtensionRegistry */
	private $extensionRegistry;
	/** @var DeprecatedHooks */
	private $deprecatedHooks;

	public function __construct(
		ExtensionRegistry $extensionRegistry,
		DeprecatedHooks $deprecatedHooks
	) {
		$this->extensionRegistry = $extensionRegistry;
		$this->deprecatedHooks = $deprecatedHooks;
	}

	public function getGlobalHooks() {
		global $wgHooks;
		return $wgHooks;
	}

	public function getExtensionHooks() {
		return $this->extensionRegistry->getAttribute( 'Hooks' ) ?? [];
	}

	public function getDeprecatedHooks() {
		return $this->deprecatedHooks;
	}
}
