<?php
/**
 * Implements Special:Userbyid
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
 * A special page that redirects to the User corresponding to a given
 * numeric user id.
 *
 * @ingroup SpecialPage
 */
class SpecialUserbyid extends SpecialPage {

	function __construct() {
		parent::__construct( 'Userbyid' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();
		$userid = !is_null( $par ) ? $par : $request->getText( 'userid' );
		if ( ! is_numeric( $userid ) ) {
			$this->showForm( $userid );
		} else {
			$username = User::whoIs( intval( $userid ) );
			if ( ! $username ) {
				$this->getOutput()->setStatusCode( 404 );
				$this->showForm( $userid );
			} else {
				$userpage = Title::makeTitle( NS_USER, $username );
				$url = $userpage->getFullURL( '', false, PROTO_CURRENT );
				$this->getOutput()->redirect( $url );
			}
		}
	}

	/**
	 * @param $title Title
	 */
	function showForm( $userid ) {
		global $wgScript;

		$this->getOutput()->addHTML(
			Html::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'specialuserbyid' ) ) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', null, $this->msg( 'userbyid' )->text() ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
			Xml::inputLabel( $this->msg( 'userbyid-page' )->text(), 'userid', 'userid', 25, $userid ) . ' ' .
			Xml::submitButton( $this->msg( 'userbyid-submit' )->text() ) . "\n" .
			Html::closeElement( 'fieldset' ) .
			Html::closeElement( 'form' )
		);
	}

	protected function getGroupName() {
		return 'users';
	}
}
