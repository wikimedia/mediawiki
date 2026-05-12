<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Page\PageIdentity;
use MediaWiki\PageEdit\PageEditStatus;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;

/**
 * Verify authorization to edit the page (user rights, rate limits, blocks).
 *
 * @since 1.44
 * @internal
 */
class AuthorizationConstraint extends EditConstraint {

	public function __construct(
		private readonly Authority $performer,
		private readonly PageIdentity $target,
		private readonly bool $new,
	) {
	}

	public function checkConstraint(): PageEditStatus {
		$status = PermissionStatus::newEmpty();

		if ( $this->new && !$this->performer->authorizeWrite( 'create', $this->target, $status ) ) {
			return $this->castPermissionStatus( $status );
		}

		if ( !$this->performer->authorizeWrite( 'edit', $this->target, $status ) ) {
			return $this->castPermissionStatus( $status );
		}

		return $this->castPermissionStatus( $status );
	}

	private function castPermissionStatus( PermissionStatus $status ): PageEditStatus {
		if ( $status->isGood() ) {
			return PageEditStatus::cast( $status );
		}

		// Report the most specific errors first
		if ( $status->isBlocked() ) {
			$value = self::AS_BLOCKED_PAGE_FOR_USER;
		} elseif ( $status->isRateLimitExceeded() ) {
			$value = self::AS_RATE_LIMITED;
		} elseif ( $status->getPermission() === 'create' ) {
			$value = self::AS_NO_CREATE_PERMISSION;
		} elseif ( !$this->performer->isRegistered() ) {
			$value = self::AS_READ_ONLY_PAGE_ANON;
		} else {
			$value = self::AS_READ_ONLY_PAGE_LOGGED;
		}

		return PageEditStatus::cast( $status )
			->setErrorFunction( $status->throwErrorPageError( ... ) )
			->setResult( false, $value );
	}

}
