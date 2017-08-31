<?php

namespace Wikimedia\Rdbms;

/**
 * Used by Database::nextSequenceValue() so Database::insert() can detect
 * values coming from the deprecated function.
 * @since 1.30
 * @deprecated since 1.30, only exists for backwards compatibility
 */
class NextSequenceValue {
}
