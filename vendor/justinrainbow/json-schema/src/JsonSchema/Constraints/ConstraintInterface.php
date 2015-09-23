<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Constraints;

/**
 * The Constraints Interface
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
interface ConstraintInterface
{
    /**
     * returns all collected errors
     *
     * @return array
     */
    public function getErrors();

    /**
     * adds errors to this validator
     *
     * @param array $errors
     */
    public function addErrors(array $errors);

    /**
     * adds an error
     *
     * @param $path
     * @param $message
     */
    public function addError($path, $message);

    /**
     * checks if the validator has not raised errors
     *
     * @return boolean
     */
    public function isValid();

    /**
     * invokes the validation of an element
     *
     * @abstract
     * @param mixed $value
     * @param mixed $schema
     * @param mixed $path
     * @param mixed $i
     */
    public function check($value, $schema = null, $path = null, $i = null);
}