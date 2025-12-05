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
	 * If presented, convert the URL to absolute
	 *
	 * @param string|null $url URL to expand (or null if none)
	 * @return array|null Null if $url is null, otherwise array with information about the logo:
	 * ('src' has expanded $url and 'alt' has a textual description suitable for site logo)
	 */
	private function buildContextForLogoByUrlIfExists( ?string $url, array $extraConfig = [] ): ?array {
		if ( !$url ) {
			return null;
		}

		return [
			'src' => $this->urlUtils->expand( $url, PROTO_CANONICAL ),
			'alt' => $this->context->msg( 'confirmemail_html_logo_alttext' )->text(),
		] + $extraConfig;
	}

	/**
	 * Build config context that can be passed into Logos.mustache
	 *
	 * @return array|null Null if no logos are configured
	 */
	private function buildLogosContext(): ?array {
		$config = SkinModule::getAvailableLogos(
			$this->context->getConfig(),
			$this->context->getLanguage()->getCode()
		);

		$usesMultipartLogo = isset( $config['icon'] );
		$result = [
			'wordmark' => $this->buildContextForLogoByUrlIfExists(
				$config['wordmark']['src'] ?? null
			),
			'tagline' => $this->buildContextForLogoByUrlIfExists(
				$config['tagline']['src'] ?? null
			),
			'icon' => $this->buildContextForLogoByUrlIfExists(
				$config['icon'] ?? $config['1x'] ?? null,
				[ 'multipart-logo' => $usesMultipartLogo ],
			),
		];
		if ( $result['icon'] === null ) {
			// No logo configured at all
			return null;
		}
		return $result;
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
				'logo' => $this->buildLogosContext(),
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
