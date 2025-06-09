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

namespace MediaWiki\Exception;

use MediaWiki\Context\RequestContext;
use MediaWiki\Debug\DeprecationHelper;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionStatus;

/**
 * Show an error when a user tries to do something they do not have the necessary
 * permissions for.
 *
 * @newable
 * @since 1.18
 * @ingroup Exception
 */
class PermissionsError extends ErrorPageError {

	use DeprecationHelper;

	private ?string $permission;
	private PermissionStatus $status;

	/**
	 * @stable to call
	 *
	 * @param string|null $permission A permission name or null if unknown
	 * @param PermissionStatus|array $status PermissionStatus containing an array of errors,
	 *   or an error array like in PermissionManager::getPermissionErrors();
	 *   must not be empty if $permission is null
	 */
	public function __construct( ?string $permission, $status = [] ) {
		$this->deprecatePublicProperty( 'permission', '1.43' );
		$this->deprecatePublicPropertyFallback( 'errors', '1.43',
			function () {
				return $this->status->toLegacyErrorArray();
			},
			function ( $errors ) {
				$this->status = PermissionStatus::newEmpty();
				foreach ( $errors as $error ) {
					if ( is_array( $error ) ) {
						// @phan-suppress-next-line PhanParamTooFewUnpack
						$this->status->fatal( ...$error );
					} else {
						$this->status->fatal( $error );
					}
				}
			}
		);

		if ( is_array( $status ) ) {
			$errors = $status;
			$status = PermissionStatus::newEmpty();
			foreach ( $errors as $error ) {
				if ( is_array( $error ) ) {
					// @phan-suppress-next-line PhanParamTooFewUnpack
					$status->fatal( ...$error );
				} else {
					$status->fatal( $error );
				}
			}
		} elseif ( !( $status instanceof PermissionStatus ) ) {
			throw new \InvalidArgumentException( __METHOD__ .
				': $status must be PermissionStatus or array, got ' . get_debug_type( $status ) );
		}

		if ( $permission === null && $status->isGood() ) {
			throw new \InvalidArgumentException( __METHOD__ .
				': $permission and $status cannot both be empty' );
		}

		$this->permission = $permission;

		if ( $status->isGood() ) {
			$status = MediaWikiServices::getInstance()
				->getPermissionManager()
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Null on permission is check when used here
				->newFatalPermissionDeniedStatus( $this->permission, RequestContext::getMain() );
		}

		$this->status = $status;

		// Give the parent class something to work with
		parent::__construct( 'permissionserrors', $status->getMessages()[0] );
	}

	/** @inheritDoc */
	public function report( $action = self::SEND_OUTPUT ) {
		global $wgOut;

		$wgOut->showPermissionStatus( $this->status, $this->permission );
		if ( $action === self::SEND_OUTPUT ) {
			$wgOut->output();
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( PermissionsError::class, 'PermissionsError' );
