<?php

namespace MediaWiki\Gallery\Exception;

use LogicException;

/**
 * Class for exceptions thrown by ImageGalleryBase::factory().
 *
 * @since 1.38
 */
class ImageGalleryClassNotFoundException extends LogicException {
}

/** @deprecated class alias since 1.46 */
class_alias( ImageGalleryClassNotFoundException::class, 'ImageGalleryClassNotFoundException' );
