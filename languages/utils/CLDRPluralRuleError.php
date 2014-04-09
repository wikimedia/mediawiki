<?php

/**
 * @author Niklas Laxström, Tim Starling
 *
 * @copyright Copyright © 2010-2012, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 *
 * @file
 * @since 1.20
 */

/**
 * The exception class for all the classes in this file. This will be thrown
 * back to the caller if there is any validation error.
 */
class CLDRPluralRuleError extends MWException {
	function __construct( $message ) {
		parent::__construct( 'CLDR plural rule error: ' . $message );
	}
}