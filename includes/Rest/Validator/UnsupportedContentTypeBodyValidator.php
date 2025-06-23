<?php

namespace MediaWiki\Rest\Validator;

use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestInterface;
use Wikimedia\Message\MessageValue;

/**
 * Validator that always fails. Meant as a convenience for Handler::getBodyValidator():
 *
 *     public function getBodyValidator( $contentType ) {
 *         if ( $contentType === 'supported/content-type' ) {
 *             return new MyValidator();
 *         }
 *         return new UnsupportedContentTypeBodyValidator( $contentType );
 *     }
 *
 * @since 1.40
 */
class UnsupportedContentTypeBodyValidator implements BodyValidator {

	private string $contentType;

	public function __construct( string $contentType ) {
		$this->contentType = $contentType;
	}

	/**
	 * @inheritDoc
	 * @return never
	 */
	public function validateBody( RequestInterface $request ): never {
		throw new LocalizedHttpException(
			new MessageValue( 'rest-unsupported-content-type', [ $this->contentType ] ),
			415
		);
	}

}
