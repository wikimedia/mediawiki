<?php
/**
 * Redirect from Special:NewSection/$1 to index.php?title=$1&action=edit&section=new.
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
 * @author DannyS712
 */
class SpecialNewSection extends RedirectSpecialPage {
	public function __construct() {
		parent::__construct( 'NewSection' );
		$this->mAllowedRedirectParams = [ 'preloadtitle', 'nosummary', 'editintro',
			'preload', 'preloadparams', 'summary' ];
	}

	/**
	 * @inheritDoc
	 */
	public function getRedirect( $subpage ) {
		if ( $subpage === null || $subpage === '' ) {
			return false;
		}
		$this->mAddedRedirectParams['title'] = $subpage;
		$this->mAddedRedirectParams['action'] = 'edit';
		$this->mAddedRedirectParams['section'] = 'new';
		return true;
	}

	protected function showNoRedirectPage() {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:New section' );
		$this->showForm();
	}

	private function showForm() {
		$form = HTMLForm::factory( 'ooui', [
			'page' => [
				'type' => 'text',
				'name' => 'page',
				'label-message' => 'newsection-page',
				'required' => true,
			],
		], $this->getContext(), 'newsection' );
		$form->setSubmitTextMsg( 'newsection-submit' );
		$form->setSubmitCallback( [ $this, 'onFormSubmit' ] );
		$form->show();
	}

	public function onFormSubmit( $formData ) {
		$title = $formData['page'];
		try {
			$page = Title::newFromTextThrow( $title );
		} catch ( MalformedTitleException $e ) {
			return Status::newFatal( $e->getMessageObject() );
		}
		$query = [ 'action' => 'edit', 'section' => 'new' ];
		$url = $page->getFullUrlForRedirect( $query );
		$this->getOutput()->redirect( $url );
	}

	public function isListed() {
		return true;
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
