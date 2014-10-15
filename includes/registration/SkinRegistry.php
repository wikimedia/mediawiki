<?php

/**
 * BaseRegistry class for skins
 *
 * @since 1.25
 */
class SkinRegistry extends BaseRegistry {
	public function __construct() {
		$this->processor = new SkinProcessor( SkinFactory::getDefaultInstance() );
	}
}
