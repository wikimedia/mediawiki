<?php

/**
 * @group Media
 */
class XMPValidateTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideDates
	 * @covers XMPValidate::validateDate
	 */
	public function testValidateDate( $value, $expected ) {
		// The method should modify $value.
		XMPValidate::validateDate( array(), $value, true );
		$this->assertEquals( $expected, $value );
	}

	public static function provideDates() {
		/* For reference valid date formats are:
		 * YYYY
		 * YYYY-MM
		 * YYYY-MM-DD
		 * YYYY-MM-DDThh:mmTZD
		 * YYYY-MM-DDThh:mm:ssTZD
		 * YYYY-MM-DDThh:mm:ss.sTZD
		 * (Time zone is optional)
		 */
		return array(
			array( '1992', '1992' ),
			array( '1992-04', '1992:04' ),
			array( '1992-02-01', '1992:02:01' ),
			array( '2011-09-29', '2011:09:29' ),
			array( '1982-12-15T20:12', '1982:12:15 20:12' ),
			array( '1982-12-15T20:12Z', '1982:12:15 20:12' ),
			array( '1982-12-15T20:12+02:30', '1982:12:15 22:42' ),
			array( '1982-12-15T01:12-02:30', '1982:12:14 22:42' ),
			array( '1982-12-15T20:12:11', '1982:12:15 20:12:11' ),
			array( '1982-12-15T20:12:11Z', '1982:12:15 20:12:11' ),
			array( '1982-12-15T20:12:11+01:10', '1982:12:15 21:22:11' ),
			array( '2045-12-15T20:12:11', '2045:12:15 20:12:11' ),
			array( '1867-06-01T15:00:00', '1867:06:01 15:00:00' ),
			/* some invalid ones */
			array( '2001--12', null ),
			array( '2001-5-12', null ),
			array( '2001-5-12TZ', null ),
			array( '2001-05-12T15', null ),
			array( '2001-12T15:13', null ),
		);
	}
}
