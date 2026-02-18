<?php

namespace MediaWiki\Mail\NotificationEmail;

use MediaWiki\Context\IContextSource;
use MediaWiki\Html\TemplateParser;
use MediaWiki\ResourceLoader\SkinModule;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * Builds HTML notification emails when a user changes or removes their email.
 * Sent to the old address. Used when $wgAllowHTMLEmail is true.
 *
 * Uses generic "contact a site administrator" message in core.
 * WikimediaMessages extension overrides with ca@wikimedia.org for WMF wikis.
 */
class HTMLNotificationEmailBuilder {

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
	 * Build config context for the logo used in email templates.
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

	/**
	 * Build the notification email when the user changed their email.
	 *
	 * @param string $username The username
	 * @param string $newEmail The new email address
	 * @return array{subject: string, body: array{text: string, html: string}}
	 */
	public function buildNotificationEmailChanged( string $username, string $newEmail ): array {
		$subject = $this->context->msg( 'notificationemail_subject_changed' )->text();
		$bodyText = $this->context->msg(
			'notificationemail_body_changed',
			$this->context->getRequest()->getIP(),
			$username,
			$newEmail
		)->text();

		$par1 = $this->context->msg( 'notificationemail_html_par1', $username )->parse();
		$par2 = $this->context->msg( 'notificationemail_html_par2_changed', $newEmail )->parse()
			. '<br><br>'
			. $this->context->msg( 'notificationemail_html_footer' )->parse();

		$bodyHtml = $this->templateParser->processTemplate( 'EmailCreated', [
			'logo' => $this->buildLogoContext(),
			'par1' => $par1,
			'par2' => $par2,
		] );

		return [
			'subject' => $subject,
			'body' => [
				'text' => $bodyText,
				'html' => $bodyHtml,
			],
		];
	}

	/**
	 * Build the notification email when the user removed their email.
	 *
	 * @param string $username The username
	 * @return array{subject: string, body: array{text: string, html: string}}
	 */
	public function buildNotificationEmailRemoved( string $username ): array {
		$subject = $this->context->msg( 'notificationemail_subject_removed' )->text();
		$bodyText = $this->context->msg(
			'notificationemail_body_removed',
			$this->context->getRequest()->getIP(),
			$username
		)->text();

		$par1 = $this->context->msg( 'notificationemail_html_par1', $username )->parse();
		$par2 = $this->context->msg( 'notificationemail_html_par2_removed' )->parse()
			. '<br><br>'
			. $this->context->msg( 'notificationemail_html_footer' )->parse();

		$bodyHtml = $this->templateParser->processTemplate( 'EmailCreated', [
			'logo' => $this->buildLogoContext(),
			'par1' => $par1,
			'par2' => $par2,
		] );

		return [
			'subject' => $subject,
			'body' => [
				'text' => $bodyText,
				'html' => $bodyHtml,
			],
		];
	}
}
