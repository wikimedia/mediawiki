<?php
/**
 * Implements Special:Unlockdb
 *
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
 * @ingroup SpecialPage
 */

/**
 * Implements Special:Unlockdb
 *
 * @ingroup SpecialPage
 */
class SpecialUnlockdb extends SpecialPage {

	public function __construct() {
		parent::__construct( 'Unlockdb', 'siteadmin' );
	}

	public function execute( $par ) {
		$this->setHeaders();

		# Permission check
		if( !$this->userCanExecute( $this->getUser() ) ) {
			$this->displayRestrictionError();
			return;
		}

		$this->outputHeader();

		$request = $this->getRequest();
		$action = $request->getVal( 'action' );

		if ( $action == 'success' ) {
			$this->showSuccess();
		} elseif ( $action == 'submit' && $request->wasPosted() &&
			$this->getUser()->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			$this->doSubmit();
		} else {
			$this->showForm();
		}
	}

	private function showForm( $err = '' ) {
		global $wgReadOnlyFile;

		$out = $this->getOutput();

		if( !file_exists( $wgReadOnlyFile ) ) {
			$out->addWikiMsg( 'databasenotlocked' );
			return;
		}

		$out->addWikiMsg( 'unlockdbtext' );

		if ( $err != '' ) {
			$out->setSubtitle( wfMsg( 'formerror' ) );
			$out->addHTML( '<p class="error">' . htmlspecialchars( $err ) . "</p>\n" );
		}

		$out->addHTML(
			Html::openElement( 'form', array( 'id' => 'unlockdb', 'method' => 'POST',
				'action' => $this->getTitle()->getLocalURL( 'action=submit' ) ) ) . "
<table>
	<tr>
		" . Html::openElement( 'td', array( 'style' => 'text-align:right' ) ) . "
			" . Html::input( 'wpLockConfirm', null, 'checkbox',  array( 'id' => 'mw-input-wpunlockconfirm' )  ) . "
		</td>
		" . Html::openElement( 'td', array( 'style' => 'text-align:left' ) ) .
			Html::openElement( 'label', array( 'for' => 'mw-input-wpunlockconfirm' ) ) .
			wfMsgHtml( 'unlockconfirm' ) . "</label>
		</td>
	</tr>
	<tr>
		<td>&#160;</td>
		" . Html::openElement( 'td', array( 'style' => 'text-align:left' ) ) . "
			" . Html::input( 'wpLock', wfMsg( 'unlockbtn' ), 'submit' ) . "
		</td>
	</tr>
</table>\n" .
			Html::hidden( 'wpEditToken', $this->getUser()->editToken() ) . "\n" .
			Html::closeElement( 'form' )
		);

	}

	private function doSubmit() {
		global $wgReadOnlyFile;

		if ( !$this->getRequest()->getCheck( 'wpLockConfirm' ) ) {
			$this->showForm( wfMsg( 'locknoconfirm' ) );
			return;
		}

		wfSuppressWarnings();
		$res = unlink( $wgReadOnlyFile );
		wfRestoreWarnings();

		if ( $res ) {
			$this->getOutput()->redirect( $this->getTitle()->getFullURL( 'action=success' ) );
		} else {
			$this->getOutput()->addWikiMsg( 'filedeleteerror', $wgReadOnlyFile );
		}
	}

	private function showSuccess() {
		$out = $this->getOutput();
		$out->setSubtitle( wfMsg( 'unlockdbsuccesssub' ) );
		$out->addWikiMsg( 'unlockdbsuccesstext' );
	}
}
