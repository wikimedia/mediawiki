<?php

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
	public function getAssociatedNavigationLinks(): array {
		$userName = $this->getUser()->getName();
		return [
			static::getTitleFor( 'Contribute', $userName )->getFullText(),
			static::getTitleFor( 'Contributions', $userName )->getFullText(),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();
		$target = $par ?? $request->getVal( 'target', '' );

		$titleLocalUrl = static::getTitleFor( 'Contribute', $this->getUser()->getName() )->getLocalUrl();

		if ( $target !== $this->getUser()->getName() ) {
			$this->getOutput()->redirect( $titleLocalUrl );
		}

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'contribute-title', $target )->escaped() );
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
		$context = $this->getContext();
		$user = $context->getUser();
		$cards = ( new ContributeFactory( $context ) )->getCards();

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
