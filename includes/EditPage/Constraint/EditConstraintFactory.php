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
use MediaWiki\Logger\Spi;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
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
	 *
	 * The checks in EditPage use wfDebugLog and logged to different channels, hence the need
	 * for multiple loggers retrieved from the Spi. The channels used are:
	 * - SimpleAntiSpam (in SimpleAntiSpamConstraint)
	 * - SpamRegex (in SpamRegexConstraint)
	 *
	 * TODO can they be combined into the same channel?
	 */
	public function __construct(
		// Multiple
		private readonly ServiceOptions $options,
		private readonly Spi $loggerFactory,
		// EditFilterMergedContentHookConstraint
		private readonly HookContainer $hookContainer,
		// ReadOnlyConstraint
		private readonly ReadOnlyMode $readOnlyMode,
		// SpamRegexConstraint
		private readonly SpamChecker $spamRegexChecker,
		// LinkPurgeRateLimitConstraint
		private readonly RateLimiter $rateLimiter,
		// RedirectConstraint
		private readonly RedirectLookup $redirectLookup,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * @param Content $content
	 * @param IContextSource $context
	 * @param string $summary
	 * @param bool $minorEdit
	 * @param Language $language
	 * @param User $user
	 * @return EditFilterMergedContentHookConstraint
	 */
	public function newEditFilterMergedContentHookConstraint(
		Content $content,
		IContextSource $context,
		string $summary,
		bool $minorEdit,
		Language $language,
		User $user
	): EditFilterMergedContentHookConstraint {
		return new EditFilterMergedContentHookConstraint(
			$this->hookContainer,
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

	/**
	 * @param string $input
	 * @param UserIdentity $user
	 * @param Title $title
	 * @return SimpleAntiSpamConstraint
	 */
	public function newSimpleAntiSpamConstraint(
		string $input,
		UserIdentity $user,
		Title $title
	): SimpleAntiSpamConstraint {
		return new SimpleAntiSpamConstraint(
			$this->loggerFactory->getLogger( 'SimpleAntiSpam' ),
			$input,
			$user,
			$title
		);
	}

	/**
	 * @param string $summary
	 * @param ?string $sectionHeading
	 * @param string $text
	 * @param string $reqIP
	 * @param Title $title
	 * @return SpamRegexConstraint
	 */
	public function newSpamRegexConstraint(
		string $summary,
		?string $sectionHeading,
		string $text,
		string $reqIP,
		Title $title
	): SpamRegexConstraint {
		return new SpamRegexConstraint(
			$this->loggerFactory->getLogger( 'SpamRegex' ),
			$this->spamRegexChecker,
			$summary,
			$sectionHeading,
			$text,
			$reqIP,
			$title
		);
	}

	public function newRedirectConstraint(
		?Title $allowedProblematicRedirectTarget,
		Content $newContent,
		Content $originalContent,
		LinkTarget $title,
		string $submitButtonLabel,
		?string $contentFormat,
	): RedirectConstraint {
		return new RedirectConstraint(
			$allowedProblematicRedirectTarget,
			$newContent,
			$originalContent,
			$title,
			$submitButtonLabel,
			$contentFormat,
			$this->redirectLookup
		);
	}

}
