<?php

use Wikimedia\Http\MultiHttpClient;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\RESTBagOStuff;

/**
 * @group BagOStuff
 *
 * @covers \Wikimedia\ObjectCache\RESTBagOStuff
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

	protected function setUp(): void {
		parent::setUp();
		$this->client =
			$this->getMockBuilder( MultiHttpClient::class )
				->setConstructorArgs( [ [] ] )
				->onlyMethods( [ 'run' ] )
				->getMock();
		$this->bag = new RESTBagOStuff( [ 'client' => $this->client, 'url' => 'http://test/rest/' ] );
	}

	/**
	 * @dataProvider provideDataGet
	 */
	public function testGet( $serializationType, $hmacKey, $data ) {
		$classReflect = new ReflectionClass( RESTBagOStuff::class );

		$serializationTypeReflect = $classReflect->getProperty( 'serializationType' );
		$serializationTypeReflect->setValue( $this->bag, $serializationType );

		$hmacKeyReflect = $classReflect->getProperty( 'hmacKey' );
		$hmacKeyReflect->setValue( $this->bag, $hmacKey );

		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42',
			'headers' => []
			// [ $rcode, $rdesc, $rhdrs, $rbody, $rerr ]
		] )->willReturn( [ 200, 'OK', [], $data, 0 ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertEquals( 'somedata', $result );
	}

	public static function provideDataGet() {
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
			// [ $rcode, $rdesc, $rhdrs, $rbody, $rerr ]
		] )->willReturn( [ 404, 'Not found', [], 'Nothing to see here', 0 ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertFalse( $result );
	}

	public function testGetBadClient() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'GET',
			'url' => 'http://test/rest/42xyz42',
			'headers' => []
			// [ $rcode, $rdesc, $rhdrs, $rbody, $rerr ]
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
			// [ $rcode, $rdesc, $rhdrs, $rbody, $rerr ]
		] )->willReturn( [ 500, 'Too busy', [], 'Server is too busy', '' ] );
		$result = $this->bag->get( '42xyz42' );
		$this->assertFalse( $result );
		$this->assertEquals( BagOStuff::ERR_UNEXPECTED, $this->bag->getLastError() );
	}

	/**
	 * @dataProvider provideDataGet
	 */
	public function testPut( $serializationType, $hmacKey, $data ) {
		$classReflect = new ReflectionClass( RESTBagOStuff::class );

		$serializationTypeReflect = $classReflect->getProperty( 'serializationType' );
		$serializationTypeReflect->setValue( $this->bag, $serializationType );

		$hmacKeyReflect = $classReflect->getProperty( 'hmacKey' );
		$hmacKeyReflect->setValue( $this->bag, $hmacKey );

		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'PUT',
			'url' => 'http://test/rest/42xyz42',
			'body' => $data,
			'headers' => []
			// [ $rcode, $rdesc, $rhdrs, $rbody, $rerr ]
		] )->willReturn( [ 200, 'OK', [], 'Done', 0 ] );
		$result = $this->bag->set( '42xyz42', 'somedata' );
		$this->assertTrue( $result );
	}

	public function testDelete() {
		$this->client->expects( $this->once() )->method( 'run' )->with( [
			'method' => 'DELETE',
			'url' => 'http://test/rest/42xyz42',
			'headers' => []
			// [ $rcode, $rdesc, $rhdrs, $rbody, $rerr ]
		] )->willReturn( [ 200, 'OK', [], 'Done', 0 ] );
		$result = $this->bag->delete( '42xyz42' );
		$this->assertTrue( $result );
	}

	public function testSetStats() {
		$statsHelper = Wikimedia\Stats\StatsFactory::newUnitTestingHelper();
		$cache = new RESTBagOStuff( [ 'client' => $this->client,
			'url' => 'http://test/rest/', 'stats' => $statsHelper->getStatsFactory() ] );
		$cache->set( 'test_set12345', 'F4l65kZqWhoAnKW8ZTzekDYfrDxT' );

		$bagostuff_bytes_sent_total = $statsHelper->getStatsFactory()
			->getCounter( 'bagostuff_bytes_sent_total' )->getSamples();

		$this->assertEquals( 41, $bagostuff_bytes_sent_total[0]->getValue() );
	}
}
