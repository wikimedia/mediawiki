<?php

namespace Wikimedia\Message;

/**
 * The constants used to specify list types. The values of the constants are an
 * unstable implementation detail and correspond to the names of the list types
 * in the Message class.
 */
class ListType {
	/** A comma-separated list */
	const COMMA = 'comma';

	/** A semicolon-separated list */
	const SEMICOLON = 'semicolon';

	/** A pipe-separated list */
	const PIPE = 'pipe';

	/** A natural-language list separated by "and" */
	const AND = 'text';
}
