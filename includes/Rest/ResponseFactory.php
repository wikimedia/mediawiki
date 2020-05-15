<?php

namespace MediaWiki\Rest;

use HttpStatus;
use InvalidArgumentException;
use LanguageCode;
use MWExceptionHandler;
use stdClass;
use Throwable;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * Generates standardized response objects.
 */
class ResponseFactory {
	private const CT_PLAIN = 'text/plain; charset=utf-8';
	private const CT_HTML = 'text/html; charset=utf-8';
	private const CT_JSON = 'application/json';

	/** @var ITextFormatter[] */
	private $textFormatters;

	/**
	 * @param ITextFormatter[] $textFormatters
	 */
	public function __construct( $textFormatters ) {
		$this->textFormatters = $textFormatters;
	}

	/**
	 * Encode a stdClass object or array to a JSON string
	 *
	 * @param array|stdClass $value
	 * @return string
	 * @throws JsonEncodingException
	 */
	public function encodeJson( $value ) {
		$json = json_encode( $value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		if ( $json === false ) {
			throw new JsonEncodingException( json_last_error_msg(), json_last_error() );
		}
		return $json;
	}

	/**
	 * Create an unspecified response. It is the caller's responsibility to set specifics
	 * like response code, content type etc.
	 * @return Response
	 */
	public function create() {
		return new Response();
	}

	/**
	 * Create a successful JSON response.
	 * @param array|stdClass $value JSON value
	 * @param string|null $contentType HTTP content type (should be 'application/json+...')
	 *   or null for plain 'application/json'
	 * @return Response
	 */
	public function createJson( $value, $contentType = null ) {
		$contentType = $contentType ?? self::CT_JSON;
		$response = new Response( $this->encodeJson( $value ) );
		$response->setHeader( 'Content-Type', $contentType );
		return $response;
	}

	/**
	 * Create a 204 (No Content) response, used to indicate that an operation which does
	 * not return anything (e.g. a PUT request) was successful.
	 *
	 * Headers are generally interpreted to refer to the target of the operation. E.g. if
	 * this was a PUT request, the caller of this method might want to add an ETag header
	 * describing the created resource.
	 *
	 * @return Response
	 */
	public function createNoContent() {
		$response = new Response();
		$response->setStatus( 204 );
		return $response;
	}

	/**
	 * Creates a permanent (301) redirect.
	 * This indicates that the caller of the API should update their indexes and call
	 * the new URL in the future. 301 redirects tend to get cached and are hard to undo.
	 * Client behavior for methods other than GET/HEAD is not well-defined and this type
	 * of response should be avoided in such cases.
	 * @param string $target Redirect target (an absolute URL)
	 * @return Response
	 */
	public function createPermanentRedirect( $target ) {
		$response = $this->createRedirectBase( $target );
		$response->setStatus( 301 );
		return $response;
	}

	/**
	 * Creates a temporary (302) redirect.
	 * HTTP 302 was underspecified and has been superseded by 303 (when the redirected request
	 * should be a GET, regardless of what the current request is) and 307 (when the method should
	 * not be changed), but might still be needed for HTTP 1.0 clients or to match legacy behavior.
	 * @param string $target Redirect target (an absolute URL)
	 * @return Response
	 * @see self::createTemporaryRedirect()
	 * @see self::createSeeOther()
	 */
	public function createLegacyTemporaryRedirect( $target ) {
		$response = $this->createRedirectBase( $target );
		$response->setStatus( 302 );
		return $response;
	}

	/**
	 * Creates a temporary (307) redirect.
	 * This indicates that the operation the client was trying to perform can temporarily
	 * be achieved by using a different URL. Clients will preserve the request method when
	 * retrying the request with the new URL.
	 * @param string $target Redirect target (an absolute URL)
	 * @return Response
	 */
	public function createTemporaryRedirect( $target ) {
		$response = $this->createRedirectBase( $target );
		$response->setStatus( 307 );
		return $response;
	}

	/**
	 * Creates a See Other (303) redirect.
	 * This indicates that the target resource might be of interest to the client, without
	 * necessarily implying that it is the same resource. The client will always use GET
	 * (or HEAD) when following the redirection. Useful for GET-after-POST.
	 * @param string $target Redirect target (an absolute URL)
	 * @return Response
	 */
	public function createSeeOther( $target ) {
		$response = $this->createRedirectBase( $target );
		$response->setStatus( 303 );
		return $response;
	}

	/**
	 * Create a 304 (Not Modified) response, used when the client has an up-to-date cached response.
	 *
	 * Per RFC 7232 the response should contain all Cache-Control, Content-Location, Date,
	 * ETag, Expires, and Vary headers that would have been sent with the 200 OK answer
	 * if the requesting client did not have a valid cached response. This is the responsibility
	 * of the caller of this method.
	 *
	 * @return Response
	 */
	public function createNotModified() {
		$response = new Response();
		$response->setStatus( 304 );
		return $response;
	}

	/**
	 * Create a HTTP 4xx or 5xx response.
	 * @param int $errorCode HTTP error code
	 * @param array $bodyData An array of data to be included in the JSON response
	 * @return Response
	 * @throws InvalidArgumentException
	 */
	public function createHttpError( $errorCode, array $bodyData = [] ) {
		if ( $errorCode < 400 || $errorCode >= 600 ) {
			throw new InvalidArgumentException( 'error code must be 4xx or 5xx' );
		}
		$response = $this->createJson( $bodyData + [
			'httpCode' => $errorCode,
			'httpReason' => HttpStatus::getMessage( $errorCode )
		] );
		// TODO add link to error code documentation
		$response->setStatus( $errorCode );
		return $response;
	}

	/**
	 * Create an HTTP 4xx or 5xx response with error message localisation
	 *
	 * @param int $errorCode
	 * @param MessageValue $messageValue
	 * @param array $extraData An array of additional data to be included in the JSON response
	 *
	 * @return Response
	 */
	public function createLocalizedHttpError(
		$errorCode,
		MessageValue $messageValue,
		array $extraData = []
	) {
		return $this->createHttpError(
			$errorCode,
			array_merge( $extraData, $this->formatMessage( $messageValue ) )
		);
	}

	/**
	 * Turn a throwable into a JSON error response.
	 * @param Throwable $exception
	 * @return Response
	 */
	public function createFromException( Throwable $exception ) {
		if ( $exception instanceof LocalizedHttpException ) {
			$response = $this->createLocalizedHttpError(
				$exception->getCode(),
				$exception->getMessageValue(),
				(array)$exception->getErrorData()
			);
		} elseif ( $exception instanceof HttpException ) {
			// FIXME can HttpException represent 2xx or 3xx responses?
			$response = $this->createHttpError(
				$exception->getCode(),
				array_merge(
					[ 'message' => $exception->getMessage() ],
					(array)$exception->getErrorData()
				)
			);
		} else {
			$response = $this->createHttpError( 500, [
				'message' => 'Error: exception of type ' . get_class( $exception ),
				'exception' => MWExceptionHandler::getStructuredExceptionData( $exception )
			] );
			// FIXME should we try to do something useful with ILocalizedException?
			// FIXME should we try to do something useful with common MediaWiki errors like ReadOnlyError?
		}
		return $response;
	}

	/**
	 * Create a JSON response from an arbitrary value.
	 * This is a fallback; it's preferable to use createJson() instead.
	 * @param mixed $value A structure containing only scalars, arrays and stdClass objects
	 * @return Response
	 * @throws InvalidArgumentException When $value cannot be reasonably represented as JSON
	 */
	public function createFromReturnValue( $value ) {
		$originalValue = $value;
		if ( is_scalar( $value ) ) {
			$data = [ 'value' => $value ];
		} elseif ( is_array( $value ) || $value instanceof stdClass ) {
			$data = $value;
		} else {
			$type = gettype( $originalValue );
			if ( $type === 'object' ) {
				$type = get_class( $originalValue );
			}
			throw new InvalidArgumentException( __METHOD__ . ": Invalid return value type $type" );
		}
		$response = $this->createJson( $data );
		return $response;
	}

	/**
	 * Create a redirect response with type / response code unspecified.
	 * @param string $target Redirect target (an absolute URL)
	 * @return Response
	 */
	protected function createRedirectBase( $target ) {
		$response = new Response( $this->getHyperLink( $target ) );
		$response->setHeader( 'Content-Type', self::CT_HTML );
		$response->setHeader( 'Location', $target );
		return $response;
	}

	/**
	 * Returns a minimal HTML document that links to the given URL, as suggested by
	 * RFC 7231 for 3xx responses.
	 * @param string $url An absolute URL
	 * @return string
	 */
	protected function getHyperLink( $url ) {
		$url = htmlspecialchars( $url );
		return "<!doctype html><title>Redirect</title><a href=\"$url\">$url</a>";
	}

	public function formatMessage( MessageValue $messageValue ) {
		if ( !$this->textFormatters ) {
			// For unit tests
			return [];
		}
		$translations = [];
		foreach ( $this->textFormatters as $formatter ) {
			$lang = LanguageCode::bcp47( $formatter->getLangCode() );
			$messageText = $formatter->format( $messageValue );
			$translations[$lang] = $messageText;
		}
		return [ 'messageTranslations' => $translations ];
	}

}
