<?php

namespace Wikimedia\Message;

/**
 * The constants used to specify list types. The values of the constants are an
 * unstable implementation detail.
 */
class ListType {
	/** A comma-separated list */
	public const COMMA = 'comma';

	/** A semicolon-separated list */
	public const SEMICOLON = 'semicolon';

	/** A pipe-separated list */
	public const PIPE = 'pipe';

	/** A natural-language list separated by "and" */
	public const AND = 'text';
}
