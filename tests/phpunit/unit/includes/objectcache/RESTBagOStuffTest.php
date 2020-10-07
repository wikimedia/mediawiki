<?php
/**
 * @group BagOStuff
 *
 * @covers RESTBagOStuff
 */
class RESTBagOStuffTest extends \MediaWikiUnitTestCase {

	/**
	 * @var MultiHttpClient
	 */
	private $client;
	/**
	 * @var RESTBagOStuff
	 */
	private $bag;

	protected function setUp() : void {
		parent::setUp();
		$this->client =
			$this->getMockBuilder( MultiHttpClient::class )
				->setConstructorArgs( [ [] ] )
				->setMethods( [ 'run' ] )
				->getMock();
		$this->bag = new RESTBagOStuff( [ 'client' => $this->client, 'url' => 'http://test/rest/' ] );
	}

	/**
	 * @dataProvider dataGet
	 */
	public function testGet( $serializationType, $hmacKey, $data ) {
		$classReflect = new ReflectionClass( RESTBagOStuff::class );

		$serializationTypeReflect = $classReflect->getProperty( 'serializationType' );
		$serializationTypeReflect->setAccessible( true );
		$serializationTypeReflect->setValue( $this->bag, $serializationType );

		$hmacKeyReflect = $classReflect->getProperty( 'hmacKey' );
		$hmacKeyReflect->setAccessible( true );
		$hmacKeyReflect->setValue( $this->bag, $hmacKey );

		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42',
			'headers' => []
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 200, 'OK', [], $data, 0 ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertEquals( 'somedata', $result );
	}

	public static function dataGet() {
		// Make sure the defaults are last, so the $bag is left as expected for the next test
		return [
			[ 'JSON', '12345', 'JSON.Us1wli82zEJ6DNQnCG//w+MShOFrdx9wCdfTUhPPA2w=."somedata"' ],
			[ 'JSON', '', 'JSON.."somedata"' ],
			[ 'PHP', '12345', 'PHP.t2EKhUF4l65kZqWhoAnKW8ZPzekDYfrDxTkQcVmGsuM=.s:8:"somedata";' ],
			[ 'PHP', '', 'PHP..s:8:"somedata";' ],
		];
	}

	public function testGetNotExist() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42',
			'headers' => []
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 404, 'Not found', [], 'Nothing to see here', 0 ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertFalse( $result );
	}

	public function testGetBadClient() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42',
			'headers' => []
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 0, '', [], '', 'cURL has failed you today' ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertFalse( $result );
		$this->assertEquals( BagOStuff::ERR_UNREACHABLE, $this->bag->getLastError() );
	}

	public function testGetBadServer() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42',
			'headers' => []
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 500, 'Too busy', [], 'Server is too busy', '' ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertFalse( $result );
		$this->assertEquals( BagOStuff::ERR_UNEXPECTED, $this->bag->getLastError() );
	}

	/**
	 * @dataProvider dataPut
	 */
	public function testPut( $serializationType, $hmacKey, $data ) {
		$classReflect = new ReflectionClass( RESTBagOStuff::class );

		$serializationTypeReflect = $classReflect->getProperty( 'serializationType' );
		$serializationTypeReflect->setAccessible( true );
		$serializationTypeReflect->setValue( $this->bag, $serializationType );

		$hmacKeyReflect = $classReflect->getProperty( 'hmacKey' );
		$hmacKeyReflect->setAccessible( true );
		$hmacKeyReflect->setValue( $this->bag, $hmacKey );

		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'PUT',
			'url' => 'http://test/rest/42xyz42',
			'body' => $data,
			'headers' => []
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 200, 'OK', [], 'Done', 0 ] );
		$result = $this->bag->set( '42xyz42', 'somedata' );
		$this->assertTrue( $result );
	}

	public static function dataPut() {
		// Make sure the defaults are last, so the $bag is left as expected for the next test
		return [
			[ 'JSON', '12345', 'JSON.Us1wli82zEJ6DNQnCG//w+MShOFrdx9wCdfTUhPPA2w=."somedata"' ],
			[ 'JSON', '', 'JSON.."somedata"' ],
			[ 'PHP', '12345', 'PHP.t2EKhUF4l65kZqWhoAnKW8ZPzekDYfrDxTkQcVmGsuM=.s:8:"somedata";' ],
			[ 'PHP', '', 'PHP..s:8:"somedata";' ],
		];
	}

	public function testDelete() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'DELETE',
			'url' => 'http://test/rest/42xyz42',
			'headers' => []
			// list( $rcode, $rdesc, $rhdrs, $rbody, $rerr )
		] )->willReturn( [ 200, 'OK', [], 'Done', 0 ] );
		$result = $this->bag->delete( '42xyz42' );
		$this->assertTrue( $result );
	}
}
