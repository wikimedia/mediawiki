<?php
/**
 * Copyright 2014
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Skin;

use LogicException;

/**
 * Exceptions for skin-related failures
 *
 * @newable
 * @since 1.24
 */
class SkinException extends LogicException {
}

/** @deprecated class alias since 1.44 */
class_alias( SkinException::class, 'SkinException' );
