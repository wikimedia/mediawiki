<?php

use MediaWiki\Content\Content;

class CustomDifferenceEngine extends DifferenceEngine {

	public function __construct() {
		parent::__construct();
	}

	public function generateContentDiffBody( Content $old, Content $new ): string {
		return $old->getText() . '|' . $new->getText();
	}

	public function showDiffStyle() {
		$this->getOutput()->addModules( 'foo' );
	}

	/** @inheritDoc */
	public function getDiffBodyCacheKeyParams() {
		$params = parent::getDiffBodyCacheKeyParams();
		$params[] = 'foo';
		return $params;
	}

}
