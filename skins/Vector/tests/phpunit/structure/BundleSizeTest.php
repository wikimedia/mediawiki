<?php

namespace MediaWiki\Skins\Vector\Tests\Structure;

class BundleSizeTest extends \MediaWiki\Tests\Structure\BundleSizeTest {

	/** @inheritDoc */
	public function getBundleSizeConfig(): string {
		return dirname( __DIR__, 3 ) . '/bundlesize.config.json';
	}
}
