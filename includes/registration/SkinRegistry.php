<?php

/**
 * BaseRegistry class for skins
 *
 * @since 1.25
 */
class SkinRegistry extends BaseRegistry {
	public function __construct() {
	}

	protected function resetProcessors() {
		$this->processors = array(
			'default' => new SkinProcessor,
		);
	}
}
