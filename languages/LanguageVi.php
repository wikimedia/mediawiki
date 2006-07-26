<?php
/**
  * Based on Language.php 1.645
  * @package MediaWiki
  * @subpackage Language
  * Compatible to MediaWiki 1.5
  * Initial translation by Trần Thế Trung and Nguyễn Thanh Quang
  * Last update 28 August 2005 (UTC)
  */

class LanguageVi extends Language {
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$datePreference = $this->dateFormat( $format );

		$month = $this->formatMonth( substr( $ts, 4, 2 ), $datePreference );
		$day = $this->formatDay( substr( $ts, 6, 2 ), $datePreference );
		$year = $this->formatNum( substr( $ts, 0, 4 ), true );

		switch( $datePreference ) {
			case 3:
			case 4: return "$day/$month/$year";
			case MW_DATE_ISO: return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
			default: return "$day $month năm $year";
		}
	}

	function timeSeparator( $format ) {
		$datePreference = $this->dateFormat($format);
			switch ( $datePreference ) {
				case '4': return 'h';
				default:  return ':';
			}
	}

	function timeDateSeparator( $format ) {
		$datePreference = $this->dateFormat($format);
			switch ( $datePreference ) {
				case '0':
				case '1':
				case '2': return ', ';
				default:  return ' ';
			}
	}

	function formatMonth( $month, $format ) {
		$datePreference = $this->dateFormat($format);
			switch ( $datePreference ) {
				case '0':
				case '1': return 'tháng ' . ( 0 + $month );
				case '2': return 'tháng ' . $this->getSpecialMonthName( $month );
				default:  return 0 + $month;
			}
	}

	function formatDay( $day, $format ) {
		$datePreference = $this->dateFormat($format);
			switch ( $datePreference ) {
				case '0':
				case '1':
				case '2': return 'ngày ' . (0 + $day);
				default:  return 0 + $day;
			}
	}

	function getSpecialMonthName( $key ) {
		$names = 'Một, Hai, Ba, Tư, Năm, Sáu, Bảy, Tám, Chín, Mười, Mười một, Mười hai';
		$names = explode(', ', $names);
		return $names[$key-1];
	}
}

?>
