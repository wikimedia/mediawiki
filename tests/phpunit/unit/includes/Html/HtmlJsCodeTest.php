<?php

use MediaWiki\Html\HtmlJsCode;

/**
 * @covers \MediaWiki\Html\HtmlJsCode
 */
class HtmlJsCodeTest extends MediaWikiUnitTestCase {

	public function testConstruction() {
		$obj = new HtmlJsCode( '' );
		$this->assertSame( '', $obj->value );
	}

}
