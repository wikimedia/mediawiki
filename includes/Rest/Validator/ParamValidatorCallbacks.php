<?php

namespace MediaWiki\Rest\Validator;

use InvalidArgumentException;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\User\UserIdentity;
use Psr\Http\Message\UploadedFileInterface;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\Callbacks;

class ParamValidatorCallbacks implements Callbacks {

	/** @var PermissionManager */
	private $permissionManager;

	/** @var RequestInterface */
	private $request;

	/** @var UserIdentity */
	private $user;

	public function __construct(
		PermissionManager $permissionManager,
		RequestInterface $request,
		UserIdentity $user
	) {
		$this->permissionManager = $permissionManager;
		$this->request = $request;
		$this->user = $user;
	}

	/**
	 * Get the raw parameters from a source in the request
	 * @param string $source 'path', 'query', or 'post'
	 * @return array
	 */
	private function getParamsFromSource( $source ) {
		switch ( $source ) {
			case 'path':
				return $this->request->getPathParams();

			case 'query':
				return $this->request->getQueryParams();

			case 'post':
				return $this->request->getPostParams();

			default:
				throw new InvalidArgumentException( __METHOD__ . ": Invalid source '$source'" );
		}
	}

	public function hasParam( $name, array $options ) {
		$params = $this->getParamsFromSource( $options['source'] );
		return isset( $params[$name] );
	}

	public function getValue( $name, $default, array $options ) {
		$params = $this->getParamsFromSource( $options['source'] );
		return $params[$name] ?? $default;
		// @todo Should normalization to NFC UTF-8 be done here (much like in the
		// action API and the rest of MW), or should it be left to handlers to
		// do whatever normalization they need?
	}

	public function hasUpload( $name, array $options ) {
		if ( $options['source'] !== 'post' ) {
			return false;
		}
		return $this->getUploadedFile( $name, $options ) !== null;
	}

	public function getUploadedFile( $name, array $options ) {
		if ( $options['source'] !== 'post' ) {
			return null;
		}
		$upload = $this->request->getUploadedFiles()[$name] ?? null;
		return $upload instanceof UploadedFileInterface ? $upload : null;
	}

	public function recordCondition(
		DataMessageValue $message, $name, $value, array $settings, array $options
	) {
		// @todo Figure out how to handle warnings
	}

	public function useHighLimits( array $options ) {
		return $this->permissionManager->userHasRight( $this->user, 'apihighlimits' );
	}

}
