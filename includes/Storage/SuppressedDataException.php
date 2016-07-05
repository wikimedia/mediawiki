<?php

namespace MediaWiki\Storage;
use Revision;

/**
 * Exception indicating that access to some data was denied to the given audience.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class SuppressedDataException extends StorageException {

	private static $audienceNames = [
		Revision::RAW => 'RAW',
		Revision::FOR_PUBLIC => 'PUBLIC',
		Revision::FOR_THIS_USER => 'USER',
	];

	private static $suppressionNames = [
		Revision::DELETED_TEXT => 'content',
		Revision::DELETED_COMMENT => 'comment',
		Revision::DELETED_USER => 'user',
		Revision::DELETED_RESTRICTED => 'restricted',
	];

	/**
	 * SuppressedDataException constructor.
	 *
	 * @param int|string $name name of the suppressed data, or a Revision::DELETED_XXX flag
	 * @param int|string $audience name of the audience, or a Revision::FOR_XXX flag.
	 */
	public function __construct( $name, $audience ) {
		$name = isset( self::$suppressionNames[$name] ) ? self::$suppressionNames[$name] : $name;
		$audience = isset( self::$audienceNames[$audience] ) ? self::$audienceNames[$name] : $audience;

		parent::__construct( "Access suppressed to $name for $audience." );
	}

}
