<?php

function titleExpected( Title $t ) {
}

class WfFailingTest extends MediaWikiTestCase {
	function testFailure() {
		titleExpected();
	}
}
