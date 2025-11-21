<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Message\MessageFormatterFactory;
use MediaWiki\Rest\Module\ModuleManager;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * A special page showing a Swagger UI for exploring REST APIs.
 *
 * @ingroup SpecialPage
 * @since 1.43
 */
class SpecialRestSandbox extends SpecialPage {

	private UrlUtils $urlUtils;
	private ModuleManager $moduleManager;

	public function __construct(
		UrlUtils $urlUtils, MessageFormatterFactory $messageFormatterFactory, BagOStuff $srvCache
	) {
		parent::__construct( 'RestSandbox' );

		$this->urlUtils = $urlUtils;

		$textFormatter = $messageFormatterFactory->getTextFormatter(
			$this->getContentLanguage()->getCode()
		);
		$responseFactory = new ResponseFactory( [ $textFormatter ] );

		$this->moduleManager = new ModuleManager(
			new ServiceOptions( ModuleManager::CONSTRUCTOR_OPTIONS, $this->getConfig() ),
			$srvCache,
			$responseFactory
		);
	}

	/** @inheritDoc */
	public function isListed() {
		// Hide the special pages if there are no APIs to explore.
		return $this->moduleManager->hasApiSpecs();
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'wiki';
	}

	private function getSpecUrl( array $apiSpecs, string $apiId ): ?string {
		if ( $apiId !== '' ) {
			$spec = $apiSpecs[$apiId] ?? null;
		} else {
			$spec = reset( $apiSpecs ) ?: null;
		}

		if ( !$spec ) {
			return null;
		}

		return $this->urlUtils->expand( $spec['url'] );
	}

	/** @inheritDoc */
	public function execute( $subPage ) {
		$this->setHeaders();
		$out = $this->getOutput();
		$this->addHelpLink( 'Help:RestSandbox' );

		$apiId = $this->getRequest()->getRawVal( 'api' ) ?? $subPage ?? '';
		$apiSpecs = $this->moduleManager->getApiSpecs();
		$specUrl = $this->getSpecUrl( $apiSpecs, $apiId );

		$out->addJsConfigVars( [
			'specUrl' => $specUrl
		] );

		$out->addModuleStyles( [
			'mediawiki.special',
			'mediawiki.hlist',
			'mediawiki.special.restsandbox.styles'
		] );

		if ( !$apiSpecs ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'restsandbox-no-specs-configured' )->parse()
			) );
			return;
		}

		if ( $out->getLanguage()->getCode() !== 'en' ) {
			$out->addHTML( Html::noticeBox( $out->msg( 'restsandbox-disclaimer' )->parse() ) );
		}

		$this->showForm( $apiSpecs, $apiId );

		if ( !$specUrl ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'restsandbox-no-such-api', $apiId )->parse()
			) );
			return;
		}

		$out->addModules( [
			'mediawiki.codex.messagebox.styles',
			'mediawiki.special.restsandbox'
		] );

		$out->addHTML( Html::openElement( 'div', [ 'id' => 'mw-restsandbox' ] ) );

		// Hidden when JS is available
		$out->addHTML( Html::errorBox(
			$out->msg( 'restsandbox-jsonly' )->parse(),
			'',
			'mw-restsandbox-client-nojs'
		) );

		// To be replaced by Swagger UI.
		$out->addElement( 'div', [
			'id' => 'mw-restsandbox-swagger-ui',
			// Force direction to "LTR" with swagger-ui.
			// Since the swagger content is not internationalized, the information is always in English.
			// We have to force the direction to "LTR" to avoid the content (specifically json strings)
			// from being mangled.
			'dir' => 'ltr',
			'lang' => 'en',
			// For dark mode compatibility
			'class' => 'skin-invert'
		] );

		$out->addHTML( Html::closeElement( 'div' ) ); // #mw-restsandbox
	}

	private function showForm( array $apiSpecs, string $apiId ) {
		$apis = [];

		foreach ( $apiSpecs as $key => $spec ) {
			$apis[$spec['name']] = $key;
		}

		$formDescriptor = [
			'intro' => [
				'type' => 'info',
				'raw' => true,
				'default' => $this->msg( 'restsandbox-text' )->parseAsBlock()
			],
			'api' => [
				'type' => 'select',
				'name' => 'api',
				'label-message' => 'restsandbox-select-api',
				'options' => $apis,
				'default' => $apiId
			],
			'title' => [
				'type' => 'hidden',
				'name' => 'title',
				'default' => $this->getPageTitle()->getPrefixedDBkey()
			],
		];

		$action = $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] );

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm->setAction( $action );
		$htmlForm->setMethod( 'GET' );
		$htmlForm->setId( 'mw-restsandbox-form' );
		$htmlForm->prepareForm()->displayForm( false );
	}
}
