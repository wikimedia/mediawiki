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
