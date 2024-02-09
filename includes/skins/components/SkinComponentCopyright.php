<?php

namespace MediaWiki\Skin;

use HtmlArmor;
use MediaWiki\Config\Config;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MessageLocalizer;

class SkinComponentCopyright implements SkinComponent {
	/** @var Config */
	private $config;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var SkinComponentRegistryContext */
	private $skinContext;
	/** @var User */
	private $user;

	/**
	 * @param SkinComponentRegistryContext $skinContext
	 */
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

	/**
	 * Get the copyright.
	 *
	 * @return string
	 */
	public function getCopyrightHTML(): string {
		$out = $this->skinContext->getOutput();
		$title = $out->getTitle();
		$isRevisionCurrent = $out->isRevisionCurrent();
		$config = $this->config;
		$localizer = $this->localizer;
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		if ( !$isRevisionCurrent
			&& !$localizer->msg( 'history_copyright' )->inContentLanguage()->isDisabled()
		) {
			$type = 'history';
		} else {
			$type = 'normal';
		}

		if ( $type == 'history' ) {
			$msg = 'history_copyright';
		} else {
			$msg = 'copyright';
		}

		if ( $config->get( MainConfigNames::RightsPage ) ) {
			$title = Title::newFromText( $config->get( MainConfigNames::RightsPage ) );
			$link = $linkRenderer->makeKnownLink( $title,
					new HtmlArmor( $config->get( MainConfigNames::RightsText ) ?: $title->getText() )
				);
		} elseif ( $config->get( MainConfigNames::RightsUrl ) ) {
			$link = $linkRenderer->makeExternalLink(
				$config->get( MainConfigNames::RightsUrl ),
				$config->get( MainConfigNames::RightsText ),
				$title
			);
		} elseif ( $config->get( MainConfigNames::RightsText ) ) {
			$link = $config->get( MainConfigNames::RightsText );
		} else {
			# Give up now
			return '';
		}

		// Allow for site and per-namespace customization of copyright notice.
		$this->skinContext->runHook( 'onSkinCopyrightFooter', [ $title, $type, &$msg, &$link ] );
		return $localizer->msg( $msg )->rawParams( $link )->text();
	}
}
