<?php
/**
 * Base class for dynamo clients.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Cache
 */

/**
 * Base class for dynamo clients, geared towards https://github.com/Netflix/dyno
 *
 * The dyno daemon is open source and can use redis or memcached as a backend
 *
 * @ingroup Cache
 */
class DynamoBagOStuff extends BagOStuff {
	/** @var string */
	protected $accessKey;
	/** @var string */
	protected $secretKey;
	/** @var string */
	protected $authUrl;
	/** @var string */
	protected $storageUrl;
	/** @var string */
	protected $storageHost;
	/** @var array */
	protected $credentials;

	/** @var MultiHttpClient */
	protected $http;

	function __construct( array $params ) {
		parent::__construct( $params );

		$this->accessKey = $params['accessKey'];
		$this->secretKey = $params['secretKey'];
		$this->authUrl = $params['authUrl'];
		$this->storageUrl = $params['storageUrl'];

		$urlInfo = parse_url( $this->storageUrl );
		$this->storageHost = $urlInfo['host'];

		$this->http = new MultiHttpClient( [] );
		$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_BE; // unreliable
	}

	protected function doGet( $key, $flags = 0 ) {
		$casToken = null;

		return $this->getWithToken( $key, $casToken, $flags );
	}

	protected function getWithToken( $key, &$casToken, $flags = 0 ) {
		$result = $this->tryStorageRequest(
			'GetItem',
			[
				'TableName' => 'ObjectCache',
				'Key' => [
					'Name' => [ 'S' => $key ]
				],
				'ConsistentRead' => ( $flags & self::READ_LATEST ) ? true : false
			]
		);

		if ( is_array( $result ) && isset( $result['Item'] ) ) {
			if ( $result['Item']['Expires'] < time() ) {
				return false;
			}

			$casToken = $result['Item']['Token'];

			return $this->unserialize( $result['Item']['Value'] );
		}

		return false;
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$type = is_int( $value ) ? 'I' : 'S';
		$result = $this->tryStorageRequest(
			'PutItem',
			[
				'TableName' => 'ObjectCache',
				'Item' => [
					'Name' => [ 'S' => $key ],
					'Value' => [ $type => $this->serialize( $value ) ],
					'Expires' => [ 'I' => time() + $exptime ],
					'Token' => mt_rand( 0, 2 ^ 31 - 1 )
				]
			]
		);

		if ( is_array( $result ) ) {
			return true;
		}

		return false;
	}

	protected function cas( $casToken, $key, $value, $exptime = 0 ) {
		$type = is_int( $value ) ? 'I' : 'S';
		$result = $this->tryStorageRequest(
			'UpdateItem',
			[
				'TableName' => 'ObjectCache',
				'Key' => [
					'Name' => [ 'S' => $key ]
				],
				'UpdateExpression' => "set Value = :newvalue, Token = :newtoken, Expires = :newexp",
    			'ConditionExpression' => "Token = :oldtoken",
    			'ExpressionAttributeValues' => [
					':newvalue' => [ $type => $this->serialize( $value ) ],
					':oldtoken' => [ 'I' => $casToken ],
					':newtoken' => [ 'I' => mt_rand( 0, 2 ^ 31 - 1 ) ],
					':newexp' => [ 'I' => time() + $exptime ],
				],
			]
		);

		if ( is_array( $result ) ) {
			return true;
		}

		return false;
	}

	public function delete( $key ) {
		$result = $this->tryStorageRequest(
			'DeleteItem',
			[
				'TableName' => 'ObjectCache',
				'Key' => [
					'Name' => [ 'S' => $key ]
				]
			]
		);

		if ( is_array( $result ) ) {
			return true;
		}

		return false;
	}

	public function add( $key, $value, $exptime = 0 ) {
		$type = is_int( $value ) ? 'I' : 'S';
		$result = $this->tryStorageRequest(
			'PutItem',
			[
				'TableName' => 'ObjectCache',
				'Item' => [
					'Name' => [ 'S' => $key ],
					'Value' => [ $type => $this->serialize( $value ) ]
				],
				'ConditionExpression' => "Name <> :f",
    			'ExpressionAttributeValues' => [
					':f' => [ 'S' => $key ]
				]
			]
		);

		if ( is_array( $result ) ) {
			return true;
		}

		return false;
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		return $this->mergeViaCas( $key, $callback, $exptime, $attempts );
	}

