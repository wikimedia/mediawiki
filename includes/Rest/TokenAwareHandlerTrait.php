<?php

namespace MediaWiki\Rest;

use LogicException;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * This trait can be used on handlers that choose to support token-based CSRF protection. Note that doing so is
 * discouraged, and you should preferrably require that the endpoint be used with a session provider that is
 * safe against CSRF, such as OAuth.
 * @see Handler::requireSafeAgainstCsrf()
 *
 * @package MediaWiki\Rest
 */
trait TokenAwareHandlerTrait {
	/**
	 * Returns the definition for the token parameter, to be used in getBodyValidator().
	 *
	 * @return array[]
	 */
	protected function getTokenParamDefinition(): array {
		return [
			'token' => [
				Handler::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => '',
			]
		];
	}

	/**
	 * Determines the CSRF token to be used, possibly taking it from a request parameter.
	 *
	 * Returns an empty string if the request isn't known to be safe and
	 * no token was supplied by the client.
	 * Returns null if the session provider is safe against CSRF (and thus no token
	 * is needed)
	 *
	 * @return string|null
	 */
	protected function getToken(): ?string {
		if ( !$this instanceof Handler ) {
			throw new LogicException( 'This trait must be used on handler classes.' );
		}

		if ( $this->getSession()->getProvider()->safeAgainstCsrf() ) {
			return null;
		}

		$body = $this->getValidatedBody();
		return $body['token'] ?? '';
	}

	/**
	 * Returns a standard error message to use when the given CSRF token is invalid.
	 * In the future, this trait may also provide a method for checking the token.
	 *
	 * @return MessageValue
	 */
	protected function getBadTokenMessage(): MessageValue {
		return MessageValue::new( 'rest-badtoken' );
	}
}
