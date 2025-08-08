<?php
/**
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
