<?php

namespace MediaWiki\Specials;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Html\TemplateParser;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\IncludableSpecialPage;
use MediaWiki\Specials\Contribute\ContributeFactory;

/**
 * Promote ways for editors to contribute.
 *
 * The cards are produced by MediaWiki\Specials\Contribute\ContributeFactory,
 * which defaults to a single card promoting Special:Wantedpages, and is
 * extended by extensions to add additional cards.
 *
 * To enable a link to this special page in the skin, which will replace the
 * link to "Contributions" in the p-personal portlet menu, add the skin name
 * to $wgSpecialContributeSkinsEnabled.
 *
 * @ingroup SpecialPage
 */
class SpecialContribute extends IncludableSpecialPage {

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
		$out->setPageTitleMsg( $this->msg( 'contribute-title', $this->getUser()->getName() ) );
		$out->addModuleStyles( [
			'mediawiki.special'
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
			$this->getHookRunner(),
			new ServiceOptions(
				ContributeFactory::CONSTRUCTOR_OPTIONS,
				$this->getConfig()
			)
		);
		$cards = $contributeFactory->getCards();
		$user = $this->getContext()->getUser();

		$templateParser = new TemplateParser( __DIR__ . '/Contribute/Templates' );
		$templateData = [
			'cards' => $cards,
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
	 */
	public function isShowable(): bool {
		return ContributeFactory::isEnabledOnCurrentSkin(
			$this->getSkin(),
			$this->getConfig()->get( MainConfigNames::SpecialContributeSkinsEnabled )
		);
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialContribute::class, 'SpecialContribute' );
