<?php
/**
 * @license GPL-2.0-or-later
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

	/** @inheritDoc */
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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'redirects';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialNewSection::class, 'SpecialNewSection' );
