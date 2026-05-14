<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Page\PageReference;
use MediaWiki\PageEdit\PageEditStatus;
use MediaWiki\ShadowPage\ShadowPageLoader;
use Wikimedia\Message\MessageSpecifier;

/**
 * Don't save a new page if it's blank or if it's a page with shadow content
 * equal to the default, although we allow empty pages in this case to
 * disable messages, see T52124.
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class DefaultTextConstraint extends EditConstraint {

	public function __construct(
		private readonly ShadowPageLoader $shadowPageLoader,
		private readonly PageReference $title,
		private readonly bool $allowBlank,
		private readonly string $userProvidedText,
		private readonly MessageSpecifier $submitButtonLabel,
	) {
	}

	public function checkConstraint(): PageEditStatus {
		$defaultContent = $this->shadowPageLoader->get( $this->title )?->getPreloadContent();
		// TODO: check whether JSON reserialization causes a mismatch
		$defaultText = $defaultContent?->serialize() ?? '';
		if ( !$this->allowBlank && $this->userProvidedText === $defaultText ) {
			return PageEditStatus::newGood( self::AS_BLANK_ARTICLE )
				->setOK( false )
				->warning( 'blankarticle', $this->submitButtonLabel );
		}
		return PageEditStatus::newGood();
	}

}
