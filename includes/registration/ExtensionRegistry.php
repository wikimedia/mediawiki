<?php

/**
 * BaseRegistry class for extensions
 *
 * @since 1.25
 */
class ExtensionRegistry extends BaseRegistry {
	public function __construct() {
		parent::__construct();
		$this->processor = new ExtensionProcessor;
	}
}