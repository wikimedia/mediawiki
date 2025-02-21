<?php

namespace MediaWiki\Language;

use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Status\StatusFormatter;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\UserIdentityUtils;
use MessageLocalizer;
use Psr\Log\LoggerInterface;

/**
 * Factory for formatters of common complex objects
 *
 * @since 1.42
 */
class FormatterFactory {

	private MessageParser $messageParser;
	private TitleFormatter $titleFormatter;
	private HookContainer $hookContainer;
	private UserIdentityUtils $userIdentityUtils;
	private LanguageFactory $languageFactory;
	private LoggerInterface $logger;

	public function __construct(
		MessageParser $messageParser,
		TitleFormatter $titleFormatter,
		HookContainer $hookContainer,
		UserIdentityUtils $userIdentityUtils,
		LanguageFactory $languageFactory,
		LoggerInterface $logger
	) {
		$this->messageParser = $messageParser;
		$this->titleFormatter = $titleFormatter;
		$this->hookContainer = $hookContainer;
		$this->userIdentityUtils = $userIdentityUtils;
		$this->languageFactory = $languageFactory;
		$this->logger = $logger;
	}

	public function getStatusFormatter( MessageLocalizer $messageLocalizer ): StatusFormatter {
		return new StatusFormatter( $messageLocalizer, $this->messageParser, $this->logger );
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
