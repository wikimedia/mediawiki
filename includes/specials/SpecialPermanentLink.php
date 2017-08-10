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
class SpecialPermanentLink extends RedirectSpecialPage {
	public function __construct() {
		parent::__construct( 'PermanentLink' );
		$this->mAllowedRedirectParams = [];
	}

	/**
	 * @param string|null $subpage
	 * @return Title|bool
	 */
	public function getRedirect( $subpage ) {
		$subpage = intval( $subpage );
		if ( $subpage === 0 ) {
			return false;
		}
		$this->mAddedRedirectParams['oldid'] = $subpage;

		return true;
	}

	protected function showNoRedirectPage() {
		$this->setHeaders();
		$this->outputHeader();
		$this->showForm();
	}

	private function showForm() {
		$form = HTMLForm::factory( 'ooui', [
			'revid' => [
				'type' => 'int',
				'name' => 'revid',
				'label-message' => 'permanentlink-revid',
			],
		], $this->getContext(), 'permanentlink' );
		$form->setSubmitTextMsg( 'permanentlink-submit' );
		$form->setSubmitCallback( [ $this, 'onFormSubmit' ] );
		$form->show();
	}

	public function onFormSubmit( $formData ) {
		$revid = $formData['revid'];
		$title = $this->getPageTitle( $revid ?: null );
		$url = $title->getFullUrlForRedirect();
		$this->getOutput()->redirect( $url );
	}

	public function isListed() {
		return true;
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
