<?php

namespace MediaWiki\Rest\HeaderParser;

/**
 * This is a parser for "HTTP-date" as defined by RFC 7231.
 *
 * Normally in MediaWiki, dates in HTTP headers are converted using
 * ConvertibleTimestamp or strtotime(), and this is encouraged by RFC 7231:
 *
 *   "Recipients of timestamp values are encouraged to be robust in parsing
 *   timestamps unless otherwise restricted by the field definition."
 *
 * In the case of If-Modified-Since, we are in fact otherwise restricted, since
 * RFC 7232 says:
 *
 *   "A recipient MUST ignore the If-Modified-Since header field if the
 *   received field-value is not a valid HTTP-date"
 *
 * So it is not correct to use strtotime() or ConvertibleTimestamp to parse
 * If-Modified-Since.
 */
class HttpDate extends HeaderParserBase {
	private const DAY_NAMES = [
		'Mon' => true,
		'Tue' => true,
		'Wed' => true,
		'Thu' => true,
		'Fri' => true,
		'Sat' => true,
		'Sun' => true
	];

	private const MONTHS_BY_NAME = [
		'Jan' => 1,
		'Feb' => 2,
		'Mar' => 3,
		'Apr' => 4,
		'May' => 5,
		'Jun' => 6,
		'Jul' => 7,
		'Aug' => 8,
		'Sep' => 9,
		'Oct' => 10,
		'Nov' => 11,
		'Dec' => 12,
	];

	private const DAY_NAMES_LONG = [
		'Monday',
		'Tuesday',
		'Wednesday',
		'Thursday',
		'Friday',
		'Saturday',
		'Sunday',
	];

	/** @var string */
	private $dayName;
	/** @var string */
	private $day;
	/** @var int */
	private $month;
	/** @var int */
	private $year;
	/** @var string */
	private $hour;
	/** @var string */
	private $minute;
	/** @var string */
	private $second;

	/**
	 * Parse an HTTP-date string
	 *
	 * @param string $dateString
	 * @return int|null The UNIX timestamp, or null if the date was invalid
	 */
	public static function parse( $dateString ) {
		$parser = new self( $dateString );
		if ( $parser->execute() ) {
			return $parser->getUnixTime();
		} else {
			return null;
		}
	}

	/**
	 * A convenience function to convert a UNIX timestamp to the preferred
	 * IMF-fixdate format for HTTP header output.
	 *
	 * @param int $unixTime
	 * @return false|string
	 */
	public static function format( $unixTime ) {
		return gmdate( 'D, d M Y H:i:s \G\M\T', $unixTime );
	}

	/**
	 * Private constructor. Use the public static functions for public access.
	 *
	 * @param string $input
	 */
	private function __construct( $input ) {
		$this->setInput( $input );
	}

	/**
	 * Parse the input string
	 *
	 * @return bool True for success
	 */
	private function execute() {
		$this->pos = 0;
		try {
			$this->consumeFixdate();
			$this->assertEnd();
			return true;
		} catch ( HeaderParserError ) {
		}
		$this->pos = 0;
		try {
			$this->consumeRfc850Date();
			$this->assertEnd();
			return true;
		} catch ( HeaderParserError ) {
		}
		$this->pos = 0;
		try {
			$this->consumeAsctimeDate();
			$this->assertEnd();
			return true;
		} catch ( HeaderParserError ) {
		}
		return false;
	}

	/**
	 * Execute the IMF-fixdate rule, or throw an exception
	 *
	 * @throws HeaderParserError
	 */
	private function consumeFixdate() {
		$this->consumeDayName();
		$this->consumeString( ', ' );
		$this->consumeDate1();
		$this->consumeString( ' ' );
		$this->consumeTimeOfDay();
		$this->consumeString( ' GMT' );
	}

	/**
	 * Execute the day-name rule, and capture the result.
	 *
	 * @throws HeaderParserError
	 */
	private function consumeDayName() {
		$next3 = substr( $this->input, $this->pos, 3 );
		if ( isset( self::DAY_NAMES[$next3] ) ) {
			$this->dayName = $next3;
			$this->pos += 3;
		} else {
			$this->error( 'expected day-name' );
		}
	}

