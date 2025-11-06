<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use Wikimedia\AtEase\AtEase;

/**
 * Implements Special:Unlockdb
 *
 * @see SpecialLockDb
 * @ingroup SpecialPage
 */
class SpecialUnlockdb extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'Unlockdb', 'siteadmin' );
	}

	/** @inheritDoc */
	public function doesWrites() {
		return false;
	}

	/** @inheritDoc */
	public function requiresWrite() {
		return false;
	}

	public function checkExecutePermissions( User $user ) {
		parent::checkExecutePermissions( $user );
		# If the lock file isn't writable, we can do sweet bugger all
		if ( !file_exists( $this->getConfig()->get( MainConfigNames::ReadOnlyFile ) ) ) {
			throw new ErrorPageError( 'lockdb', 'databasenotlocked' );
		}
	}

	/** @inheritDoc */
	protected function getFormFields() {
		return [
			'Confirm' => [
				'type' => 'toggle',
				'label-message' => 'unlockconfirm',
			],
		];
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegend( false )
			->setHeaderHtml( $this->msg( 'unlockdbtext' )->parseAsBlock() )
			->setSubmitTextMsg( 'unlockbtn' );
	}

	/** @inheritDoc */
	public function onSubmit( array $data ) {
		if ( !$data['Confirm'] ) {
			return Status::newFatal( 'locknoconfirm' );
		}

		$readOnlyFile = $this->getConfig()->get( MainConfigNames::ReadOnlyFile );
		AtEase::suppressWarnings();
		$res = unlink( $readOnlyFile );
		AtEase::restoreWarnings();

		if ( $res ) {
			return Status::newGood();
		} else {
			return Status::newFatal( 'filedeleteerror', $readOnlyFile );
		}
	}

	public function onSuccess() {
		$out = $this->getOutput();
		$out->addSubtitle( $this->msg( 'unlockdbsuccesssub' ) );
		$out->addWikiMsg( 'unlockdbsuccesstext' );
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'wiki';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUnlockdb::class, 'SpecialUnlockdb' );
