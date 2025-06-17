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

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\SpecialPage\RedirectSpecialPage;
use MediaWiki\Title\Title;

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
class SpecialDiff extends RedirectSpecialPage {
	public function __construct() {
		parent::__construct( 'Diff' );
		$this->mAllowedRedirectParams = [];
	}

	/**
	 * @param string|null $subpage
	 * @return Title|bool
	 */
	public function getRedirect( $subpage ) {
		$parts = $subpage !== null ? explode( '/', $subpage ) : [];

		// Try to parse the values given, generating somewhat pretty URLs if possible
		if ( count( $parts ) === 1 && $parts[0] !== '' ) {
			$this->mAddedRedirectParams['diff'] = $parts[0];
		} elseif ( count( $parts ) === 2 ) {
			$this->mAddedRedirectParams['oldid'] = $parts[0];
			$this->mAddedRedirectParams['diff'] = $parts[1];
		} else {
			return false;
		}

		return true;
	}

	protected function showNoRedirectPage() {
		$this->addHelpLink( 'Help:Diff' );
		$this->setHeaders();
		$this->outputHeader();
		$this->showForm();
	}

	private function showForm() {
		$form = HTMLForm::factory( 'ooui', [
			'oldid' => [
				'name' => 'oldid',
				'type' => 'int',
				'label-message' => 'diff-form-oldid',
			],
			'diff' => [
				'name' => 'diff',
				// FIXME Set the type for the other field to int - T256425
				'type' => 'selectorother',
				'options-messages' => [
					'diff-form-other-revid' => 'other',
					'last' => 'prev',
					'cur' => 'cur',
					'next' => 'next',
				],
				'label-message' => 'diff-form-revid',
				// Remove validation callback when using int type - T256425
				'validation-callback' => function ( $value ) {
					$value = trim( $value ?? '' );
					if ( preg_match( '/^\d*$/', $value )
						|| in_array( $value, [ 'prev', 'cur', 'next' ], true )
					) {
						return true;
					}
					return $this->msg( 'diff-form-error-revid' );
				},
			],
		], $this->getContext(), 'diff-form' );
		$form->setSubmitTextMsg( 'diff-form-submit' );
		$form->setSubmitCallback( $this->onFormSubmit( ... ) );
		$form->show();
	}

	/**
	 * @param array $formData
	 */
	private function onFormSubmit( $formData ) {
		$params = [];
		if ( $formData['oldid'] ) {
			$params[] = $formData['oldid'];
		}
		if ( $formData['diff'] ) {
			// Remove trim when using int type - T256425
			$params[] = trim( $formData['diff'] );
		}
		$title = $this->getPageTitle( $params ? implode( '/', $params ) : null );
		$url = $title->getFullUrlForRedirect();
		$this->getOutput()->redirect( $url );
	}

	public function getDescription() {
		// 'diff' message is in lowercase, using own message
		return $this->msg( 'diff-form' );
	}

	public function getName() {
		return 'diff-form';
	}

	public function isListed() {
		return true;
	}

	protected function getGroupName() {
		return 'redirects';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialDiff::class, 'SpecialDiff' );
