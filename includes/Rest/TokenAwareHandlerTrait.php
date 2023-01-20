<?php

namespace MediaWiki\Rest;

use LoggedOutEditToken;
use LogicException;
use MediaWiki\Session\Session;
use Wikimedia\Message\DataMessageValue;
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
	abstract public function getValidatedBody();

	abstract public function getSession(): Session;

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
		return DataMessageValue::new( 'rest-badtoken' );
	}

	/**
	 * Checks that the given CSRF token is valid (or the used authentication method does
	 * not require CSRF).
	 * Note that this method only supports the 'csrf' token type. The body validator must
	 * return an array and include the 'token' field (see getTokenParamDefinition()).
	 * @param bool $allowAnonymousToken Allow anonymous users to pass the check by submitting
	 *   an empty token. (This matches how e.g. anonymous editing works on the action API and web.)
	 * @return void
	 * @throws LocalizedHttpException
	 */
	protected function validateToken( bool $allowAnonymousToken = false ): void {
		if ( $this->getSession()->getProvider()->safeAgainstCsrf() ) {
			return;
		}

		$submittedToken = $this->getToken();
		$sessionToken = null;
		$isAnon = $this->getSession()->getUser()->isAnon();
		if ( $allowAnonymousToken && $isAnon ) {
			$sessionToken = new LoggedOutEditToken();
		} elseif ( $this->getSession()->hasToken() ) {
			$sessionToken = $this->getSession()->getToken();
		}

		if ( $sessionToken && $sessionToken->match( $submittedToken ) ) {
			return;
		} elseif ( !$submittedToken ) {
			throw $this->getBadTokenException( 'rest-badtoken-missing' );
		} elseif ( $isAnon && !$this->getSession()->isPersistent() ) {
			// The client probably forgot to authenticate.
			throw $this->getBadTokenException( 'rest-badtoken-nosession' );
		} else {
			// The user submitted a token, the session had a token, but they didn't match.
			throw new LocalizedHttpException( $this->getBadTokenMessage(), 403 );
		}
	}

	/**
	 * @param string $messageKey
	 * @return LocalizedHttpException
	 * @internal For use by the trait only
	 */
	private function getBadTokenException( string $messageKey ): LocalizedHttpException {
		return new LocalizedHttpException( DataMessageValue::new( $messageKey, [], 'rest-badtoken' ), 403 );
	}
}
