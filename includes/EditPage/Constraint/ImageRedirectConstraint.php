<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\Authority;

/**
 * Verify user permissions:
 *    If creating a redirect in the file namespace, must have upload rights
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ImageRedirectConstraint implements IEditConstraint {

	/**
	 * @param Content $newContent
	 * @param LinkTarget $title
	 * @param Authority $performer
	 */
	public function __construct(
		private readonly Content $newContent,
		private readonly LinkTarget $title,
		private readonly Authority $performer,
	) {
	}

	public function checkConstraint(): EditPageStatus {
		// Check isn't simple enough to just repeat when getting the status
		if ( $this->title->getNamespace() === NS_FILE &&
			$this->newContent->isRedirect() &&
			!$this->performer->isAllowed( 'upload' )
		) {
			$errorCode = $this->performer->getUser()->isRegistered() ?
				self::AS_IMAGE_REDIRECT_LOGGED :
				self::AS_IMAGE_REDIRECT_ANON;
			return EditPageStatus::newGood( $errorCode )
				->setOK( false )
				->setErrorFunction( static fn () => throw new PermissionsError( 'upload' ) );
		}

		return EditPageStatus::newGood();
	}

}
