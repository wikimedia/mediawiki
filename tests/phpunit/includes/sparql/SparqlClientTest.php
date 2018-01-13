<?php
namespace MediaWiki\Sparql;

use MWHttpRequest;
use PHPUnit_Framework_TestCase;

/**
 * @covers \MediaWiki\Sparql\SparqlClient
 */
class SparqlClientTest extends PHPUnit_Framework_TestCase {

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	private function getHttpRequest() {
		$request = $this->getMockBuilder( MWHttpRequest::class )->disableOriginalConstructor()->getMock();
		return $request;
	}

	public function testQuery() {
		$client = new SparqlClient( 'http://query.wikidata.org/' );
		$request = $this->getHttpRequest();
		$client->setClientOptions([
			'httpRequestImplementation' => $request
		]);

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

		$request->method( 'execute' )->willReturn( \Status::newGood( 200 ) );
		$request->method( 'getContent' )->willReturn( $json );
		// values only
		$result = $client->query( "TEST SPARQL" );
		$this->assertCount( 2, $result );
		$this->assertEquals( 'http://wikiba.se/ontology#Dump', $result[0]['x'] );
		$this->assertEquals( 'http://creativecommons.org/ns#license', $result[0]['y'] );
		$this->assertEquals( '0.1.0', $result[1]['z'] );
		$this->assertNull( $result[1]['y'] );
		// raw data format
		$result = $client->query( "TEST SPARQL 2", true );
		$this->assertCount( 2, $result );
		$this->assertEquals( 'uri', $result[0]['x']['type'] );
		$this->assertEquals( 'http://wikiba.se/ontology#Dump', $result[0]['x']['value'] );
		$this->assertEquals( 'literal', $result[1]['z']['type'] );
		$this->assertEquals( '0.1.0', $result[1]['z']['value'] );
		$this->assertNull( $result[1]['y'] );
	}

	/**
	 * @expectedException \Mediawiki\Sparql\SparqlException
	 */
	public function testBadQuery() {
		$client = new SparqlClient( 'http://query.wikidata.org/' );
		$request = $this->getHttpRequest();
		$client->setClientOptions([
			'httpRequestImplementation' => $request
		]);
		$request->method( 'execute' )->willReturn( \Status::newFatal( "Bad query" ) );
		$result = $client->query( "TEST SPARQL 3" );
	}

}
