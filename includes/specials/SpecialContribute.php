<?php

use MediaWiki\Html\TemplateParser;
use MediaWiki\Specials\Contribute\ContributeFactory;

/**
 * Special:Contribute, show user contribute options in the 1st tab
 *  and a list of contribution on the 2nd tab.
 *
 * @ingroup SpecialPage
 */
class SpecialContribute extends IncludableSpecialPage {

	/**
	 * SpecialContribute constructor.
	 */
	public function __construct() {
		parent::__construct( 'Contribute' );
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
		$contributeFactory = new ContributeFactory(
			$this->getContext(),
			$this->getHookRunner()
		);
		$cards = $contributeFactory->getCards();
		$user = $this->getContext()->getUser();

		$templateParser = new TemplateParser( __DIR__ . '/Contribute/Templates' );
		$templateData = [
			'cards' => $cards,
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
	 * @inheritDoc
	 */
	public function getAssociatedNavigationLinks(): array {
		if ( $this->isShowable() ) {
			$user = $this->getUser();
			return ContributeFactory::getAssociatedNavigationLinks(
				$user,
				$user
			);
		}
		return [];
	}

	/**
	 * Check if skin is allowed to access the Special:Contribute page
	 * and the page have enough cards to be enabled
	 *
	 * @return bool
	 */
	public function isShowable(): bool {
		return ContributeFactory::isEnabledOnCurrentSkin(
			$this->getSkin(),
			$this->getConfig()->get( 'SpecialContributeSkinsEnabled' )
		);
	}
}
