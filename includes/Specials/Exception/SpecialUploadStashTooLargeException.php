<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Exception;

use MediaWiki\Upload\Exception\UploadStashException;

/**
 * @newable
 * @ingroup SpecialPage
 * @ingroup Upload
 */
class SpecialUploadStashTooLargeException extends UploadStashException {
}

/** @deprecated class alias since 1.46 */
class_alias( SpecialUploadStashTooLargeException::class, 'SpecialUploadStashTooLargeException' );
