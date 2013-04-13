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
class SpecialUserbyid extends FormSpecialPage {
	/**
	 * The sub-page of the special page.
	 * @var string
	 */
	protected $par = null;

	function __construct() {
		parent::__construct( 'Userbyid', 'read' );
	}

	protected function setParameter( $par ) {
		/* save subpage, so we can use it in getFormFields() */
		$this->par = $par;
	}

	function getFormFields() {
		return array(
			'UserID' => array(
				'type' => 'text',
				'label-message' => 'userbyid-value',
				'default' => $this->par,
				'required' => true,
				'validation-callback' => array( $this, 'checkUserID' ),
			)
		);
	}

	function checkUserID( $value, $alldata ) {
		if ( $value === '' || $value === null ) {
			return true;
		}
		if ( !ctype_digit( $value ) ) {
			return $this->msg( 'htmlform-int-invalid' )->parseAsBlock();
		}
		$user = User::newFromId( (int)$value );
		$user->load();
		if ( $user->isAnon() ) {
			$this->getOutput()->setStatusCode( 404 );
			return $this->msg( 'userbyid-not-exists' )->parseAsBlock();
		}
		return true;
	}

	function alterForm( HTMLForm $form ) {
		/* display summary at top of page */
		$this->outputHeader();
		/* submit the form every time */
		$form->setMethod( 'get' );
	}

	function onSubmit( array $data ) {
		$user = User::newFromId( (int)$data['UserID'] );
		$user->load();
		if ( !$user->isAnon() ) {
			$this->getOutput()->redirect( $user->getUserPage()->getFullURL() );
		}
	}

	function onSuccess() {
		/* do nothing; we redirect if successful in onSubmit() */
	}

	protected function getGroupName() {
		return 'users';
	}
}
