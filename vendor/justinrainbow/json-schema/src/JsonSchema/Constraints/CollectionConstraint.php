<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Constraints;

/**
 * The CollectionConstraint Constraints, validates an array against a given schema
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * @author Bruno Prieto Reis <bruno.p.reis@gmail.com>
 */
class CollectionConstraint extends Constraint
{
    /**
     * {@inheritDoc}
     */
    public function check($value, $schema = null, $path = null, $i = null)
    {
        // Verify minItems
        if (isset($schema->minItems) && count($value) < $schema->minItems) {
            $this->addError($path, "There must be a minimum of " . $schema->minItems . " items in the array");
        }

        // Verify maxItems
        if (isset($schema->maxItems) && count($value) > $schema->maxItems) {
            $this->addError($path, "There must be a maximum of " . $schema->maxItems . " items in the array");
        }

        // Verify uniqueItems
        if (isset($schema->uniqueItems)) {
            $unique = $value;
            if (is_array($value) && count($value)) {
                $unique = array_map(function($e) { return var_export($e, true); }, $value);
            }
            if (count(array_unique($unique)) != count($value)) {
                $this->addError($path, "There are no duplicates allowed in the array");
            }
        }

        // Verify items
        if (isset($schema->items)) {
            $this->validateItems($value, $schema, $path, $i);
        }
    }

    /**
     * Validates the items
     *
     * @param array     $value
     * @param \stdClass $schema
     * @param string    $path
     * @param string    $i
     */
    protected function validateItems($value, $schema = null, $path = null, $i = null)
    {
        if (is_object($schema->items)) {
            // just one type definition for the whole array
            foreach ($value as $k => $v) {
                $initErrors = $this->getErrors();

                // First check if its defined in "items"
                $this->checkUndefined($v, $schema->items, $path, $k);

                // Recheck with "additionalItems" if the first test fails
                if (count($initErrors) < count($this->getErrors()) && (isset($schema->additionalItems) && $schema->additionalItems !== false)) {
                    $secondErrors = $this->getErrors();
                    $this->checkUndefined($v, $schema->additionalItems, $path, $k);
                }

                // Reset errors if needed
                if (isset($secondErrors) && count($secondErrors) < count($this->getErrors())) {
                    $this->errors = $secondErrors;
                } else if (isset($secondErrors) && count($secondErrors) === count($this->getErrors())) {
                    $this->errors = $initErrors;
                }
            }
        } else {
            // Defined item type definitions
            foreach ($value as $k => $v) {
                if (array_key_exists($k, $schema->items)) {
                    $this->checkUndefined($v, $schema->items[$k], $path, $k);
                } else {
                    // Additional items
                    if (property_exists($schema, 'additionalItems')) {
                        if ($schema->additionalItems !== false) {
                            $this->checkUndefined($v, $schema->additionalItems, $path, $k);
                        } else {
                            $this->addError(
                                $path, 'The item ' . $i . '[' . $k . '] is not defined and the definition does not allow additional items');
                        }
                    } else {
                        // Should be valid against an empty schema
                        $this->checkUndefined($v, new \stdClass(), $path, $k);
                    }
                }
            }

            // Treat when we have more schema definitions than values, not for empty arrays
            if(count($value) > 0) {
                for ($k = count($value); $k < count($schema->items); $k++) {
                    $this->checkUndefined(new UndefinedConstraint(), $schema->items[$k], $path, $k);
                }
            }
        }
    }
}
