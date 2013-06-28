<?php
/*
 * Tests for wfDateTime() and wfLocalDateTime();
 */
class WfDateTimeTest extends MediaWikiTestCase {

	function testLocalTimezone() {
		global $wgLocaltimezone;
		$dateTime = wfLocalDateTime();
		$this->assertEquals( $wgLocaltimezone, $dateTime->getTimezone()->getName(), "wfLocalDateTime returns local time zone" );
		$this->assertEquals( date( 'T' ), $dateTime->getTimezone()->getName(), "wfLocalDateTime returns local time zone" );
	}

	function testUTCTimezone() {
		$dateTime = wfDateTime();
		$this->assertEquals( 'UTC', $dateTime->getTimezone()->getName(), "wfDateTime returns UTC time zone" );
	}

	function testLocalTimezoneOwnTimestamp() {
		global $wgLocaltimezone;
		$ts = time() - 10000;
		$dateTime = wfLocalDateTime( $ts );
		$this->assertEquals( $wgLocaltimezone, $dateTime->getTimezone()->getName(), "wfLocalDateTime returns local time zone" );
		$this->assertEquals( date( 'T' ), $dateTime->getTimezone()->getName(), "wfLocalDateTime returns local time zone" );
		$this->assertEquals( $ts, $dateTime->getTimestamp(), "wfLocalDateTime returns same timestamp" );
	}

	function testUTCTimezoneOwnTimestamp() {
		$ts = time() - 10000;
		$dateTime = wfDateTime( $ts );
		$this->assertEquals( 'UTC', $dateTime->getTimezone()->getName(), "wfDateTime returns UTC time zone" );
		$this->assertEquals( $ts, $dateTime->getTimestamp(), "wfDateTime returns same timestamp" );
	}
}
