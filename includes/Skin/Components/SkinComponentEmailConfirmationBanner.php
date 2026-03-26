<?php

namespace MediaWiki\Skin\Components;

use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Mail\ConfirmEmail\EmailConfirmationBannerHandler;

/**
 * @internal
 */
class SkinComponentEmailConfirmationBanner implements SkinComponent {

	private EmailConfirmationBannerHandler $handler;
	private IContextSource $context;

	public function __construct(
		EmailConfirmationBannerHandler $handler,
		IContextSource $context
	) {
		$this->handler = $handler;
		$this->context = $context;
	}

	/** @inheritDoc */
	public function getTemplateData(): array {
		if ( !$this->handler->shouldShowBanner( $this->context->getUser(), $this->context->getTitle() ) ) {
			return [ 'html' => '' ];
		}

		$out = $this->context->getOutput();
		$out->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
		$out->addModules( 'mediawiki.emailConfirmationBanner.abTest' );

		$armA = Html::rawElement(
			'div',
			[ 'class' => 'mw-emailconfirmbanner-container', 'data-arm' => 'arm_a', 'style' => 'display:none' ],
			Html::warningBox( $this->context->msg( 'confirmemail-notice' )->parse(), 'mw-emailconfirmbanner' )
		);
		$armB = Html::rawElement(
			'div',
			[ 'class' => 'mw-emailconfirmbanner-container', 'data-arm' => 'arm_b', 'style' => 'display:none' ],
			Html::warningBox( $this->context->msg( 'confirmemail-notice-arm-b' )->parse(), 'mw-emailconfirmbanner' )
		);

		return [ 'html' => $armA . $armB ];
	}
}
