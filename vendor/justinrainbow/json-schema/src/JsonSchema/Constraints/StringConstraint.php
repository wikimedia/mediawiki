<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Constraints;

/**
 * The StringConstraint Constraints, validates an string against a given schema
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * @author Bruno Prieto Reis <bruno.p.reis@gmail.com>
 */
class StringConstraint extends Constraint
{
    /**
     * {@inheritDoc}
     */
    public function check($element, $schema = null, $path = null, $i = null)
    {
        // Verify maxLength
        if (isset($schema->maxLength) && $this->strlen($element) > $schema->maxLength) {
            $this->addError($path, "Must be at most " . $schema->maxLength . " characters long");
        }

        //verify minLength
        if (isset($schema->minLength) && $this->strlen($element) < $schema->minLength) {
            $this->addError($path, "Must be at least " . $schema->minLength . " characters long");
        }

        // Verify a regex pattern
        if (isset($schema->pattern) && !preg_match('#' . str_replace('#', '\\#', $schema->pattern) . '#', $element)) {
            $this->addError($path, "Does not match the regex pattern " . $schema->pattern);
        }

        $this->checkFormat($element, $schema, $path, $i);
    }

    private function strlen($string)
    {
        if (extension_loaded('mbstring')) {
            return mb_strlen($string, mb_detect_encoding($string));
        } else {
            return strlen($string);
        }
    }
}
