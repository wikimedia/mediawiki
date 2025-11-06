<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\SpecialPage\RedirectSpecialPage;
use MediaWiki\Title\Title;

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
		$this->addHelpLink( 'Help:PermanentLink' );
		$this->setHeaders();
		$this->outputHeader();
		$this->showForm();
	}

	private function showForm() {
		HTMLForm::factory( 'ooui', [
			'revid' => [
				'type' => 'int',
				'name' => 'revid',
				'label-message' => 'permanentlink-revid',
			],
		], $this->getContext(), 'permanentlink' )
			->setSubmitTextMsg( 'permanentlink-submit' )
			->setSubmitCallback( $this->onFormSubmit( ... ) )
			->show();
	}

	/**
	 * @param array $formData
	 */
	private function onFormSubmit( $formData ) {
		$revid = $formData['revid'];
		$title = $this->getPageTitle( $revid ?: null );
		$url = $title->getFullUrlForRedirect();
		$this->getOutput()->redirect( $url );
	}

	/** @inheritDoc */
	public function isListed() {
		return true;
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
class_alias( SpecialPermanentLink::class, 'SpecialPermanentLink' );