	/**
	 * Execute the date1 rule
	 *
	 * @throws HeaderParserError
	 */
	private function consumeDate1() {
		$this->consumeDay();
		$this->consumeString( ' ' );
		$this->consumeMonth();
		$this->consumeString( ' ' );
		$this->consumeYear();
	}

	/**
	 * Execute the day rule, and capture the result.
	 *
	 * @throws HeaderParserError
	 */
	private function consumeDay() {
		$this->day = $this->consumeFixedDigits( 2 );
	}

	/**
	 * Execute the month rule, and capture the result
	 *
	 * @throws HeaderParserError
	 */
	private function consumeMonth() {
		$next3 = substr( $this->input, $this->pos, 3 );
		if ( isset( self::MONTHS_BY_NAME[$next3] ) ) {
			$this->month = self::MONTHS_BY_NAME[$next3];
			$this->pos += 3;
		} else {
			$this->error( 'expected month' );
		}
	}

	/**
	 * Execute the year rule, and capture the result
	 *
	 * @throws HeaderParserError
	 */
	private function consumeYear() {
		$this->year = (int)$this->consumeFixedDigits( 4 );
	}

	/**
	 * Execute the time-of-day rule
	 * @throws HeaderParserError
	 */
	private function consumeTimeOfDay() {
		$this->hour = $this->consumeFixedDigits( 2 );
		$this->consumeString( ':' );
		$this->minute = $this->consumeFixedDigits( 2 );
		$this->consumeString( ':' );
		$this->second = $this->consumeFixedDigits( 2 );
	}

	/**
	 * Execute the rfc850-date rule
	 *
	 * @throws HeaderParserError
	 */
	private function consumeRfc850Date() {
		$this->consumeDayNameLong();
		$this->consumeString( ', ' );
		$this->consumeDate2();
		$this->consumeString( ' ' );
		$this->consumeTimeOfDay();
		$this->consumeString( ' GMT' );
	}

	/**
	 * Execute the date2 rule.
	 *
	 * @throws HeaderParserError
	 */
	private function consumeDate2() {
		$this->consumeDay();
		$this->consumeString( '-' );
		$this->consumeMonth();
		$this->consumeString( '-' );
		$year = $this->consumeFixedDigits( 2 );
		// RFC 2626 section 11.2
		$currentYear = (int)gmdate( 'Y' );
		$startOfCentury = (int)round( $currentYear, -2 );
		$this->year = $startOfCentury + intval( $year );
		$pivot = $currentYear + 50;
		if ( $this->year > $pivot ) {
			$this->year -= 100;
		}
	}

	/**
	 * Execute the day-name-l rule
	 *
	 * @throws HeaderParserError
	 */
	private function consumeDayNameLong() {
		foreach ( self::DAY_NAMES_LONG as $dayName ) {
			if ( substr_compare( $this->input, $dayName, $this->pos, strlen( $dayName ) ) === 0 ) {
				$this->dayName = substr( $dayName, 0, 3 );
				$this->pos += strlen( $dayName );
				return;
			}
		}
		$this->error( 'expected day-name-l' );
	}

	/**
	 * Execute the asctime-date rule
	 *
	 * @throws HeaderParserError
	 */
	private function consumeAsctimeDate() {
		$this->consumeDayName();
		$this->consumeString( ' ' );
		$this->consumeDate3();
		$this->consumeString( ' ' );
		$this->consumeTimeOfDay();
		$this->consumeString( ' ' );
		$this->consumeYear();
	}

	/**
	 * Execute the date3 rule
	 *
	 * @throws HeaderParserError
	 */
	private function consumeDate3() {
		$this->consumeMonth();
		$this->consumeString( ' ' );
		if ( ( $this->input[$this->pos] ?? '' ) === ' ' ) {
			$this->pos++;
			$this->day = '0' . $this->consumeFixedDigits( 1 );
		} else {
			$this->day = $this->consumeFixedDigits( 2 );
		}
	}

	/**
	 * Convert the captured results to a UNIX timestamp.
	 * This should only be called after parsing succeeds.
	 *
	 * @return int
	 */
	private function getUnixTime() {
		return gmmktime( (int)$this->hour, (int)$this->minute, (int)$this->second,
			$this->month, (int)$this->day, $this->year );
	}
}
