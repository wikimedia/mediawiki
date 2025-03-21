<?php

namespace MediaWiki\Skin;

use MediaWiki\Config\Config;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MessageLocalizer;
use Wikimedia\HtmlArmor\HtmlArmor;

class SkinComponentCopyright implements SkinComponent {
	use ProtectedHookAccessorTrait;

	/** @var Config */
	private $config;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var SkinComponentRegistryContext */
	private $skinContext;
	/** @var User */
	private $user;

	public function __construct( SkinComponentRegistryContext $skinContext ) {
		$this->skinContext = $skinContext;
		$this->config = $skinContext->getConfig();
		$this->localizer = $skinContext->getMessageLocalizer();
		$this->user = $skinContext->getUser();
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return [
			'html' => $this->getCopyrightHTML(),
		];
	}

	public function getCopyrightHTML(): string {
		$out = $this->skinContext->getOutput();
		$title = $out->getTitle();
		$isRevisionCurrent = $out->isRevisionCurrent();
		$config = $this->config;
		$localizer = $this->localizer;
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

		if ( $config->get( MainConfigNames::RightsPage ) ) {
			$title = Title::newFromText( $config->get( MainConfigNames::RightsPage ) );
			$link = $linkRenderer->makeKnownLink( $title,
					new HtmlArmor( $config->get( MainConfigNames::RightsText ) ?: $title->getText() )
				);
		} elseif ( $config->get( MainConfigNames::RightsUrl ) ) {
			$link = $linkRenderer->makeExternalLink(
				$config->get( MainConfigNames::RightsUrl ),
				$config->get( MainConfigNames::RightsText ),
				$title ?? SpecialPage::getTitleFor( 'Badtitle' )
			);
		} elseif ( $config->get( MainConfigNames::RightsText ) ) {
			$link = $config->get( MainConfigNames::RightsText );
		} else {
			# Give up now
			return '';
		}

		if ( $config->get( MainConfigNames::AllowRawHtmlCopyrightMessages ) ) {
			// First check whether the old, raw HTML messages exist (if not disallowed by wiki config),
			// for compatibility with on-wiki message overrides.

			if ( !$isRevisionCurrent && !$localizer->msg( 'history_copyright' )->isDisabled() ) {
				$type = 'history';
			} else {
				$type = 'normal';
			}

			$msgKey = $type === 'history' ? 'history_copyright' : 'copyright';

			// Allow for site and per-namespace customization of copyright notice.
			$this->getHookRunner()->onSkinCopyrightFooter( $title, $type, $msgKey, $link );

			$msg = $localizer->msg( $msgKey )->rawParams( $link );
			if ( !$msg->isDisabled() ) {
				return $msg->text();
			}
		}

		// TODO: The hook should probably be called with $type === 'history' even if this message
		// is disabled, to allow customization, but then we'd probably have to call it again with
		// $type === 'normal' if it turns out it's not customized?
		if ( !$isRevisionCurrent && !$localizer->msg( 'copyright-footer-history' )->isDisabled() ) {
			$type = 'history';
		} else {
			$type = 'normal';
		}

		// If it does not exist or disabled, use the new, safer wikitext message.
		$msgKey = $type === 'history' ? 'copyright-footer-history' : 'copyright-footer';
		$msgSpec = Message::newFromSpecifier( $msgKey )->rawParams( $link );

		// Allow for site and per-namespace customization of copyright notice.
		$this->getHookRunner()->onSkinCopyrightFooterMessage( $title, $type, $msgSpec );

		$msg = $localizer->msg( $msgSpec );
		if ( !$msg->isDisabled() ) {
			return $msg->parse();
		}

		return '';
	}
}
