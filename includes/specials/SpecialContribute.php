<?php

use MediaWiki\Specials\Contribute\ContributeFactory;
use MediaWiki\User\UserIdentity;

/**
 * Special:Contribute, show user contribute options in the 1st tab
 *  and a list of contribution on the 2nd tab.
 *
 * @ingroup SpecialPage
 */
class SpecialContribute extends IncludableSpecialPage {

	/**
	 * @var array List of MediaWiki\Specials\Contribute\Card\ContributeCard
	 * to show on the Special:Contribute page
	 */
	private array $cards = [];

	/**
	 * SpecialContribute constructor.
	 */
	public function __construct() {
		parent::__construct( 'Contribute' );
		$this->cards = ( new ContributeFactory( $this->getContext() ) )->getCards();
	}

	/**
	 * @inheritDoc
	 */
	public function getAssociatedNavigationLinks(): array {
		return $this->getAssociatedNavigationLinksForUser( $this->getUser() );
	}

	/**
	 * @param UserIdentity|null $targetUser
	 * @return array
	 */
	public function getAssociatedNavigationLinksForUser( $targetUser ): array {
		if ( !$this->isShowable() ) {
			return [];
		}
		$viewer = $this->getUser();
		// Don't show the links to Special:Contribute when viewing other users' contributions.
		if ( $targetUser === null || !$viewer->equals( $targetUser ) ) {
			return [];
		}
		return [
			$this->getPageTitle()->getFullText(),
			SpecialPage::getTitleFor( 'Contributions', $targetUser->getName() )->getFullText(),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'contribute-title', $this->getUser()->getName() )->escaped() );
		$out->addModuleStyles( [
			'mediawiki.special',
			'oojs-ui.styles.icons-content'
		] );
		$out->addHTML( $this->getContributePage() );
	}

	/**
	 * Get the contribute page HTML, check ContributeFactory it is used to
	 * get the contribute cards and render them using the mustache template.
	 *
	 * @return string
	 */
	private function getContributePage() {
		$user = $this->getContext()->getUser();

		$templateParser = new TemplateParser( __DIR__ . '/Contribute/Templates' );
		$templateData = [
			'cards' => $this->cards,
			'userName' => $user->getName(),
			'userPage' => $user->getUserPage(),
			'contribute' => $this->msg( 'contribute' )->text(),
			'viewContributions' => $this->msg( 'viewcontribs' )->text(),
		];
		$outputHTML = $templateParser->processTemplate(
			'SpecialContribute',
			$templateData
		);

		return $outputHTML;
	}

	/**
	 * @inheritDoc
	 */
	public function getShortDescription( string $path = '' ): string {
		return $this->msg( 'special-tab-contribute-short' )->text();
	}

	/**
	 * Check if any cards are available to show on the Special:Contribute page
	 *
	 * @return bool
	 */
	private function hasCards() {
		return count( $this->cards ) > 0;
	}

	/**
	 * Check if it is allowed to access the Special:Contribute page
	 *
	 * @return bool
	 */
	public function isShowable() {
		$specialContributeSkinsDisabled = $this->getConfig()->get( 'SpecialContributeSkinsDisabled' );
		return $this->hasCards() &&
			!in_array( $this->getSkin()->getSkinName(), $specialContributeSkinsDisabled );
	}

}
