<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Json
 */

namespace MediaWiki\Json;

/**
 * Defines JSON-related constants.
 * @internal
 * @package MediaWiki\Json
 */
interface JsonConstants {

	/**
	 * Name of the property where the class information is stored.
	 */
	public const TYPE_ANNOTATION = '_type_';
	/**
	 * Name of the marker property to indicate that array contents
	 * need to be examined during deserialization.
	 */
	public const COMPLEX_ANNOTATION = '_complex_';
}
