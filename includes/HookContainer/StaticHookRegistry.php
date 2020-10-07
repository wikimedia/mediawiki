<?php

namespace MediaWiki\HookContainer;

/**
 * This is a simple immutable HookRegistry which can be used to set up a local
 * HookContainer in tests and for similar purposes.
 */
class StaticHookRegistry implements HookRegistry {
	/** @var array */
	private $globalHooks;

	/** @var array */
	private $extensionHooks;

	/** @var DeprecatedHooks */
	private $deprecatedHooks;

	/**
	 * @param array $globalHooks An array of legacy hooks in the same format as $wgHooks
	 * @param array $extensionHooks An array of modern hooks in the format
	 *   described in HookRegistry::getExtensionHooks()
	 * @param array $deprecatedHooksArray An array of deprecated hooks in the
	 *   format expected by DeprecatedHooks::__construct(). These hooks are added
	 *   to the core deprecated hooks list which is always present.
	 */
	public function __construct(
		array $globalHooks = [],
		array $extensionHooks = [],
		array $deprecatedHooksArray = []
	) {
		$this->globalHooks = $globalHooks;
		$this->extensionHooks = $extensionHooks;
		$this->deprecatedHooks = new DeprecatedHooks( $deprecatedHooksArray );
	}

	public function getGlobalHooks() {
		return $this->globalHooks;
	}

	public function getExtensionHooks() {
		return $this->extensionHooks;
	}

	/**
	 * @return DeprecatedHooks
	 */
	public function getDeprecatedHooks() {
		return $this->deprecatedHooks;
	}
}
