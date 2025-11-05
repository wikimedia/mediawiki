<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\IApiMessage;

/**
 * A StatusValue for upload verification errors.
 *
 * This status will never have a value. It's only used to keep track of errors.
 *
 * @unstable
 * @since 1.44
 * @extends StatusValue<never>
 */
class UploadVerificationStatus extends StatusValue {

	private bool $recoverableError = false;
	private ?string $invalidParameter = null;
	private ?array $apiData = null;
	private ?string $apiCode = null;

	public function setRecoverableError( bool $recoverableError ): self {
		$this->recoverableError = $recoverableError;
		return $this;
	}

	public function setInvalidParameter( string $invalidParameter ): self {
		$this->invalidParameter = $invalidParameter;
		return $this;
	}

	public function setApiData( array $apiData ): self {
		$this->apiData = $apiData;
		return $this;
	}

	public function setApiCode( string $apiCode ): self {
		$this->apiCode = $apiCode;
		return $this;
	}

	public function isRecoverableError(): bool {
		return $this->recoverableError;
	}

	public function getInvalidParameter(): ?string {
		return $this->invalidParameter;
	}

	public function asApiMessage(): IApiMessage {
		$messages = $this->getMessages();
		return ApiMessage::create( $messages[0], $this->apiCode, $this->apiData );
	}
}
