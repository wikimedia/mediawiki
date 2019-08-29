<?php

namespace Wikimedia\Message;

/**
 * The constants used to specify parameter types. The values of the constants
 * are an unstable implementation detail.
 *
 * Unless otherwise noted, these should be used with an instance of ScalarParam.
 */
class ParamType {
	/** A simple text string or another MessageValue, not otherwise formatted. */
	const TEXT = 'text';

	/** A number, to be formatted using local digits and separators */
	const NUM = 'num';

	/**
	 * A number of seconds, to be formatted as natural language text.
	 * The value will be output exactly.
	 */
	const DURATION_LONG = 'duration';

	/**
	 * A number of seconds, to be formatted as natural language text in an abbreviated way.
	 * The output will be rounded to an appropriate magnitude.
	 */
	const DURATION_SHORT = 'timeperiod';

	/**
	 * An expiry time.
	 *
	 * The input is either a timestamp in one of the formats accepted by the
	 * Wikimedia\Timestamp library, or "infinity" if the thing doesn't expire.
	 *
	 * The output is a date and time in local format, or a string representing
	 * an "infinite" expiry.
	 */
	const EXPIRY = 'expiry';

	/** A number of bytes. The output will be rounded to an appropriate magnitude. */
	const SIZE = 'size';

	/** A number of bits per second. The output will be rounded to an appropriate magnitude. */
	const BITRATE = 'bitrate';

	/** A list of values. Must be used with ListParam. */
	const LIST = 'list';

	/**
	 * A text parameter which is substituted after formatter processing.
	 *
	 * The creator of the parameter and message is responsible for ensuring
	 * that the value will be safe for the intended output format, and
	 * documenting what that intended output format is.
	 */
	const RAW = 'raw';

	/**
	 * A text parameter which is substituted after formatter processing.
	 * The output will be escaped as appropriate for the output format so
	 * as to represent plain text rather than any sort of markup.
	 */
	const PLAINTEXT = 'plaintext';
}
