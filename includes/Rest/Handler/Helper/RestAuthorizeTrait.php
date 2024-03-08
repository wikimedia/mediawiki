<?php

namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Rest\HttpException;
use Wikimedia\Message\MessageValue;

trait RestAuthorizeTrait {
	use RestStatusTrait;

	/**
	 * Authorize an action
	 *
	 * @see Authroity::authorizeWrite
	 * @throws HttpException
	 */
	private function authorizeActionOrThrow(
		Authority $authority,
		string $action
	): void {
		$status = PermissionStatus::newEmpty();
		if ( !$authority->authorizeAction( $action, $status ) ) {
			$this->handleStatus( $status );
		}
	}

	/**
	 * Authorize a read operation
	 *
	 * @see Authroity::authorizeWrite
	 * @throws HttpException
	 */
	private function authorizeReadOrThrow(
		Authority $authority,
		string $action,
		PageIdentity $target
	): void {
		$status = PermissionStatus::newEmpty();
		if ( !$authority->authorizeRead( $action, $target, $status ) ) {
			$this->handleStatus( $status );
		}
	}

	/**
	 * Authorize a write operation
	 *
	 * @see Authroity::authorizeWrite
	 * @throws HttpException
	 */
	private function authorizeWriteOrThrow(
		Authority $authority,
		string $action,
		PageIdentity $target
	): void {
		$status = PermissionStatus::newEmpty();
		if ( !$authority->authorizeWrite( $action, $target, $status ) ) {
			$this->handleStatus( $status );
		}
	}

	/**
	 * Throw an exception if the status contains an error.
	 *
	 * @throws HttpException
	 * @return never
	 */
	private function handleStatus( PermissionStatus $status ): void {
		// The permission name should always be set, but don't explode if it isn't.
		$permission = $status->getPermission() ?: '(unknown)';

		if ( $status->isRateLimitExceeded() ) {
			$this->throwExceptionForStatus(
				$status,
				MessageValue::new( 'rest-rate-limit-exceeded', [ $permission ] ),
				429 // See https://www.rfc-editor.org/rfc/rfc6585#section-4
			);
		}

		$this->throwExceptionForStatus(
			$status,
			MessageValue::new( 'rest-permission-error', [ $permission ] ),
			403
		);
	}

}
