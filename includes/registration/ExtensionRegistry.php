<?php

/**
 * BaseRegistry class for extensions
 *
 * @since 1.25
 */
class ExtensionRegistry extends BaseRegistry {
	public function __construct() {
		parent::__construct();
	}

	protected function resetProcessors() {
		$this->processors = array(
			'default' => new ExtensionProcessor,
		);
	}
}