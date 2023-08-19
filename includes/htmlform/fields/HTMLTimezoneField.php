<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserTimeCorrection;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * Dropdown widget that allows the user to select a timezone, either by choosing a geographic zone, by using the wiki
 * default, or by manually specifying an offset. It also has an option to fill the value from the browser settings.
 * The value of this field is in a format accepted by UserTimeCorrection.
 */
class HTMLTimezoneField extends HTMLSelectOrOtherField {
	private const FIELD_CLASS = 'mw-htmlform-timezone-field';

	/** @var ITextFormatter */
	private $msgFormatter;

	/**
	 * @stable to call
	 * @inheritDoc
	 * Note that no options should be specified.
	 */
	public function __construct( $params ) {
		if ( isset( $params['options'] ) ) {
			throw new InvalidArgumentException( "Options should not be provided to " . __CLASS__ );
		}
		$params['placeholder-message'] ??= 'timezone-useoffset-placeholder';
		$params['options'] = [];
		parent::__construct( $params );
		$lang = $this->mParent ? $this->mParent->getLanguage() : RequestContext::getMain()->getLanguage();
		$langCode = $lang->getCode();
		$this->msgFormatter = MediaWikiServices::getInstance()->getMessageFormatterFactory()
			->getTextFormatter( $langCode );
		$this->mOptions = $this->getTimezoneOptions();
	}

	/**
	 * @return array<string|string[]>
	 */
	private function getTimezoneOptions(): array {
		$opt = [];

		$localTZoffset = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::LocalTZoffset );
		$timeZoneList = $this->getTimeZoneList();

		$timestamp = MWTimestamp::getLocalInstance();
		// Check that the LocalTZoffset is the same as the local time zone offset
		if ( $localTZoffset === (int)$timestamp->format( 'Z' ) / 60 ) {
			$timezoneName = $timestamp->getTimezone()->getName();
			// Localize timezone
			if ( isset( $timeZoneList[$timezoneName] ) ) {
				$timezoneName = $timeZoneList[$timezoneName]['name'];
			}
			$server_tz_msg = $this->msgFormatter->format(
				MessageValue::new( 'timezoneuseserverdefault', [ $timezoneName ] )
			);
		} else {
			$tzstring = UserTimeCorrection::formatTimezoneOffset( $localTZoffset );
			$server_tz_msg = $this->msgFormatter->format(
				MessageValue::new( 'timezoneuseserverdefault', [ $tzstring ] )
			);
		}
		$opt[$server_tz_msg] = "System|$localTZoffset";
		$opt[$this->msgFormatter->format( MessageValue::new( 'timezoneuseoffset' ) )] = 'other';
		$opt[$this->msgFormatter->format( MessageValue::new( 'guesstimezone' ) )] = 'guess';

		foreach ( $timeZoneList as $timeZoneInfo ) {
			$region = $timeZoneInfo['region'];
			if ( !isset( $opt[$region] ) ) {
				$opt[$region] = [];
			}
			$opt[$region][$timeZoneInfo['name']] = $timeZoneInfo['timecorrection'];
		}
		return $opt;
	}

	/**
	 * Get a list of all time zones
	 * @return string[][] A list of all time zones. The system name of the time zone is used as key and
	 *  the value is an array which contains localized name, the timecorrection value used for
	 *  preferences and the region
	 */
	private function getTimeZoneList(): array {
		$identifiers = DateTimeZone::listIdentifiers();
		'@phan-var array|false $identifiers'; // See phan issue #3162
		if ( $identifiers === false ) {
			return [];
		}
		sort( $identifiers );

		$tzRegions = [
			'Africa' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-africa' ) ),
			'America' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-america' ) ),
			'Antarctica' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-antarctica' ) ),
			'Arctic' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-arctic' ) ),
			'Asia' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-asia' ) ),
			'Atlantic' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-atlantic' ) ),
			'Australia' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-australia' ) ),
			'Europe' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-europe' ) ),
			'Indian' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-indian' ) ),
			'Pacific' => $this->msgFormatter->format( MessageValue::new( 'timezoneregion-pacific' ) ),
		];
		asort( $tzRegions );

		$timeZoneList = [];

		$now = new DateTime();

		foreach ( $identifiers as $identifier ) {
			$parts = explode( '/', $identifier, 2 );

			// DateTimeZone::listIdentifiers() returns a number of
			// backwards-compatibility entries. This filters them out of the
			// list presented to the user.
			if ( count( $parts ) !== 2 || !array_key_exists( $parts[0], $tzRegions ) ) {
				continue;
			}

			// Localize region
			$parts[0] = $tzRegions[$parts[0]];

			$dateTimeZone = new DateTimeZone( $identifier );
			$minDiff = floor( $dateTimeZone->getOffset( $now ) / 60 );

			$display = str_replace( '_', ' ', $parts[0] . '/' . $parts[1] );
			$value = "ZoneInfo|$minDiff|$identifier";

			$timeZoneList[$identifier] = [
				'name' => $display,
				'timecorrection' => $value,
				'region' => $parts[0],
			];
		}

		return $timeZoneList;
	}

	/**
	 * @inheritDoc
	 */
	public function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );
		if ( $p !== true ) {
			return $p;
		}

		if ( !( new UserTimeCorrection( $value ) )->isValid() ) {
			return $this->mParent->msg( 'timezone-invalid' )->escaped();
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	protected function getFieldClasses(): array {
		$classes = parent::getFieldClasses();
		$classes[] = self::FIELD_CLASS;
		return $classes;
	}
}
