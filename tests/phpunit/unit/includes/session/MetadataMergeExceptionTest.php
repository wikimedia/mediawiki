<?php

namespace MediaWiki\Session;

/**
 * @group Session
 * @covers MediaWiki\Session\MetadataMergeException
 */
class MetadataMergeExceptionTest extends \MediaWikiUnitTestCase {

	public function testBasics() {
		$data = [ 'foo' => 'bar' ];

		$ex = new MetadataMergeException();
		$this->assertInstanceOf( \UnexpectedValueException::class, $ex );
		$this->assertSame( [], $ex->getContext() );

		$ex2 = new MetadataMergeException( 'Message', 42, $ex, $data );
		$this->assertSame( 'Message', $ex2->getMessage() );
		$this->assertSame( 42, $ex2->getCode() );
		$this->assertSame( $ex, $ex2->getPrevious() );
		$this->assertSame( $data, $ex2->getContext() );

		$ex->setContext( $data );
		$this->assertSame( $data, $ex->getContext() );
	}

}
