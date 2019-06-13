<?php

/**
 * @covers TransactionRoundDefiningUpdate
 */
class TransactionRoundDefiningUpdateTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function testDoUpdate() {
		$ran = 0;
		$update = new TransactionRoundDefiningUpdate( function () use ( &$ran ) {
			$ran++;
		} );
		$this->assertSame( 0, $ran );
		$update->doUpdate();
		$this->assertSame( 1, $ran );
	}
}
