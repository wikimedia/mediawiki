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
	 * @var SpecialContribute
	 */
	private static $instance = null;

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
		if ( self::$instance instanceof SpecialContribute ) {
			$this->cards = static::$instance->cards;
		} else {
			$this->cards = ( new ContributeFactory( $this->getContext() ) )->getCards();
		}
		static::$instance = $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getAssociatedNavigationLinks(): array {
		if ( !$this->canShowCardsAndSkinEnabled() ) {
			return [];
		}
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
		$lowerPath = strtolower( explode( '/', $path )[0] );
		$shortKey = 'special-tab-' . $lowerPath;
		$shortKey .= '-short';
		$msgShort = $this->msg( $shortKey );
		return $msgShort->text();
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
	 * Check if the Special:Contribute page is enabled for the current skin
	 *
	 * @return bool
	 */
	private function isEnabledOnCurrentSkin() {
		return !in_array(
			$this->getSkin()->getSkinName(),
			$this->getConfig()->get( 'SpecialContributeSkinsDisabled' )
		);
	}

	/**
	 * Check if skin is allowed to access the Special:Contribute page
	 * and the page have enough cards to be enabled
	 *
	 * @return bool
	 */
	private function canShowCardsAndSkinEnabled() {
		return $this->hasCards() && $this->isEnabledOnCurrentSkin();
	}

	/**
	 * Check if the skin can show Special:Contribute page.
	 *
	 * @return bool
	 */
	public static function isShowable(): bool {
		return static::getInstance()->canShowCardsAndSkinEnabled();
	}

	/**
	 * Instance Creater maintains a single instance of Special:Contribute object
	 *
	 * @return SpecialContribute
	 */
	public static function getInstance() {
		if ( static::$instance === null ) {
			static::$instance = new SpecialContribute();
		}
		return static::$instance;
	}

}
