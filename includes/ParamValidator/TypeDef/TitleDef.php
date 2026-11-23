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
 *   was specified), or no page has the given ID (when PARAM_ALLOW_ID was specified).
 *   No data.
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

	/** Value for PARAM_RETURN_OBJECT: return a LinkTarget. */
	public const LINK_TARGET_VALUE = true;

	/**
	 * Value for PARAM_RETURN_OBJECT: return a TitleParam, which keeps the title as
	 * requested next to the page. With PARAM_ALLOW_ID, a page ID that matches no page
	 * does not fail validation (unless PARAM_MUST_EXIST is set): the TitleParam then
	 * has no page.
	 */
	public const TITLE_PARAM_VALUE = 'title-param-value';

	/**
	 * (bool|string) Whether to return an object.
	 *
	 * If LINK_TARGET_VALUE, a LinkTarget is returned. If TITLE_PARAM_VALUE, a TitleParam
	 * is returned. Otherwise, the validated title is returned as a string (in
	 * getPrefixedText() format), this is the default. For backwards compatibility,
	 * true is accepted and means the same as LINK_TARGET_VALUE.
	 *
	 * Avoid enabling this with PARAM_ISMULTI, as it may result in excessive DB
	 * lookups. If you do combine them, consider setting low values for
	 * PARAM_ISMULTI_LIMIT1 and PARAM_ISMULTI_LIMIT2 to mitigate it.
	 */
	public const PARAM_RETURN_OBJECT = 'param-return-object';

	/**
	 * (bool) Whether to allow specifying the page by ID, formatted like "#123".
	 *
	 * Defaults to false.
	 *
	 * Avoid combining with PARAM_ISMULTI, as it may result in excessive
	 * DB lookups. If you do combine them, consider setting low values for
	 * PARAM_ISMULTI_LIMIT1 and PARAM_ISMULTI_LIMIT2 to mitigate it.
	 */
	public const PARAM_ALLOW_ID = 'param-allow-id';

	/** @var TitleFactory */
	private $titleFactory;

	public function __construct( Callbacks $callbacks, TitleFactory $titleFactory ) {
		parent::__construct( $callbacks );
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @inheritDoc
	 * @return string|LinkTarget|TitleParam Depending on the PARAM_RETURN_OBJECT setting.
	 */
	public function validate( $name, $value, array $settings, array $options ) {
		$mustExist = !empty( $settings[self::PARAM_MUST_EXIST] );
		$returnObject = $settings[self::PARAM_RETURN_OBJECT] ?? false;
		$allowId = $settings[self::PARAM_ALLOW_ID] ?? false;
		$pageId = null;

		$this->failIfNotString( $name, $value, $settings, $options );

		if ( $allowId && preg_match( '/^#(\d+)$/D', $value, $m ) ) {
			$pageId = (int)$m[1];
			$title = $this->titleFactory->newFromID( $pageId );

			if ( !$title ) {
				if ( $returnObject === self::TITLE_PARAM_VALUE && !$mustExist ) {
					return new TitleParam( $value, null, $pageId );
				}
				// Message used: paramvalidator-missingtitle
				$this->failure( 'missingtitle', $name, $value, $settings, $options );
			}
		} else {
			$title = $this->titleFactory->newFromText( $value );

			if ( !$title ) {
				// Message used: paramvalidator-badtitle
				$this->failure( 'badtitle', $name, $value, $settings, $options );
			}
		}

		if ( $mustExist && !$title->exists() ) {
			// Message used: paramvalidator-missingtitle
			$this->failure( 'missingtitle', $name, $value, $settings, $options );
		}

		if ( $returnObject === self::TITLE_PARAM_VALUE ) {
			return new TitleParam( $value, $title->canExist() ? $title->toPageIdentity() : null, $pageId );
		} elseif ( $returnObject ) {
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
		if ( $value instanceof TitleParam ) {
			return $value->getText();
		}
		return parent::stringifyValue( $name, $value, $settings, $options );
	}

	/** @inheritDoc */
	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'][] = self::PARAM_MUST_EXIST;
		$ret['allowedKeys'][] = self::PARAM_RETURN_OBJECT;
		$ret['allowedKeys'][] = self::PARAM_ALLOW_ID;

		if ( !is_bool( $settings[self::PARAM_MUST_EXIST] ?? false ) ) {
			$ret['issues'][self::PARAM_MUST_EXIST] = 'PARAM_MUST_EXIST must be boolean, got '
				. gettype( $settings[self::PARAM_MUST_EXIST] );
		}

		$returnObject = $settings[self::PARAM_RETURN_OBJECT] ?? false;
		if ( !is_bool( $returnObject ) && $returnObject !== self::TITLE_PARAM_VALUE ) {
			$ret['issues'][self::PARAM_RETURN_OBJECT] = 'PARAM_RETURN_OBJECT must be '
				. 'LINK_TARGET_VALUE or TITLE_PARAM_VALUE, got ' . gettype( $returnObject );
		}

		if ( !is_bool( $settings[self::PARAM_ALLOW_ID] ?? false ) ) {
			$ret['issues'][self::PARAM_ALLOW_ID] = 'PARAM_ALLOW_ID must be boolean, got '
				. gettype( $settings[self::PARAM_ALLOW_ID] );
		}

		if ( !empty( $settings[ParamValidator::PARAM_ISMULTI] ) &&
			( !empty( $settings[self::PARAM_RETURN_OBJECT] ) || ( $settings[self::PARAM_ALLOW_ID] ?? false ) ) &&
			(
				( $settings[ParamValidator::PARAM_ISMULTI_LIMIT1] ?? 100 ) > 10 ||
				( $settings[ParamValidator::PARAM_ISMULTI_LIMIT2] ?? 100 ) > 10
			)
		) {
			$ret['issues'][] = 'Multi-valued title-type parameters with PARAM_RETURN_OBJECT or PARAM_ALLOW_ID '
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
		$info['allowId'] = $settings[self::PARAM_ALLOW_ID] ?? false;

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

		if ( $settings[self::PARAM_ALLOW_ID] ?? false ) {
			$info[self::PARAM_ALLOW_ID] = MessageValue::new( 'paramvalidator-help-type-title-allow-id' );
		}

		return $info;
	}

}
