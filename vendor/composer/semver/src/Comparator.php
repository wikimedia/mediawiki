<?php

/*
 * This file is part of composer/semver.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Semver;

use Composer\Semver\Constraint\VersionConstraint;

class Comparator
{
    /**
     * Whether $version1 > $version2.
     *
     * @param string $version1
     * @param string $version2
     *
     * @return bool
     */
    public static function greaterThan($version1, $version2)
    {
        return self::compare($version1, '>', $version2);
    }

    /**
     * Whether $version1 >= $version2.
     *
     * @param string $version1
     * @param string $version2
     *
     * @return bool
     */
    public static function greaterThanOrEqualTo($version1, $version2)
    {
        return self::compare($version1, '>=', $version2);
    }

    /**
     * Whether $version1 < $version2.
     *
     * @param string $version1
     * @param string $version2
     *
     * @return bool
     */
    public static function lessThan($version1, $version2)
    {
        return self::compare($version1, '<', $version2);
    }

    /**
     * Whether $version1 <= $version2.
     *
     * @param string $version1
     * @param string $version2
     *
     * @return bool
     */
    public static function lessThanOrEqualTo($version1, $version2)
    {
        return self::compare($version1, '<=', $version2);
    }

    /**
     * Whether $version1 == $version2.
     *
     * @param string $version1
     * @param string $version2
     *
     * @return bool
     */
    public static function equalTo($version1, $version2)
    {
        return self::compare($version1, '==', $version2);
    }

    /**
     * Whether $version1 != $version2.
     *
     * @param string $version1
     * @param string $version2
     *
     * @return bool
     */
    public static function notEqualTo($version1, $version2)
    {
        return self::compare($version1, '!=', $version2);
    }

    /**
     * Evaluate $version1 $operator $version2.
     *
     * @param string $version1
     * @param string $operator Comparison operator like ">" or "<=", etc.
     * @param string $version2
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public static function compare($version1, $operator, $version2)
    {
        if (!in_array($operator, VersionConstraint::getSupportedOperators())) {
            throw new \InvalidArgumentException(sprintf(
                'Operator "%s" not supported, expected one of: %s',
                $operator,
                implode(', ', VersionConstraint::getSupportedOperators())
            ));
        }

        $constraint = new VersionConstraint($operator, $version2);

        return $constraint->matches(new VersionConstraint('==', $version1));
    }
}
