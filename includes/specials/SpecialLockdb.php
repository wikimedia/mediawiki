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
 * A form to make the database read-only (eg for maintenance purposes).
 *
 * See also @ref $wgReadOnlyFile.
 *
 * @ingroup SpecialPage
 */
class SpecialLockdb extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'Lockdb', 'siteadmin' );
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
		if ( !is_writable( dirname( $this->getConfig()->get( MainConfigNames::ReadOnlyFile ) ) ) ) {
			throw new ErrorPageError( 'lockdb', 'lockfilenotwritable' );
		}
		if ( file_exists( $this->getConfig()->get( MainConfigNames::ReadOnlyFile ) ) ) {
			throw new ErrorPageError( 'lockdb', 'databaselocked' );
		}
	}

	/** @inheritDoc */
	protected function getFormFields() {
		return [
			'Reason' => [
				'type' => 'textarea',
				'rows' => 4,
				'label-message' => 'enterlockreason',
			],
			'Confirm' => [
				'type' => 'toggle',
				'label-message' => 'lockconfirm',
			],
		];
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegend( false )
			->setHeaderHtml( $this->msg( 'lockdbtext' )->parseAsBlock() )
			->setSubmitTextMsg( 'lockbtn' );
	}

	/** @inheritDoc */
	public function onSubmit( array $data ) {
		if ( !$data['Confirm'] ) {
			return Status::newFatal( 'locknoconfirm' );
		}

		AtEase::suppressWarnings();
		$fp = fopen( $this->getConfig()->get( MainConfigNames::ReadOnlyFile ), 'w' );
		AtEase::restoreWarnings();

		if ( $fp === false ) {
			# This used to show a file not found error, but the likeliest reason for fopen()
			# to fail at this point is insufficient permission to write to the file...good old
			# is_writable() is plain wrong in some cases, it seems...
			return Status::newFatal( 'lockfilenotwritable' );
		}
		fwrite( $fp, $data['Reason'] );
		$timestamp = wfTimestampNow();
		$contLang = $this->getContentLanguage();
		fwrite( $fp, "\n<p>" . $this->msg( 'lockedbyandtime',
			$this->getUser()->getName(),
			$contLang->date( $timestamp, false, false ),
			$contLang->time( $timestamp, false, false )
		)->inContentLanguage()->text() . "</p>\n" );
		fclose( $fp );

		return Status::newGood();
	}

	public function onSuccess() {
		$out = $this->getOutput();
		$out->addSubtitle( $this->msg( 'lockdbsuccesssub' ) );
		$out->addWikiMsg( 'lockdbsuccesstext' );
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

/** @deprecated class alias since 1.41 */
class_alias( SpecialLockdb::class, 'SpecialLockdb' );
