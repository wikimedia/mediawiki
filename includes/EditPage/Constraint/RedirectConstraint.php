<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Title\Title;
use StatusValue;
use Wikimedia\Message\MessageValue;

/**
 * Verify the page does not redirect to
 *  - a page that does not exist (broken redirect)
 *  - another redirect (double redirect)
 *  - another redirect pointing to the current page (double redirect loop)
 *  - itself (self redirect)
 * unless
 *  - the user is okay with it and submits the edit twice
 *  - the page already had this problem before the edit
 *
 * @since 1.45
 * @internal
 */
class RedirectConstraint implements IEditConstraint {

	/** @var int|null the problem affecting the redirect, or null if the constraint passed */
	public ?int $status = null;

	/** @var Title|null the target page of the redirect the page is pointing to */
	private ?Title $doubleRedirectTarget = null;

	/** @var Title|null the title the problematic redirect is pointing to */
	public ?Title $problematicTarget = null;

	public function __construct(
		private readonly ?Title $allowedProblematicRedirectTarget,
		private readonly Content $newContent,
		private readonly Content $originalContent,
		private readonly LinkTarget $title,
		private readonly string $submitButtonLabel,
		private readonly ?string $contentFormat,
		private readonly RedirectLookup $redirectLookup,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function checkConstraint(): string {
		$newRedirectTarget = $this->getRedirectTarget( $this->newContent );

		// the constraint should only be checked if there is a redirect in the new content, and either
		// - $allowedProblematicRedirect is null (the last save attempt didn't contain a problematic redirect)
		// - the last save attempt contained a problematic redirect, and the target is not the same as the one in this
		//   save attempt (T395767, T395768)
		if ( $newRedirectTarget !== null && !$this->allowedProblematicRedirectTarget?->equals( $newRedirectTarget ) ) {
			$currentTarget = $this->getRedirectTarget( $this->originalContent );

			// the constraint should only fail if there was no previous content or the previous content contained
			// a problematic redirect to a different page
			if ( !$currentTarget?->equals( $newRedirectTarget ) ) {

				$this->problematicTarget = $newRedirectTarget;
				if ( $newRedirectTarget->equals( $this->title ) ) {
					// redirect pointing to itself - self redirect
					$this->status = self::AS_SELF_REDIRECT;
					return self::CONSTRAINT_FAILED;
				} elseif ( !$newRedirectTarget->isKnown() ) {
					// redirect target unknown - broken redirect
					$this->status = self::AS_BROKEN_REDIRECT;
					return self::CONSTRAINT_FAILED;
				} elseif ( $newRedirectTarget->isRedirect() ) {
					// redirect target is a redirect - double redirect
					$this->status = self::AS_DOUBLE_REDIRECT;

					$this->doubleRedirectTarget = Title::castFromLinkTarget(
						$this->redirectLookup->getRedirectTarget( $newRedirectTarget )
					);
					if ( $this->doubleRedirectTarget?->isSameLinkAs( $this->title ) ) {
						// the double redirect is pointing to the current page, so it's a loop
						$this->status = self::AS_DOUBLE_REDIRECT_LOOP;
					}

					return self::CONSTRAINT_FAILED;
				} elseif ( !$newRedirectTarget->isValidRedirectTarget() ) {
					// redirect target is not a valid redirect target
					$this->status = self::AS_INVALID_REDIRECT_TARGET;
					return self::CONSTRAINT_FAILED;
				}
			}

		}

		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood( $this->status );

		switch ( $this->status ) {
			case self::AS_BROKEN_REDIRECT:
				$statusValue->fatal(
					'edit-constraint-brokenredirect',
					MessageValue::new( $this->submitButtonLabel )
				);
				break;
			case self::AS_DOUBLE_REDIRECT:
				$doubleRedirectTargetTitle = Title::castFromLinkTarget( $this->doubleRedirectTarget );

				$suggestedRedirectContent = $this->newContent->getContentHandler()
					->makeRedirectContent( $doubleRedirectTargetTitle );
				$suggestedRedirectCode = Html::element(
					'pre',
					[],
					$suggestedRedirectContent?->serialize( $this->contentFormat ) ?? ''
				);

				$statusValue->fatal(
					'edit-constraint-doubleredirect',
					MessageValue::new( $this->submitButtonLabel ),
					wfEscapeWikiText( $doubleRedirectTargetTitle->getPrefixedText() ),
					$suggestedRedirectCode,
				);
				break;
			case self::AS_DOUBLE_REDIRECT_LOOP:
				$statusValue->fatal(
					'edit-constraint-doubleredirect-loop',
					MessageValue::new( $this->submitButtonLabel )
				);
				break;
			case self::AS_INVALID_REDIRECT_TARGET:
				$statusValue->fatal(
					'edit-constraint-invalidredirecttarget',
					MessageValue::new( $this->submitButtonLabel )
				);
				break;
			case self::AS_SELF_REDIRECT:
				$statusValue->fatal(
					'selfredirect',
					MessageValue::new( $this->submitButtonLabel )
				);
				break;
		}

		return $statusValue;
	}

	private function getRedirectTarget( Content $content ): ?Title {
		if ( $content instanceof WikitextContent ) {
			// specify $allowInvalidTarget since the WikitextContentHandler won't return invalid targets otherwise
			[ $target, ] = $content->getContentHandler()->extractRedirectTargetAndText( $content, true );
			return Title::castFromLinkTarget( $target );
		}
		return $content->getRedirectTarget();
	}

}
