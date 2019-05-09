<?php

namespace MediaWiki\Rest;

/**
 * MOCK UP ONLY
 * @unstable
 */
class ResponseFactory {
	const CT_PLAIN = 'text/plain; charset=utf-8';
	const CT_JSON = 'application/json';

	public function create404() {
		$response = new Response( 'Path not found' );
		$response->setStatus( 404 );
		$response->setHeader( 'Content-Type', self::CT_PLAIN );
		return $response;
	}

	public function create500( $message ) {
		$response = new Response( $message );
		$response->setStatus( 500 );
		$response->setHeader( 'Content-Type', self::CT_PLAIN );
		return $response;
	}

	public function createFromException( \Exception $exception ) {
		if ( $exception instanceof HttpException ) {
			$response = new Response( $exception->getMessage() );
			$response->setStatus( $exception->getCode() );
			$response->setHeader( 'Content-Type', self::CT_PLAIN );
			return $response;
		} else {
			return $this->create500( "Error: exception of type " . gettype( $exception ) );
		}
	}

	public function createFromReturnValue( $value ) {
		if ( is_scalar( $value )
			|| ( is_object( $value ) && method_exists( $value, '__toString' ) )
		) {
			$value = [ 'value' => (string)$value ];
		}
		$json = json_encode( $value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		if ( $json === false ) {
			throw new JsonEncodingException( json_last_error_msg(), json_last_error() );
		}
		$response = new Response( $json );
		$response->setHeader( 'Content-Type', self::CT_JSON );
		return $response;
	}
}
