<?php
/**
 * Redirect from Special:PermanentLink/### to index.php?oldid=###.
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
 * Redirect from Special:PermanentLink/### to index.php?oldid=###.
 *
 * @ingroup SpecialPage
 */
class SpecialPermanentLink extends SpecialPage {
	function __construct() {
		parent::__construct( 'PermanentLink' );
	}

	public function execute( $subpage ) {
		$request = $this->getRequest();
		$opts = new FormOptions;
		$opts->add( 'revid', '' );
		$opts->fetchValuesFromRequest( $request );

		$out = $this->getOutput();
		$subpage = intval( $subpage );
		if ( $subpage === 0 && $opts->getValue( 'revid' ) ) {
			$subpage = intval( $opts->getValue( 'revid' ) );
		}
		if ( $subpage === 0 ) {
			$this->setHeaders();
			$this->outputHeader();

			$form = new HTMLForm( array(
				'revid' => array(
					'type' => 'int',
					'name' => 'revid',
					'label-message' => 'permanentlink-revid',
				),
			), $this->getContext(), 'permanentlink' );
			$form->setSubmitTextMsg( 'permanentlink-submit' );
			$form->setMethod( 'get' );
			$form->setSubmitCallback( array( __CLASS__, 'getPermanentLink' ) );
			$form->setDisplayFormat( 'vform' );
			$form->show();
			return true;
		}
		$url = wfAppendQuery( wfScript( 'index' ), array( 'oldid' => $subpage ) );
		$out->redirect( $url );
		return true;
	}

	static function getPermanentLink( $formData, $form ) {
		// If it returns true, the form is hidden.
		return false;
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
