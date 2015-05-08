<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiMain
 */
class ApiMainTest extends ApiTestCase {

	/**
	 * Test that the API will accept a FauxRequest and execute. The help action
	 * (default) throws a UsageException. Just validate we're getting proper XML
	 *
	 * @expectedException UsageException
	 */
	public function testApi() {
		$api = new ApiMain(
			new FauxRequest( array( 'action' => 'help', 'format' => 'xml' ) )
		);
		$api->execute();
		$api->getPrinter()->setBufferResult( true );
		$api->printResult( false );
		$resp = $api->getPrinter()->getBuffer();

		libxml_use_internal_errors( true );
		$sxe = simplexml_load_string( $resp );
		$this->assertNotInternalType( "bool", $sxe );
		$this->assertThat( $sxe, $this->isInstanceOf( "SimpleXMLElement" ) );
	}

	/**
	 * @covers ApiMain::lacksSameOriginSecurity
	 */
	public function testLacksSameOriginSecurity() {
		// Basic test
		$main = new ApiMain( new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ) ) );
		$this->assertFalse( $main->lacksSameOriginSecurity(), 'Basic test, should have security' );

		// JSONp
		$main = new ApiMain(
			new FauxRequest( array( 'action' => 'query', 'format' => 'xml', 'callback' => 'foo'  ) )
		);
		$this->assertTrue( $main->lacksSameOriginSecurity(), 'JSONp, should lack security' );

		// Header
		$request = new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ) );
		$request->setHeader( 'TrEaT-As-UnTrUsTeD', '' ); // With falsey value!
		$main = new ApiMain( $request );
		$this->assertTrue( $main->lacksSameOriginSecurity(), 'Header supplied, should lack security' );

		// Hook
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			'RequestHasSameOriginSecurity' => array( function () { return false; } )
		) );
		$main = new ApiMain( new FauxRequest( array( 'action' => 'query', 'meta' => 'siteinfo' ) ) );
		$this->assertTrue( $main->lacksSameOriginSecurity(), 'Hook, should lack security' );
	}
}
