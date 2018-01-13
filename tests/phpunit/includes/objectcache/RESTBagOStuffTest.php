<?php
/**
 * @group BagOStuff
 *
 * @covers RESTBagOStuff
 */
class RESTBagOStuffTest extends MediaWikiTestCase {

	/**
	 * @var MultiHttpClient
	 */
	private $client;
	/**
	 * @var RESTBagOStuff
	 */
	private $bag;

	public function setUp() {
		parent::setUp();
		$this->client =
			$this->getMockBuilder( MultiHttpClient::class )
				->setConstructorArgs( [ [] ] )
				->setMethods( [ 'run' ] )
				->getMock();
		$this->bag = new RESTBagOStuff( [ 'client' => $this->client, 'url' => 'http://test/rest/' ] );
	}

	public function testGet() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42'
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 200, 'OK', [], 's:8:"somedata";', 0 ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertEquals( 'somedata', $result );
	}

	public function testGetNotExist() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42'
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 404, 'Not found', [], 'Nothing to see here', 0 ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertFalse( $result );
	}

	public function testGetBadClient() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42'
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 0, '', [], '', 'cURL has failed you today' ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertFalse( $result );
		$this->assertEquals( BagOStuff::ERR_UNREACHABLE, $this->bag->getLastError() );
	}

	public function testGetBadServer() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42'
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 500, 'Too busy', [], 'Server is too busy', '' ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertFalse( $result );
		$this->assertEquals( BagOStuff::ERR_UNEXPECTED, $this->bag->getLastError() );
	}

	public function testPut() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'PUT',
			'url' => 'http://test/rest/42xyz42',
			'body' => 's:8:"postdata";'
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 200, 'OK', [], 'Done', 0 ] );
		$result = $this->bag->set( '42xyz42', 'postdata' );
		$this->assertTrue( $result );
	}

	public function testDelete() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'DELETE',
			'url' => 'http://test/rest/42xyz42',
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 200, 'OK', [], 'Done', 0 ] );
		$result = $this->bag->delete( '42xyz42' );
		$this->assertTrue( $result );
	}
}
