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
use MediaWiki\Logger\Spi;
use MediaWiki\MainConfigNames;
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

	private ServiceOptions $options;
	private Spi $loggerFactory;
	private HookContainer $hookContainer;
	private ReadOnlyMode $readOnlyMode;
	private SpamChecker $spamRegexChecker;
	private RateLimiter $rateLimiter;

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
	 *
	 * @param ServiceOptions $options
	 * @param Spi $loggerFactory
	 * @param HookContainer $hookContainer
	 * @param ReadOnlyMode $readOnlyMode
	 * @param SpamChecker $spamRegexChecker
	 * @param RateLimiter $rateLimiter
	 */
	public function __construct(
		ServiceOptions $options,
		Spi $loggerFactory,
		HookContainer $hookContainer,
		ReadOnlyMode $readOnlyMode,
		SpamChecker $spamRegexChecker,
		RateLimiter $rateLimiter
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		// Multiple
		$this->options = $options;
		$this->loggerFactory = $loggerFactory;

		// EditFilterMergedContentHookConstraint
		$this->hookContainer = $hookContainer;

		// ReadOnlyConstraint
		$this->readOnlyMode = $readOnlyMode;

		// SpamRegexConstraint
		$this->spamRegexChecker = $spamRegexChecker;

		// LinkPurgeRateLimitConstraint
		$this->rateLimiter = $rateLimiter;
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

}
