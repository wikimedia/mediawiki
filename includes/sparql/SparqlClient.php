<?php

namespace MediaWiki\Sparql;

use MWHttpRequest;
use SPARQL\SparqlException;

/**
 * Simple SPARQL client
 *
 * @license GPL-2.0+
 * @author Stas Malyshev
 */
class SparqlClient {

	/**
	 * Limit on how long can be the query to be sent by GET.
	 */
	const MAX_GET_SIZE = 2048;

	/**
	 * User agent for HTTP requests.
	 * @var string
	 */
	const SPARQL_USER_AGENT = "Mediawiki SPARQL Client";

	/**
	 * Query timeout (seconds)
	 * @var int
	 */
	private $timeout = 30;

	/**
	 * SPARQL endpoint URL
	 * @var string
	 */
	private $endpoint;

	/**
	 * Client options
	 * @var array
	 */
	private $options = [];

	/**
	 * @param string $url SPARQL Endpoint
	 */
	public function __construct( $url ) {
		$this->endpoint = $url;
	}

	/**
	 * Set query timeout (in seconds)
	 * @param int $timeout
	 * @return $this
	 */
	public function setTimeout( $timeout ) {
		if ( $timeout >= 0 ) {
			$this->timeout = $timeout;
		}
		return $this;
	}

	/**
	 * Set client options
	 * @param array $options
	 * @return $this
	 */
	public function setClientOptions( $options ) {
		$this->options = $options;
		return $this;
	}

	/**
	 * Query SPARQL endpoint
	 *
	 * @param string $sparql query
	 * @param bool   $rawData Whether to return only values or full data objects
	 *
	 * @return array List of results, one row per array element
	 *               Each row will contain fields indexed by variable name.
	 * @throws SPARQLException
	 */
	public function query( $sparql, $rawData = false ) {
		$queryData = [ "query" => $sparql, "format" => "json" ];
		$options = array_merge( [ 'method' => 'GET' ], $this->options );

		if ( empty( $options['userAgent'] ) ) {
			$options['userAgent'] = self::SPARQL_USER_AGENT;
		}

		if ( $this->timeout >= 0 ) {
			// Blazegraph setting, see https://wiki.blazegraph.com/wiki/index.php/REST_API
			$queryData['maxQueryTimeMillis'] = $this->timeout * 1000;
			$options['timeout'] = $this->timeout;
		}

		if ( strlen($sparql) > self::MAX_GET_SIZE ) {
			// big requests go to POST
			$options['method'] = 'POST';
			$options['postData'] = 'query=' . urlencode( $sparql );
			unset( $queryData['query'] );
		}

		$url = $this->endpoint . '?' . http_build_query( $queryData );
		$request = MWHttpRequest::factory( $url, $options, __METHOD__ );

		$status = $request->execute();

		if ( !$status->isOK() ) {
			throw new SparqlException( "HTTP error: {$status->getWikiText()}" );
		}
		$result = $request->getContent();
		\MediaWiki\suppressWarnings();
		$data = json_decode( $result, true );
		\MediaWiki\restoreWarnings();
		if ( !$data ) {
			throw new SparqlException( "HTTP request failed, response:\n" .
			                           substr( $result, 1024 ) );
		}

		return $this->extractData( $data, $rawData );
	}

	/**
	 * Extract data from SPARQL response format.
	 * The response must be in format described in:
	 * https://www.w3.org/TR/sparql11-results-json/
	 *
	 * @param array $data SPARQL result
	 * @param bool  $rawData Whether to return only values or full data objects
	 *
	 * @return array List of results, one row per element.
	 */
	private function extractData( $data, $rawData = false ) {
		$result = [];
		if ( $data && !empty( $data['results'] ) ) {
			$vars = $data['head']['vars'];
			$resrow = [];
			foreach ( $data['results']['bindings'] as $row ) {
				foreach ( $vars as $var ) {
					if ( !isset( $row[$var] ) ) {
						$resrow[$var] = null;
						continue;
					}
					if ( $rawData ) {
						$resrow[$var] = $row[$var];
					} else {
						$resrow[$var] = $row[$var]['value'];
					}
				}
				$result[] = $resrow;
			}
		}
		return $result;
	}

}
