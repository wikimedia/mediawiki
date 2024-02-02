<?php

namespace MediaWiki\Language;

use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Status\StatusFormatter;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\UserIdentityUtils;
use MessageCache;
use MessageLocalizer;

/**
 * Factory for formatters of common complex objects
 *
 * @since 1.42
 */
class FormatterFactory {

	private MessageCache $messageCache;
	private TitleFormatter $titleFormatter;
	private HookContainer $hookContainer;
	private UserIdentityUtils $userIdentityUtils;
	private LanguageFactory $languageFactory;

	public function __construct(
		MessageCache $messageCache,
		TitleFormatter $titleFormatter,
		HookContainer $hookContainer,
		UserIdentityUtils $userIdentityUtils,
		LanguageFactory $languageFactory
	) {
		$this->messageCache = $messageCache;
		$this->titleFormatter = $titleFormatter;
		$this->hookContainer = $hookContainer;
		$this->userIdentityUtils = $userIdentityUtils;
		$this->languageFactory = $languageFactory;
	}

	public function getStatusFormatter( MessageLocalizer $messageLocalizer ): StatusFormatter {
		return new StatusFormatter( $messageLocalizer, $this->messageCache );
	}

	public function getBlockErrorFormatter( LocalizationContext $context ): BlockErrorFormatter {
		return new BlockErrorFormatter(
			$this->titleFormatter,
			$this->hookContainer,
			$this->userIdentityUtils,
			$this->languageFactory,
			$context
		);
	}

}