	public function changeTTL( $key, $exptime = 0 ) {
		$result = $this->tryStorageRequest(
			'UpdateItem',
			[
				'TableName' => 'ObjectCache',
				'Key' => [
					'Name' => [ 'S' => $key ]
				],
				'UpdateExpression' => "set Expires = :newexp",
				'ExpressionAttributeValues' => [
					':newexp' => [ 'I' => time() + $exptime ],
				],
			]
		);

		if ( is_array( $result ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param mixed $data
	 * @return string
	 */
	protected function serialize( $data ) {
		// Serialize anything but integers so INCR/DECR work
		// Do not store integer-like strings as integers to avoid type confusion (bug 60563)
		return is_int( $data ) ? $data : serialize( $data );
	}

	/**
	 * @param string $data
	 * @return mixed
	 */
	protected function unserialize( $data ) {
		$int = intval( $data );
		return $data === (string)$int ? $int : unserialize( $data );
	}

	private function getCredentials() {
		if ( $this->credentials ) {
			return $this->credentials;
		}

		$res = $this->runAuthRequest( 'GetSessionToken', [ 'DurationSeconds' => 86400 ] );
		if ( is_object( $res ) && isset( $res->GetSessionTokenResult ) ) {
			$result = $res->GetSessionTokenResult;
			$this->credentials = [
				'AccessKey' => $result->Credentials->AccessKeyId,
				'SecretKey' => $result->Credentials->SecretAccessKey,
				'SessionToken' => $result->Credentials->SessionToken
			];
		}

		return $this->credentials;
	}

	/**
	 * @param $action
	 * @param array $options
	 * @return SimpleXMLElement|int
	 */
	private function runAuthRequest( $action, array $options = [] ) {
		$params = [
			'AWSAccessKeyId' => $this->accessKey,
			'Version' => '2011-06-15',
			'SignatureMethod' => 'HmacSHA256',
			'SignatureVersion' => 2,
			'Timestamp' => gmdate( 'Y-m-d\TH:i:s\Z' ),
			'Action' => $action
		] + $options;

		ksort( $params );
		$normalizedParams = [];
		foreach ( $params as $name => $value ) {
			$normalizedParams[] =
				str_replace( '%7E', '~', rawurlencode( $name ) ) .
				'=' .
				str_replace( '%7E', '~', rawurlencode( $value ) );
		}
		$normalizedParams = implode( '&', $normalizedParams );

		$urlParts = parse_url( $this->authUrl );

		$params['Signature'] = base64_encode( hash_hmac(
			'sha256',
			"POST\n{$urlParts['host']}\n{$urlParts['path']}\n{$normalizedParams}",
			$this->secretKey,
			true
		) );

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
			'method' => 'POST',
			'url' => $this->authUrl,
			'body' => $params
		] );

		if ( $rcode >= 200 && $rcode < 300 ) {
			return simplexml_load_string( $rbody );
		}

		return $rcode;
	}

	/**
	 * @param string $action
	 * @param array $options Map to be JSON encoded
	 * @return array|int
	 */
	private function runStoreRequest( $action, array $options = [] ) {
		$credentials = $this->getCredentials();
		if ( $credentials === false ) {
			return 403;
		}

		$headers = [
			'host' => $this->storageHost,
			'x-amz-date' => gmdate( DATE_RFC2822 ),
			'x-amz-target' => "DynamoDB_20111205.{$action}",
			'x-amz-security-token' => $credentials['SessionToken'],
			'content-type' => 'application/x-amz-json-1.0'
		];

		ksort( $headers );
		$normalizedHeaders = '';
		foreach ( $headers as $name => $value ) {
			$normalizedHeaders .= "{$name}:{$value}\n";
		}
		$body = json_encode( $options, JSON_FORCE_OBJECT );
		$digest = "POST\n/\n\n{$normalizedHeaders}\n{$body}";
		$signature = base64_encode( hash_hmac(
			'sha256',
			hash( 'sha256', $digest, true ),
			$credentials['SecretKey'],
			true
		) );

		$authParams = [
			'AWSAccessKeyId' => $credentials['AccessKey'],
			'Algorithm' => 'HmacSHA256',
			'SignedHeaders' => implode( ';', array_keys( $headers ) ),
			'Signature' => $signature
		];

		$normalizedAuthParams = [];
		foreach ( $authParams as $key => $value ) {
			$normalizedAuthParams[] = "{$key}={$value}";
		}
		$headers['x-amzn-authorization'] = 'AWS3 ' . implode( ',', $normalizedAuthParams );

		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $this->http->run( [
			'method' => 'POST',
			'url' => "{$this->storageUrl}/",
			'headers' => $headers,
			'body' => $body
		] );

		if ( $rcode >= 200 && $rcode < 300 ) {
			return json_decode( $rbody, true );
		}

		return $rcode;
	}

	/**
	 * @param string $action
	 * @param array $options Map to be JSON encoded
	 * @return array|int
	 */
	private function tryStorageRequest( $action, array $options = [] ) {
		$response = $this->runStoreRequest( $action, $options );
		if ( $response === 401 ) {
			$this->credentials = null;
			$response = $this->runStoreRequest( $action, $options );
		}

		return $response;
	}
}
