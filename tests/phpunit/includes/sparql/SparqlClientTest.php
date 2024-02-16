<?php

namespace MediaWiki\Tests\Sparql;

use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Sparql\SparqlClient;
use MWHttpRequest;

/**
 * @covers \MediaWiki\Sparql\SparqlClient
 */
class SparqlClientTest extends \PHPUnit\Framework\TestCase {

	private function getRequestFactory( $request ) {
		$requestFactory = $this->createMock( HttpRequestFactory::class );
		$requestFactory->method( 'create' )->willReturn( $request );
		return $requestFactory;
	}

	private function getRequestMock( $content ) {
		$request = $this->createMock( MWHttpRequest::class );
		$request->method( 'execute' )->willReturn( \MediaWiki\Status\Status::newGood( 200 ) );
		$request->method( 'getContent' )->willReturn( $content );
		return $request;
	}

	public function testQuery() {
		$json = <<<JSON
{
  "head" : {
    "vars" : [ "x", "y", "z" ]
  },
  "results" : {
    "bindings" : [ {
      "x" : {
        "type" : "uri",
        "value" : "http://wikiba.se/ontology#Dump"
      },
      "y" : {
        "type" : "uri",
        "value" : "http://creativecommons.org/ns#license"
      },
      "z" : {
        "type" : "uri",
        "value" : "http://creativecommons.org/publicdomain/zero/1.0/"
      }
    }, {
      "x" : {
        "type" : "uri",
        "value" : "http://wikiba.se/ontology#Dump"
      },
      "z" : {
        "type" : "literal",
        "value" : "0.1.0"
      }
    } ]
  }
}
JSON;

		$request = $this->getRequestMock( $json );
		$client = new SparqlClient( 'http://acme.test/', $this->getRequestFactory( $request ) );

		// values only
		$result = $client->query( "TEST SPARQL" );
		$this->assertCount( 2, $result );
		$this->assertEquals( 'http://wikiba.se/ontology#Dump', $result[0]['x'] );
		$this->assertEquals( 'http://creativecommons.org/ns#license', $result[0]['y'] );
		$this->assertSame( '0.1.0', $result[1]['z'] );
		$this->assertNull( $result[1]['y'] );
		// raw data format
		$result = $client->query( "TEST SPARQL 2", true );
		$this->assertCount( 2, $result );
		$this->assertEquals( 'uri', $result[0]['x']['type'] );
		$this->assertEquals( 'http://wikiba.se/ontology#Dump', $result[0]['x']['value'] );
		$this->assertEquals( 'literal', $result[1]['z']['type'] );
		$this->assertSame( '0.1.0', $result[1]['z']['value'] );
		$this->assertNull( $result[1]['y'] );
	}

	public function testBadQuery() {
		$request = $this->createMock( MWHttpRequest::class );
		$client = new SparqlClient( 'http://acme.test/', $this->getRequestFactory( $request ) );

		$request->method( 'execute' )->willReturn( \MediaWiki\Status\Status::newFatal( "Bad query" ) );
		$this->expectException( \MediaWiki\Sparql\SparqlException::class );
		$result = $client->query( "TEST SPARQL 3" );
	}

	public static function optionsProvider() {
		return [
			'defaults' => [
				'TEST тест SPARQL 4 ',
				null,
				null,
				[
					'http://acme.test/',
					'query=TEST+%D1%82%D0%B5%D1%81%D1%82+SPARQL+4+',
					'format=json',
					'maxQueryTimeMillis=30000',
				],
				[
					'method' => 'GET',
					'userAgent' => 'testOptions SparqlClient',
					'timeout' => 30
				]
			],
			'big query' => [
				str_repeat( 'ZZ', SparqlClient::MAX_GET_SIZE ),
				null,
				null,
				[
					'format=json',
					'maxQueryTimeMillis=30000',
				],
				[
					'method' => 'POST',
					'postData' => 'query=' . str_repeat( 'ZZ', SparqlClient::MAX_GET_SIZE ),
				]
			],
			'timeout 1s' => [
				'TEST SPARQL 4',
				null,
				1,
				[
					'maxQueryTimeMillis=1000',
				],
				[
					'timeout' => 1
				]
			],
			'more options' => [
				'TEST SPARQL 5',
				[
					'userAgent' => 'My Test',
					'randomOption' => 'duck',
				],
				null,
				[],
				[
					'userAgent' => 'My Test',
					'randomOption' => 'duck',
				]
			],

		];
	}

	/**
	 * @dataProvider  optionsProvider
	 * @param string $sparql
	 * @param array|null $options
	 * @param int|null $timeout
	 * @param array $expectedUrl
	 * @param array $expectedOptions
	 */
	public function testOptions( $sparql, $options, $timeout, $expectedUrl, $expectedOptions ) {
		$requestFactory = $this->createMock( HttpRequestFactory::class );
		$requestFactory->method( 'getUserAgent' )->willReturn( 'testOptions' );
		$client = new SparqlClient( 'http://acme.test/', $requestFactory );

		$request = $this->getRequestMock( '{}' );

		$requestFactory->method( 'create' )->willReturnCallback(
			function ( $url, $options ) use ( $request, $expectedUrl, $expectedOptions ) {
				foreach ( $expectedUrl as $eurl ) {
					$this->assertStringContainsString( $eurl, $url );
				}
				foreach ( $expectedOptions as $ekey => $evalue ) {
					$this->assertArrayHasKey( $ekey, $options );
					$this->assertEquals( $options[$ekey], $evalue );
				}
				return $request;
			}
		);

		if ( $options !== null ) {
			$client->setClientOptions( $options );
		}
		if ( $timeout !== null ) {
			$client->setTimeout( $timeout );
		}

		$result = $client->query( $sparql );
	}

}
