<?php

namespace Wikimedia\Message;

/**
 * The constants used to specify parameter types. The values of the constants
 * are an unstable implementation detail, and correspond to the names of the
 * parameter types in the Message class.
 */
class ParamType {
	/** A simple text parameter */
	const TEXT = 'text';

	/** A number, to be formatted using local digits and separators */
	const NUM = 'num';

	/** A number of seconds, to be formatted as natural language text. */
	const DURATION_LONG = 'duration';

	/** A number of seconds, to be formatted in an abbreviated way. */
	const DURATION_SHORT = 'timeperiod';

	/**
	 * An expiry time for a block. The input is either a timestamp in one
	 * of the formats accepted by the Wikimedia\Timestamp library, or
	 * "infinity" for an infinite block.
	 */
	const EXPIRY = 'expiry';

	/** A number of bytes. */
	const SIZE = 'size';

	/** A number of bits per second. */
	const BITRATE = 'bitrate';

	/** The list type (ListParam) */
	const LIST = 'list';

	/**
	 * A text parameter which is substituted after preprocessing, and so is
	 * not available to the preprocessor and cannot be modified by it.
	 */
	const RAW = 'raw';

	/** Reserved for future use. */
	const PLAINTEXT = 'plaintext';
}
