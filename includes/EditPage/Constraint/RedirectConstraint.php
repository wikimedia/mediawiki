<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\Content\WikitextContent;
use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Title\Title;
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

	/** @var Title|null the title the problematic redirect is pointing to */
	public ?Title $problematicTarget = null;

	public function __construct(
		private readonly ?Title $allowedProblematicRedirectTarget,
		private readonly Content $newContent,
		private readonly ?Content $originalContent,
		private readonly LinkTarget $title,
		private readonly MessageValue $errorMessageWrapper,
		private readonly ?string $contentFormat,
		private readonly RedirectLookup $redirectLookup,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function checkConstraint(): EditPageStatus {
		$newRedirectTarget = $this->getRedirectTarget( $this->newContent );

		// the constraint should only be checked if there is a redirect in the new content, and either
		// - $allowedProblematicRedirect is null (the last save attempt didn't contain a problematic redirect)
		// - the last save attempt contained a problematic redirect, and the target is not the same as the one in this
		//   save attempt (T395767, T395768)
		if ( $newRedirectTarget !== null && !$this->allowedProblematicRedirectTarget?->equals( $newRedirectTarget ) ) {
			$currentTarget = $this->originalContent !== null
				? $this->getRedirectTarget( $this->originalContent )
				: null;

			// the constraint should only fail if there was no previous content or the previous content contained
			// a problematic redirect to a different page
			if ( !$currentTarget?->equals( $newRedirectTarget ) ) {
				$this->problematicTarget = $newRedirectTarget;

				if ( $newRedirectTarget->equals( $this->title ) ) {
					// redirect pointing to itself - self redirect
					return $this->wrapResult(
						self::AS_SELF_REDIRECT,
						MessageValue::new( 'edit-constraint-selfredirect-warning' )
					);
				} elseif ( !$newRedirectTarget->isKnown() ) {
					// redirect target unknown - broken redirect
					return $this->wrapResult(
						self::AS_BROKEN_REDIRECT,
						MessageValue::new( 'edit-constraint-brokenredirect-warning' )
					);
				} elseif ( $newRedirectTarget->isRedirect() ) {
					$doubleRedirectTarget = Title::castFromLinkTarget(
						$this->redirectLookup->getRedirectTarget( $newRedirectTarget )
					);

					if ( $doubleRedirectTarget?->isSameLinkAs( $this->title ) ) {
						// the double redirect is pointing to the current page, so it's a loop
						return $this->wrapResult(
							self::AS_DOUBLE_REDIRECT_LOOP,
							MessageValue::new( 'edit-constraint-doubleredirect-loop-warning' )
						);
					}

					// redirect target is a redirect - double redirect
					$doubleRedirectTargetTitle = Title::castFromLinkTarget( $doubleRedirectTarget );

					$suggestedRedirectContent = $this->newContent->getContentHandler()
						->makeRedirectContent( $doubleRedirectTargetTitle );
					$suggestedRedirectCode = Html::element(
						'pre',
						[],
						$suggestedRedirectContent?->serialize( $this->contentFormat ) ?? ''
					);

					return $this->wrapResult( self::AS_DOUBLE_REDIRECT, MessageValue::new(
						'edit-constraint-doubleredirect-warning',
						[
							wfEscapeWikiText( $doubleRedirectTargetTitle->getFullText() ),
							$suggestedRedirectCode,
						]
					) );
				} elseif ( !$newRedirectTarget->isValidRedirectTarget() ) {
					// redirect target is not a valid redirect target
					return $this->wrapResult(
						self::AS_INVALID_REDIRECT_TARGET,
						MessageValue::new( 'edit-constraint-invalidredirecttarget-warning' )
					);
				}
			}

		}

		return EditPageStatus::newGood();
	}

	private function wrapResult( int $result, MessageValue $errorMessage ): EditPageStatus {
		return EditPageStatus::newGood( $result )
			->warning( $this->errorMessageWrapper->params( $errorMessage ) )
			->setOK( false );
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
