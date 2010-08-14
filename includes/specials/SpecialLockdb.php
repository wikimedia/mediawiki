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
		global $wgUser, $wgOut, $wgRequest;

		$this->setHeaders();

		if( !$wgUser->isAllowed( 'siteadmin' ) ) {
			$wgOut->permissionRequired( 'siteadmin' );
			return;
		}

		$this->outputHeader();

		# If the lock file isn't writable, we can do sweet bugger all
		global $wgReadOnlyFile;
		if( !is_writable( dirname( $wgReadOnlyFile ) ) ) {
			self::notWritable();
			return;
		}

		$action = $wgRequest->getVal( 'action' );
		$this->reason = $wgRequest->getVal( 'wpLockReason', '' );

		if ( $action == 'success' ) {
			$this->showSuccess();
		} else if ( $action == 'submit' && $wgRequest->wasPosted() &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
			$this->doSubmit();
		} else {
			$this->showForm();
		}
	}

	private function showForm( $err = '' ) {
		global $wgOut, $wgUser;

		$wgOut->addWikiMsg( 'lockdbtext' );

		if ( $err != '' ) {
			$wgOut->setSubtitle( wfMsg( 'formerror' ) );
			$wgOut->addHTML( '<p class="error">' . htmlspecialchars( $err ) . "</p>\n" );
		}

		$wgOut->addHTML(
			Html::openElement( 'form', array( 'id' => 'lockdb', 'method' => 'POST',
				'action' => $this->getTitle()->getLocalURL( 'action=submit' ) ) ). "\n" .
			wfMsgHtml( 'enterlockreason' ) . ":\n" .
			Html::textarea( 'wpLockReason', $this->reason, array( 'rows' => 4 ) ). "
<table>
	<tr>
		" . Html::openElement( 'td', array( 'style' => 'text-align:right' ) ) . "
			" . Html::input( 'wpLockConfirm', null, 'checkbox' ) . "
		</td>
		" . Html::openElement( 'td', array( 'style' => 'text-align:left' ) ) .
			wfMsgHtml( 'lockconfirm' ) . "</td>
	</tr>
	<tr>
		<td>&#160;</td>
		" . Html::openElement( 'td', array( 'style' => 'text-align:left' ) ) . "
			" . Html::input( 'wpLock', wfMsg( 'lockbtn' ), 'submit' ) . "
		</td>
	</tr>
</table>\n" .
			Html::hidden( 'wpEditToken', $wgUser->editToken() ) . "\n" .
			Html::closeElement( 'form' )
		);

	}

	private function doSubmit() {
		global $wgOut, $wgUser, $wgContLang, $wgRequest;
		global $wgReadOnlyFile;

		if ( ! $wgRequest->getCheck( 'wpLockConfirm' ) ) {
			$this->showForm( wfMsg( 'locknoconfirm' ) );
			return;
		}
		$fp = @fopen( $wgReadOnlyFile, 'w' );

		if ( false === $fp ) {
			# This used to show a file not found error, but the likeliest reason for fopen()
			# to fail at this point is insufficient permission to write to the file...good old
			# is_writable() is plain wrong in some cases, it seems...
			self::notWritable();
			return;
		}
		fwrite( $fp, $this->reason );
		fwrite( $fp, "\n<p>(by " . $wgUser->getName() . " at " .
		  $wgContLang->timeanddate( wfTimestampNow() ) . ")</p>\n" );
		fclose( $fp );

		$wgOut->redirect( $this->getTitle()->getFullURL( 'action=success' ) );
	}

	private function showSuccess() {
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( 'lockdb' ) );
		$wgOut->setSubtitle( wfMsg( 'lockdbsuccesssub' ) );
		$wgOut->addWikiMsg( 'lockdbsuccesstext' );
	}

	public static function notWritable() {
		global $wgOut;
		$wgOut->showErrorPage( 'lockdb', 'lockfilenotwritable' );
	}
}
