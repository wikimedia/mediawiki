<?php

namespace MediaWiki\Logging;

use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserEditTracker;
use stdClass;
use Wikimedia\ObjectFactory\ObjectFactory;

class LogFormatterFactory {

	public const SERVICE_OPTIONS = [
		MainConfigNames::LogActionsHandlers,
	];

	private ServiceOptions $serviceOptions;
	private ObjectFactory $objectFactory;
	private HookContainer $hookContainer;
	private LinkRenderer $linkRenderer;
	private Language $contentLanguage;
	private CommentFormatter $commentFormatter;
	private UserEditTracker $userEditTracker;

	public function __construct(
		ServiceOptions $options,
		ObjectFactory $objectFactory,
		HookContainer $hookContainer,
		LinkRenderer $linkRenderer,
		Language $contentLanguage,
		CommentFormatter $commentFormatter,
		UserEditTracker $userEditTracker
	) {
		$options->assertRequiredOptions( self::SERVICE_OPTIONS );
		$this->serviceOptions = $options;
		$this->objectFactory = $objectFactory;
		$this->hookContainer = $hookContainer;
		$this->linkRenderer = $linkRenderer;
		$this->contentLanguage = $contentLanguage;
		$this->commentFormatter = $commentFormatter;
		$this->userEditTracker = $userEditTracker;
	}

	public function newFromEntry( LogEntry $entry ): LogFormatter {
		$logActionsHandlers = $this->serviceOptions->get( MainConfigNames::LogActionsHandlers );
		$fulltype = $entry->getFullType();
		$wildcard = $entry->getType() . '/*';
		$handler = $logActionsHandlers[$fulltype] ?? $logActionsHandlers[$wildcard] ?? '';

		if ( $handler !== '' ) {
			$formatter = $this->objectFactory->createObject( $handler, [
				'extraArgs' => [ $entry ],
				'allowClassName' => true,
				'assertClass' => LogFormatter::class,
			] );
		} else {
			$formatter = new LegacyLogFormatter( $entry, $this->hookContainer );
		}

		$formatter->setLinkRenderer( $this->linkRenderer );
		$formatter->setContentLanguage( $this->contentLanguage );
		$formatter->setCommentFormatter( $this->commentFormatter );
		$formatter->setUserEditTracker( $this->userEditTracker );
		return $formatter;
	}

	/**
	 * @param stdClass|array $row
	 * @return LogFormatter
	 * @see DatabaseLogEntry::getSelectQueryData
	 */
	public function newFromRow( $row ): LogFormatter {
		return $this->newFromEntry( DatabaseLogEntry::newFromRow( $row ) );
	}

}

/** @deprecated class alias since 1.44 */
class_alias( LogFormatterFactory::class, 'LogFormatterFactory' );
