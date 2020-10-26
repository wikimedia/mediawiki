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

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\Spi;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserIdentity;
use ReadOnlyMode;
use Title;
use User;

/**
 * Constraints reflect possible errors that need to be checked
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class EditConstraintFactory {

	/** @var Spi */
	private $loggerFactory;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var SpamChecker */
	private $spamRegexChecker;

	/**
	 * Some constraints have dependencies that need to be injected,
	 * this class serves as a factory for all of the different constraints
	 * that need dependencies injected.
	 *
	 * The checks in EditPage use wfDebugLog and logged to different channels, hence the need
	 * for multiple loggers retrieved from the Spi. TODO can they be combined into the same channel?
	 *
	 * @param Spi $loggerFactory
	 * @param PermissionManager $permissionManager
	 * @param ReadOnlyMode $readOnlyMode
	 * @param SpamChecker $spamRegexChecker
	 */
	public function __construct(
		Spi $loggerFactory,
		PermissionManager $permissionManager,
		ReadOnlyMode $readOnlyMode,
		SpamChecker $spamRegexChecker
	) {
		// Multiple
		$this->loggerFactory = $loggerFactory;
		$this->permissionManager = $permissionManager;

		// ReadOnlyConstraint
		$this->readOnlyMode = $readOnlyMode;

		// SpamRegexConstraint
		$this->spamRegexChecker = $spamRegexChecker;
	}

	/**
	 * @param User $user
	 * @param Title $title
	 * @param string $newContentModel
	 * @return ContentModelChangeConstraint
	 */
	public function newContentModelChangeConstraint(
		User $user,
		Title $title,
		string $newContentModel
	) : ContentModelChangeConstraint {
		return new ContentModelChangeConstraint(
			$this->permissionManager,
			$user,
			$title,
			$newContentModel
		);
	}

	/**
	 * @param User $user
	 * @return EditRightConstraint
	 */
	public function newEditRightConstraint( User $user ) : EditRightConstraint {
		return new EditRightConstraint(
			$this->permissionManager,
			$user
		);
	}

	/**
	 * @param LinkTarget $title
	 * @param bool $isRedirect
	 * @param User $user
	 * @return ImageRedirectConstraint
	 */
	public function newImageRedirectConstraint(
		LinkTarget $title,
		bool $isRedirect,
		User $user
	) : ImageRedirectConstraint {
		return new ImageRedirectConstraint(
			$this->permissionManager,
			$title,
			$isRedirect,
			$user
		);
	}

	/**
	 * @return ReadOnlyConstraint
	 */
	public function newReadOnlyConstraint() : ReadOnlyConstraint {
		return new ReadOnlyConstraint(
			$this->readOnlyMode
		);
	}

	/**
	 * @param string $input
	 * @param UserIdentity $user
	 * @param Title $title
	 * @return SimpleAntiSpamConstraint
	 */
	public function newSimpleAntiSpamConstraint(
		string $input,
		UserIdentity $user,
		Title $title
	) : SimpleAntiSpamConstraint {
		return new SimpleAntiSpamConstraint(
			$this->loggerFactory->getLogger( 'SimpleAntiSpam' ),
			$input,
			$user,
			$title
		);
	}

	/**
	 * @param string $summary
	 * @param string $sectionHeading
	 * @param string $text
	 * @param string $reqIP
	 * @param Title $title
	 * @return SpamRegexConstraint
	 */
	public function newSpamRegexConstraint(
		string $summary,
		string $sectionHeading,
		string $text,
		string $reqIP,
		Title $title
	) : SpamRegexConstraint {
		return new SpamRegexConstraint(
			$this->loggerFactory->getLogger( 'SpamRegex' ),
			$this->spamRegexChecker,
			$summary,
			$sectionHeading,
			$text,
			$reqIP,
			$title
		);
	}

	/**
	 * @param LinkTarget $title
	 * @param User $user
	 * @return UserBlockConstraint
	 */
	public function newUserBlockConstraint(
		LinkTarget $title,
		User $user
	) : UserBlockConstraint {
		return new UserBlockConstraint(
			$this->permissionManager,
			$title,
			$user
		);
	}

}
