<?php

namespace MediaWiki\ParamValidator\TypeDef;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
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

	private ChangeTagsStore $changeTagsStore;

	public function __construct( Callbacks $callbacks, ChangeTagsStore $changeTagsStore ) {
		parent::__construct( $callbacks );
		$this->changeTagsStore = $changeTagsStore;
	}

	/** @inheritDoc */
	public function validate( $name, $value, array $settings, array $options ) {
		$this->failIfNotString( $name, $value, $settings, $options );

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
			$msg = $tagsStatus->getMessage();
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

	/** @inheritDoc */
	public function getEnumValues( $name, array $settings, array $options ) {
		return $this->changeTagsStore->listExplicitlyDefinedTags();
	}

}
