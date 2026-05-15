<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Content;
use MediaWiki\Context\IContextSource;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Constraints reflect possible errors that need to be checked
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class EditConstraintFactory {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		// PageSizeConstraint
		MainConfigNames::MaxArticleSize,
	];

	/**
	 * Some constraints have dependencies that need to be injected,
	 * this class serves as a factory for all of the different constraints
	 * that need dependencies injected.
	 */
	public function __construct(
		// Multiple
		private readonly ServiceOptions $options,
		// EditFilterMergedContentHookConstraint
		private readonly HookContainer $hookContainer,
		// ReadOnlyConstraint
		private readonly ReadOnlyMode $readOnlyMode,
		// SpamRegexConstraint
		private readonly SpamChecker $spamRegexChecker,
		private readonly LoggerInterface $spamRegexLogger,
		// LinkPurgeRateLimitConstraint
		private readonly RateLimiter $rateLimiter,
		// RedirectConstraint
		private readonly RedirectLookup $redirectLookup,
		// AccidentalRecreationConstraint
		private readonly IConnectionProvider $connectionProvider,
		private readonly LogFormatterFactory $logFormatterFactory,
		// EditFilterMergedContentHookConstraint
		private readonly UserFactory $userFactory,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	public function newEditFilterMergedContentHookConstraint(
		Content $content,
		IContextSource $context,
		string $summary,
		bool $minorEdit,
		Language $language,
		UserIdentity $user
	): EditFilterMergedContentHookConstraint {
		return new EditFilterMergedContentHookConstraint(
			$this->hookContainer,
			$this->userFactory,
			$content,
			$context,
			$summary,
			$minorEdit,
			$language,
			$user
		);
	}

	/**
	 * @param int $contentSize
	 * @param string $type
	 * @return PageSizeConstraint
	 */
	public function newPageSizeConstraint(
		int $contentSize,
		string $type
	): PageSizeConstraint {
		return new PageSizeConstraint(
			$this->options->get( MainConfigNames::MaxArticleSize ),
			$contentSize,
			$type
		);
	}

	public function newReadOnlyConstraint(): ReadOnlyConstraint {
		return new ReadOnlyConstraint(
			$this->readOnlyMode
		);
	}

	/**
	 * @param RateLimitSubject $subject
	 *
	 * @return LinkPurgeRateLimitConstraint
	 */
	public function newLinkPurgeRateLimitConstraint(
		RateLimitSubject $subject
	): LinkPurgeRateLimitConstraint {
		return new LinkPurgeRateLimitConstraint(
			$this->rateLimiter,
			$subject
		);
	}

	public function newSpamRegexConstraint(
		string $summary,
		?string $sectionHeading,
		string $text,
		string $reqIP,
		PageReference $page,
	): SpamRegexConstraint {
		return new SpamRegexConstraint(
			$this->spamRegexLogger,
			$this->spamRegexChecker,
			$summary,
			$sectionHeading,
			$text,
			$reqIP,
			$page,
		);
	}

	public function newRedirectConstraint(
		?LinkTarget $allowedProblematicRedirectTarget,
		Content $newContent,
		?Content $originalContent,
		PageReference $page,
		MessageSpecifier $errorMessageWrapper,
		?string $contentFormat,
	): RedirectConstraint {
		return new RedirectConstraint(
			$allowedProblematicRedirectTarget,
			$newContent,
			$originalContent,
			$page,
			$errorMessageWrapper,
			$contentFormat,
			$this->redirectLookup
		);
	}

	public function newAccidentalRecreationConstraint(
		Title $title,
		bool $allowRecreation,
		?string $startTime,
		?MessageSpecifier $submitButtonLabel = null,
	): AccidentalRecreationConstraint {
		return new AccidentalRecreationConstraint(
			$this->connectionProvider,
			$this->logFormatterFactory,
			$title,
			$allowRecreation,
			$startTime,
			$submitButtonLabel,
		);
	}

}
