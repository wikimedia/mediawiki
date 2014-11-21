<?php
/**
 * Redirect from Special:Diff/### to index.php?diff=### and
 * from Special:Diff/###/### to index.php?oldid=###&diff=###.
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
 * Redirect from Special:Diff/### to index.php?diff=### and
 * from Special:Diff/###/### to index.php?oldid=###&diff=###.
 *
 * All of the following are valid usages:
 * - [[Special:Diff/12345]] (diff of a revision with the previous one)
 * - [[Special:Diff/12345/prev]] (diff of a revision with the previous one as well)
 * - [[Special:Diff/12345/next]] (diff of a revision with the next one)
 * - [[Special:Diff/12345/cur]] (diff of a revision with the latest one of that page)
 * - [[Special:Diff/12345/98765]] (diff between arbitrary two revisions)
 *
 * @ingroup SpecialPage
 * @since 1.23
 */
class SpecialDiff extends SpecialPage {
	function __construct() {
		parent::__construct( 'Diff' );
	}

	public function execute( $subpage ) {
		$request = $this->getRequest();
		$opts = new FormOptions;
		$opts->add( 'diff', '' );
		$opts->add( 'oldid', '' );
		$opts->fetchValuesFromRequest( $request );

		$params = array();

		$out = $this->getOutput();
		$parts = explode( '/', $subpage );

		// Try to parse the values given, generating somewhat pretty URLs if possible
		if ( count( $parts ) === 1 && $parts[0] !== '' ) {
			$params['diff'] = $parts[0];
		} elseif ( count( $parts ) === 2 ) {
			$params['oldid'] = $parts[0];
			$params['diff'] = $parts[1];
		} elseif ( $opts->getValue( 'diff' ) || $opts->getValue( 'oldid' ) ) {
			$params['oldid'] = $opts->getValue( 'oldid' );
			$params['diff'] = $opts->getValue( 'diff' );
		} else {
			// Wrong number of parameters, show form

			$this->setHeaders();
			$this->outputHeader();

			$form = new HTMLForm( array(
				'oldid' => array(
					'name' => 'oldid',
					'type' => 'int',
					'label-message' => 'diff-form-oldid',
				),
				'diff' => array(
					'name' => 'diff',
					'class' => 'HTMLTextField',
					'label-message' => 'diff-form-revid',
				),
			), $this->getContext(), 'diff-form' );
			$form->setSubmitTextMsg( 'diff-form-submit' );
			$form->setMethod( 'get' );
			$form->setSubmitCallback( array( __CLASS__, 'callbackDiff' ) );
			$form->setDisplayFormat( 'vform' );
			$form->show();
			return true;
		}
		$url = wfAppendQuery( wfScript( 'index' ), $params );
		$out->redirect( $url );
		return true;
	}

	static function callbackDiff( $formData ) {
		// If it returns true, the form is hidden.
		return false;
	}

	function getDescription() {
		// 'diff' message is in lowercase, using own message
		return $this->msg( 'diff-form' )->text();
	}

	function getName() {
		return 'diff-form';
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
