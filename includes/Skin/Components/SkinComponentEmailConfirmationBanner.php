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

		$this->context->getOutput()->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
		$notice = $this->context->msg( 'confirmemail-notice' )->parse();

		return [
			'html' => Html::warningBox( $notice, 'mw-emailconfirmbanner' )
		];
	}
}
