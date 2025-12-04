<?php

namespace MediaWiki\Mail\ConfirmEmail;

use MediaWiki\Context\IContextSource;
use MediaWiki\Html\TemplateParser;
use MediaWiki\ResourceLoader\SkinModule;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ObjectCache\BagOStuff;

class HTMLConfirmEmailBuilder implements IConfirmEmailBuilder {

	private readonly TemplateParser $templateParser;

	public function __construct(
		private readonly IContextSource $context,
		BagOStuff $cache,
		private readonly UrlUtils $urlUtils
	) {
		$this->templateParser = new TemplateParser(
			dirname( __DIR__, 3 ) . '/resources/templates/ConfirmEmail', $cache
		);
	}

	/**
	 * Build config context for the logo used in EmailCreated.mustache
	 *
	 * @return array|null Null if no logo is configured
	 */
	private function buildLogoContext(): ?array {
		$config = SkinModule::getAvailableLogos(
			$this->context->getConfig(),
			$this->context->getLanguage()->getCode()
		);
		if ( !isset( $config['1x'] ) ) {
			return null;
		}

		return [
			'icon' => [
				'src' => $this->urlUtils->expand( $config['1x'], PROTO_CANONICAL ),
				'alt' => $this->context->msg( 'confirmemail_html_logo_alttext' )->text(),
			],
		];
	}

	public function buildEmailCreated( ConfirmEmailData $data ): ConfirmEmailContent {
		return new ConfirmEmailContent(
			$this->context->msg( 'confirmemail_html_subject' )->text(),
			implode( PHP_EOL . PHP_EOL, [
				$this->context->msg(
					'confirmemail_html_par1',
					$data->getRecipientUser()->getName(),
				)->text(),
				$this->context->msg(
					'confirmemail_html_par2',
				)->text(),
				$this->context->msg(
					'confirmemail_plaintext_button_label',
					$data->getRecipientUser()->getName(),
					$data->getConfirmationUrl(),
				)->text(),
				$this->context->msg(
					'confirmemail_plaintext_footer',
					$data->getRecipientUser()->getName(),
					$data->getInvalidationUrl(),
				)->text(),
			] ),
			$this->templateParser->processTemplate( 'EmailCreated', [
				'logo' => $this->buildLogoContext(),
				'confirmationUrl' => $data->getConfirmationUrl(),
				'par1' => $this->context->msg(
					'confirmemail_html_par1',
					$data->getRecipientUser()->getName(),
				)->parse(),
				'par2' => $this->context->msg(
					'confirmemail_html_par2',
				)->parse(),
				'buttonLabel' => $this->context->msg(
					'confirmemail_html_button_label',
					$data->getRecipientUser()->getName(),
				)->text(),
				'footer' => $this->context->msg(
					'confirmemail_html_footer',
					$data->getRecipientUser()->getName(),
					$data->getInvalidationUrl(),
				)->parse(),
				'username' => $data->getRecipientUser()->getName(),
			] )
		);
	}

	public function buildEmailChanged( ConfirmEmailData $data ): ConfirmEmailContent {
		return $this->buildEmailCreated( $data );
	}

	public function buildEmailSet( ConfirmEmailData $data ): ConfirmEmailContent {
		return $this->buildEmailCreated( $data );
	}
}
