<?php

use MediaWiki\Specials\Contribute\ContributeFactory;

/**
 * Special:Contribute, show user contribute options in the 1st tab
 *  and a list of contrubution on the 2nd tab.
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
	public function getAssociatedNavigationLinks(): array {
		return $this->getUser() ? [
			'Special:Contribute/' . $this->getUser()->getName(),
			'Special:Contributions/' . $this->getUser()->getName(),
			] : [];
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();
		$target = $par ?? $request->getVal( 'target', '' );

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'contribute-title', $target )->escaped() );
		$out->addModuleStyles( [ 'mediawiki.special', 'mediawiki.special.contribute' ] );
		$out->addHTML( $this->getContributePage() );
	}

	/**
	 * Get the contribute page HTML, check ContributeFactory it is used to
	 * get the contribute cards and render them using the mustache template.
	 *
	 * @return string
	 */
	private function getContributePage() {
		$context = $this->getContext();
		$user = $context->getUser();
		$cards = ( new ContributeFactory() )->getCards();

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
		$lowerPath = strtolower( explode( '/', $path )[0] );
		$shortKey = 'special-tab-' . $lowerPath;
		$shortKey .= '-short';
		$msgShort = $this->msg( $shortKey );
		return $msgShort->text();
	}
}
