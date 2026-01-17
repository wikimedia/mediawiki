<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Title\Title;
use StatusValue;
use Wikimedia\Message\MessageValue;

/**
 * Don't save a new page if it's blank or if it's a MediaWiki:
 * message with content equivalent to default (allow empty pages
 * in this case to disable messages, see T52124)
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class DefaultTextConstraint implements IEditConstraint {

	/**
	 * @param Title $title
	 * @param bool $allowBlank
	 * @param string $userProvidedText
	 * @param string $submitButtonLabel
	 */
	public function __construct(
		private readonly Title $title,
		private readonly bool $allowBlank,
		private readonly string $userProvidedText,
		private readonly string $submitButtonLabel,
	) {
	}

	public function checkConstraint(): StatusValue {
		$defaultMessageText = $this->title->getDefaultMessageText();
		if ( $this->title->getNamespace() === NS_MEDIAWIKI && $defaultMessageText !== false ) {
			$defaultText = $defaultMessageText;
		} else {
			$defaultText = '';
		}

		if ( !$this->allowBlank && $this->userProvidedText === $defaultText ) {
			return StatusValue::newGood( self::AS_BLANK_ARTICLE )->fatal(
				'blankarticle',
				MessageValue::new( $this->submitButtonLabel ),
			);
		}
		return StatusValue::newGood();
	}

}
