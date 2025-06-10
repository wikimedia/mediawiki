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
use SearchEngineFactory;

/**
 * Redirect from Special:NewSection/$1 to index.php?title=$1&action=edit&section=new.
 *
 * @ingroup SpecialPage
 * @author DannyS712
 */
class SpecialNewSection extends RedirectSpecialPage {

	private SearchEngineFactory $searchEngineFactory;

	public function __construct(
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'NewSection' );
		$this->mAllowedRedirectParams = [ 'preloadtitle', 'nosummary', 'editintro',
			'preload', 'preloadparams', 'summary' ];
		$this->searchEngineFactory = $searchEngineFactory;
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
				'type' => 'title',
				'name' => 'page',
				'label-message' => 'newsection-page',
				'required' => true,
				'creatable' => true,
			],
		], $this->getContext(), 'newsection' );
		$form->setSubmitTextMsg( 'newsection-submit' );
		$form->setSubmitCallback( $this->onFormSubmit( ... ) );
		$form->show();
	}

	/**
	 * @param array $formData
	 */
	private function onFormSubmit( $formData ) {
		$title = $formData['page'];
		$page = Title::newFromTextThrow( $title );
		$query = [ 'action' => 'edit', 'section' => 'new' ];
		$url = $page->getFullUrlForRedirect( $query );
		$this->getOutput()->redirect( $url );
	}

	public function isListed() {
		return true;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	protected function getGroupName() {
		return 'redirects';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialNewSection::class, 'SpecialNewSection' );
