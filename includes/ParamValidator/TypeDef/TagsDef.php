<?php

namespace MediaWiki\ParamValidator\TypeDef;

use ChangeTags;
use MediaWiki\Message\Converter as MessageConverter;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\Callbacks;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Type definition for tags type
 *
 * A tags type is an enum type for selecting MediaWiki change tags.
 *
 * Failure codes:
 *  - 'badtags': The value was not a valid set of tags. Data:
 *    - 'disallowedtags': The tags that were disallowed.
 *
 * @since 1.35
 */
class TagsDef extends EnumDef {

	/** @var MessageConverter */
	private $messageConverter;

	public function __construct( Callbacks $callbacks ) {
		parent::__construct( $callbacks );
		$this->messageConverter = new MessageConverter();
	}

	public function validate( $name, $value, array $settings, array $options ) {
		// Validate the full list of tags at once, because the caller will
		// *probably* stop at the first exception thrown.
		if ( isset( $options['values-list'] ) ) {
			$ret = $value;
			$tagsStatus = ChangeTags::canAddTagsAccompanyingChange( $options['values-list'] );
		} else {
			// The 'tags' type always returns an array.
			$ret = [ $value ];
			$tagsStatus = ChangeTags::canAddTagsAccompanyingChange( $ret );
		}

		if ( !$tagsStatus->isGood() ) {
			$msg = $this->messageConverter->convertMessage( $tagsStatus->getMessage() );
			$data = [];
			if ( $tagsStatus->value ) {
				// Specific tags are not allowed.
				$data['disallowedtags'] = $tagsStatus->value;
			// @codeCoverageIgnoreStart
			} else {
				// All are disallowed, I guess
				$data['disallowedtags'] = $settings['values-list'] ?? $ret;
			}
			// @codeCoverageIgnoreEnd

			// Only throw if $value is among the disallowed tags
			if ( in_array( $value, $data['disallowedtags'], true ) ) {
				throw new ValidationException(
					DataMessageValue::new( $msg->getKey(), $msg->getParams(), 'badtags', $data ),
					$name, $value, $settings
				);
			}
		}

		return $ret;
	}

	public function getEnumValues( $name, array $settings, array $options ) {
		return ChangeTags::listExplicitlyDefinedTags();
	}

}
