<?php
/**
 * Implements Special:Lockdb
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
 * A form to make the database readonly (eg for maintenance purposes).
 *
 * @ingroup SpecialPage
 */
class SpecialLockdb extends SpecialPage {
	var $reason = '';

	public function __construct() {
		parent::__construct( 'Lockdb', 'siteadmin' );
	}

	public function execute( $par ) {
		$this->setHeaders();

		# Permission check
		if( !$this->userCanExecute( $this->getUser() ) ) {
			$this->displayRestrictionError();
			return;
		}

		$this->outputHeader();

		# If the lock file isn't writable, we can do sweet bugger all
		global $wgReadOnlyFile;
		if( !is_writable( dirname( $wgReadOnlyFile ) ) ) {
			$this->getOutput()->addWikiMsg( 'lockfilenotwritable' );
			return;
		}

		$request = $this->getRequest();
		$action = $request->getVal( 'action' );
		$this->reason = $request->getVal( 'wpLockReason', '' );

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
		$out = $this->getOutput();
		$out->addWikiMsg( 'lockdbtext' );

		if ( $err != '' ) {
			$out->setSubtitle( wfMsg( 'formerror' ) );
			$out->addHTML( '<p class="error">' . htmlspecialchars( $err ) . "</p>\n" );
		}

		$out->addHTML(
			Html::openElement( 'form', array( 'id' => 'lockdb', 'method' => 'POST',
				'action' => $this->getTitle()->getLocalURL( 'action=submit' ) ) ). "\n" .
			wfMsgHtml( 'enterlockreason' ) . ":\n" .
			Html::textarea( 'wpLockReason', $this->reason, array( 'rows' => 4 ) ). "
<table>
	<tr>
		" . Html::openElement( 'td', array( 'style' => 'text-align:right' ) ) . "
			" . Html::input( 'wpLockConfirm', null, 'checkbox', array( 'id' => 'mw-input-wplockconfirm' ) ) . "
		</td>
		" . Html::openElement( 'td', array( 'style' => 'text-align:left' ) ) .
			Html::openElement( 'label', array( 'for' => 'mw-input-wplockconfirm' ) ) .
			wfMsgHtml( 'lockconfirm' ) . "</label>
		</td>
	</tr>
	<tr>
		<td>&#160;</td>
		" . Html::openElement( 'td', array( 'style' => 'text-align:left' ) ) . "
			" . Html::input( 'wpLock', wfMsg( 'lockbtn' ), 'submit' ) . "
		</td>
	</tr>
</table>\n" .
			Html::hidden( 'wpEditToken', $this->getUser()->editToken() ) . "\n" .
			Html::closeElement( 'form' )
		);

	}

	private function doSubmit() {
		global $wgContLang, $wgReadOnlyFile;

		if ( !$this->getRequest()->getCheck( 'wpLockConfirm' ) ) {
			$this->showForm( wfMsg( 'locknoconfirm' ) );
			return;
		}

		wfSuppressWarnings();
		$fp = fopen( $wgReadOnlyFile, 'w' );
		wfRestoreWarnings();

		if ( false === $fp ) {
			# This used to show a file not found error, but the likeliest reason for fopen()
			# to fail at this point is insufficient permission to write to the file...good old
			# is_writable() is plain wrong in some cases, it seems...
			$this->getOutput()->addWikiMsg( 'lockfilenotwritable' );
			return;
		}
		fwrite( $fp, $this->reason );
		$timestamp = wfTimestampNow();
		fwrite( $fp, "\n<p>" . wfMsgExt(
			'lockedbyandtime',
			array( 'content', 'parsemag' ),
			$this->getUser()->getName(),
			$wgContLang->date( $timestamp ),
			$wgContLang->time( $timestamp )
		) . "</p>\n" );
		fclose( $fp );

		$this->getOutput()->redirect( $this->getTitle()->getFullURL( 'action=success' ) );
	}

	private function showSuccess() {
		$out = $this->getOutput();
		$out->setPagetitle( wfMsg( 'lockdb' ) );
		$out->setSubtitle( wfMsg( 'lockdbsuccesssub' ) );
		$out->addWikiMsg( 'lockdbsuccesstext' );
	}
}
