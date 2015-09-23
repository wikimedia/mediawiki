<?php

/*
 * This file is part of composer/semver.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\Semver\Constraint;

/**
 * Provides a common basis for specific package link constraints.
 *
 * @author Nils Adermann <naderman@naderman.de>
 */
abstract class SpecificConstraint implements LinkConstraintInterface
{
    /** @var string */
    protected $prettyString;

    /**
     * @param LinkConstraintInterface $provider
     *
     * @return bool
     */
    public function matches(LinkConstraintInterface $provider)
    {
        if ($provider instanceof MultiConstraint) {
            // turn matching around to find a match
            return $provider->matches($this);
        } elseif ($provider instanceof $this) {
            // see note at bottom of this class declaration
            return $this->matchSpecific($provider);
        }

        return true;
    }

    /**
     * @param string $prettyString
     */
    public function setPrettyString($prettyString)
    {
        $this->prettyString = $prettyString;
    }

    /**
     * @return string
     */
    public function getPrettyString()
    {
        if ($this->prettyString) {
            return $this->prettyString;
        }

        return $this->__toString();
    }

    // implementations must implement a method of this format:
    // not declared abstract here because type hinting violates parameter coherence (TODO right word?)
    // public function matchSpecific(<SpecificConstraintType> $provider);
}
