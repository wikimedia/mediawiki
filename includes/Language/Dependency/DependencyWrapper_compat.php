<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

// phpcs:disable MediaWiki.Files.ClassMatchesFilename.NotMatch

// NO_NAMESPACE backward compatibility

/**
 * Compatibility class for unserialize of php code from cache.
 * Adding a namespace breaks class properties, the serialize content contains full qualified names,
 * that does not unserialize clean for private properties.
 * T415619 / T428663
 *
 * @deprecated Since 1.47
 * @ingroup Language
 */
class DependencyWrapper extends \MediaWiki\Language\Dependency\DependencyWrapper {
}
