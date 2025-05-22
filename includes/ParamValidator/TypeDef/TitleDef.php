<?php

namespace MediaWiki\ParamValidator\TypeDef;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Title\TitleFactory;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\Callbacks;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for page titles.
 *
 * Failure codes:
 * - 'badtitle': invalid title (e.g. containing disallowed characters). No data.
 * - 'missingtitle': the page with this title does not exist (when PARAM_MUST_EXIST
 *   was specified). No data.
 *
 * @since 1.36
 */
class TitleDef extends TypeDef {

	/**
	 * (bool) Whether the page with the given title needs to exist.
	 *
	 * Defaults to false.
	 */
	public const PARAM_MUST_EXIST = 'param-must-exist';

	/**
	 * (bool) Whether to return a LinkTarget.
	 *
	 * If false, the validated title is returned as a string (in getPrefixedText() format).
	 * Default is false.
	 *
	 * Avoid setting true with PARAM_ISMULTI, as it may result in excessive DB
	 * lookups. If you do combine them, consider setting low values for
	 * PARAM_ISMULTI_LIMIT1 and PARAM_ISMULTI_LIMIT2 to mitigate it.
	 */
	public const PARAM_RETURN_OBJECT = 'param-return-object';

	/** @var TitleFactory */
	private $titleFactory;

	public function __construct( Callbacks $callbacks, TitleFactory $titleFactory ) {
		parent::__construct( $callbacks );
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @inheritDoc
	 * @return string|LinkTarget Depending on the PARAM_RETURN_OBJECT setting.
	 */
	public function validate( $name, $value, array $settings, array $options ) {
		$mustExist = !empty( $settings[self::PARAM_MUST_EXIST] );
		$returnObject = !empty( $settings[self::PARAM_RETURN_OBJECT] );

		$this->failIfNotString( $name, $value, $settings, $options );

		$title = $this->titleFactory->newFromText( $value );

		if ( !$title ) {
			// Message used: paramvalidator-badtitle
			$this->failure( 'badtitle', $name, $value, $settings, $options );
		} elseif ( $mustExist && !$title->exists() ) {
			// Message used: paramvalidator-missingtitle
			$this->failure( 'missingtitle', $name, $value, $settings, $options );
		}

		if ( $returnObject ) {
			return $title->getTitleValue();
		} else {
			return $title->getPrefixedText();
		}
	}

	/** @inheritDoc */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( $value instanceof LinkTarget ) {
			return $this->titleFactory->newFromLinkTarget( $value )->getPrefixedText();
		}
		return parent::stringifyValue( $name, $value, $settings, $options );
	}

	/** @inheritDoc */
	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'][] = self::PARAM_MUST_EXIST;
		$ret['allowedKeys'][] = self::PARAM_RETURN_OBJECT;

		if ( !is_bool( $settings[self::PARAM_MUST_EXIST] ?? false ) ) {
			$ret['issues'][self::PARAM_MUST_EXIST] = 'PARAM_MUST_EXIST must be boolean, got '
				. gettype( $settings[self::PARAM_MUST_EXIST] );
		}

		if ( !is_bool( $settings[self::PARAM_RETURN_OBJECT] ?? false ) ) {
			$ret['issues'][self::PARAM_RETURN_OBJECT] = 'PARAM_RETURN_OBJECT must be boolean, got '
				. gettype( $settings[self::PARAM_RETURN_OBJECT] );
		}

		if ( !empty( $settings[ParamValidator::PARAM_ISMULTI] ) &&
			!empty( $settings[self::PARAM_RETURN_OBJECT] ) &&
			(
				( $settings[ParamValidator::PARAM_ISMULTI_LIMIT1] ?? 100 ) > 10 ||
				( $settings[ParamValidator::PARAM_ISMULTI_LIMIT2] ?? 100 ) > 10
			)
		) {
			$ret['issues'][] = 'Multi-valued title-type parameters with PARAM_RETURN_OBJECT '
				. 'should set low values (<= 10) for PARAM_ISMULTI_LIMIT1 and PARAM_ISMULTI_LIMIT2.'
				. ' (Note that "<= 10" is arbitrary. If something hits this, we can investigate a real limit '
				. 'once we have a real use case to look at.)';
		}

		return $ret;
	}

	/** @inheritDoc */
	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		$info['mustExist'] = !empty( $settings[self::PARAM_MUST_EXIST] );

		return $info;
	}

	/** @inheritDoc */
	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-title' );

		$mustExist = !empty( $settings[self::PARAM_MUST_EXIST] );
		$info[self::PARAM_MUST_EXIST] = $mustExist
			? MessageValue::new( 'paramvalidator-help-type-title-must-exist' )
			: MessageValue::new( 'paramvalidator-help-type-title-no-must-exist' );

		return $info;
	}

}
