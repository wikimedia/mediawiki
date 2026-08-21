<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\Page\PageReference;
use MediaWiki\PageEdit\PageEditStatus;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;

/**
 * Verify user permissions:
 *    If creating a redirect in the file namespace, must have upload rights
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ImageRedirectConstraint extends EditConstraint {

	public function __construct(
		private readonly Content $newContent,
		private readonly PageReference $page,
		private readonly Authority $performer,
	) {
	}

	public function checkConstraint(): PageEditStatus {
		$status = PermissionStatus::newGood();
		// Check isn't simple enough to just repeat when getting the status
		if ( $this->page->getNamespace() === NS_FILE &&
			$this->newContent->isRedirect() &&
			!$this->performer->isAllowed( 'upload', $status )
		) {
			$errorCode = $this->performer->getUser()->isRegistered() ?
				self::AS_IMAGE_REDIRECT_LOGGED :
				self::AS_IMAGE_REDIRECT_ANON;
			return PageEditStatus::newGood( $errorCode )
				->setOK( false )
				->setErrorFunction( $status->throwErrorPageError( ... ) );
		}

		return PageEditStatus::newGood();
	}

}
