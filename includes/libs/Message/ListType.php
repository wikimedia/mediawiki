<?php

namespace Wikimedia\Message;

/**
 * The constants used to specify list types. The values of the constants are an
 * unstable implementation detail.
 */
enum ListType: string {
	/** A comma-separated list */
	case COMMA = 'comma';

	/** A semicolon-separated list */
	case SEMICOLON = 'semicolon';

	/** A pipe-separated list */
	case PIPE = 'pipe';

	/** A natural-language list separated by "and" */
	case AND = 'text';

	/**
	 * Return the ListTypes, as an array of string flag values.
	 * @return list<string>
	 */
	public static function values(): array {
		return array_column( self::cases(), 'value' );
	}
}
