<?php

namespace MediaWiki\Rest\Validator;

use InvalidArgumentException;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\RequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use UtfNormal\Validator;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\Callbacks;

class ParamValidatorCallbacks implements Callbacks {

	private RequestInterface $request;
	private Authority $authority;

	public function __construct(
		RequestInterface $request,
		Authority $authority
	) {
		$this->request = $request;
		$this->authority = $authority;
	}

	/**
	 * Get the raw parameters from a source in the request
	 * @param string $source 'path', 'query', or 'post'
	 * @return array
	 */
	private function getParamsFromSource( $source ) {
		// This switch block must match Validator::KNOWN_PARAM_SOURCES
		switch ( $source ) {
			case 'path':
				return $this->request->getPathParams();

			case 'query':
				return $this->request->getQueryParams();

			case 'post':
				wfDeprecatedMsg( 'The "post" source is deprecated, use "body" instead', '1.43' );
				return $this->request->getPostParams();

			case 'body':
				return $this->request->getParsedBody() ?? [];

			default:
				throw new InvalidArgumentException( __METHOD__ . ": Invalid source '$source'" );
		}
	}

	/** @inheritDoc */
	public function hasParam( $name, array $options ) {
		$params = $this->getParamsFromSource( $options['source'] );
		return isset( $params[$name] );
	}

	/** @inheritDoc */
	public function getValue( $name, $default, array $options ) {
		$params = $this->getParamsFromSource( $options['source'] );
		$value = $params[$name] ?? $default;

		// Normalisation for body is being handled in Handler::parseBodyData
		if ( !isset( $options['raw'] ) && $options['source'] !== 'body' ) {
			if ( is_string( $value ) ) {
				// Normalize value to NFC UTF-8
				$normalizedValue = Validator::cleanUp( $value );
				// TODO: Warn if normalization was applied

				$value = $normalizedValue;
			}
		}

		return $value;
	}

	/** @inheritDoc */
	public function hasUpload( $name, array $options ) {
		if ( $options['source'] !== 'post' ) {
			return false;
		}
		return $this->getUploadedFile( $name, $options ) !== null;
	}

	/** @inheritDoc */
	public function getUploadedFile( $name, array $options ) {
		if ( $options['source'] !== 'post' ) {
			return null;
		}
		$upload = $this->request->getUploadedFiles()[$name] ?? null;
		return $upload instanceof UploadedFileInterface ? $upload : null;
	}

	/** @inheritDoc */
	public function recordCondition(
		DataMessageValue $message, $name, $value, array $settings, array $options
	) {
		// @todo Figure out how to handle warnings
	}

	/** @inheritDoc */
	public function useHighLimits( array $options ) {
		return $this->authority->isAllowed( 'apihighlimits' );
	}

}
