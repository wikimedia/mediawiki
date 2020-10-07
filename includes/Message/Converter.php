<?php

namespace MediaWiki\Message;

use InvalidArgumentException;
use Message;
use ReflectionClass;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * Converter between Message and MessageValue
 * @since 1.35
 */
class Converter {

	/** @var string[]|null ParamType constants */
	private static $constants = null;

	/**
	 * Return the ParamType constants
	 * @return string[]
	 */
	private static function getTypes() {
		if ( self::$constants === null ) {
			$rc = new ReflectionClass( ParamType::class );
			self::$constants = array_values( $rc->getConstants() );
		}

		return self::$constants;
	}

	/**
	 * Allow the Message class to be mocked in tests by constructing objects in
	 * a protected method.
	 *
	 * @internal
	 * @param string $key
	 * @return Message
	 */
	public function createMessage( $key ) {
		return new Message( $key );
	}

	/**
	 * Convert a Message to a MessageValue
	 * @param Message $m
	 * @return MessageValue
	 */
	public function convertMessage( Message $m ) {
		$mv = new MessageValue( $m->getKey() );
		foreach ( $m->getParams() as $param ) {
			$mv->params( $this->convertParam( $param ) );
		}
		return $mv;
	}

	/**
	 * Convert a Message parameter to a MessageParam
	 * @param array|string|int $param
	 * @return MessageParam
	 */
	private function convertParam( $param ) {
		if ( $param instanceof Message ) {
			return new ScalarParam( ParamType::TEXT, $this->convertMessage( $param ) );
		}
		if ( !is_array( $param ) ) {
			return new ScalarParam( ParamType::TEXT, $param );
		}

		if ( isset( $param['list'] ) && isset( $param['type'] ) ) {
			$convertedElements = [];
			foreach ( $param['list'] as $element ) {
				$convertedElements[] = $this->convertParam( $element );
			}
			return new ListParam( $param['type'], $convertedElements );
		}

		foreach ( self::getTypes() as $type ) {
			if ( $type !== ParamType::LIST && isset( $param[$type] ) ) {
				return new ScalarParam( $type, $param[$type] );
			}
		}

		throw new InvalidArgumentException( "Unrecognized Message param: " . json_encode( $param ) );
	}

	/**
	 * Convert a MessageValue to a Message
	 * @param MessageValue $mv
	 * @return Message
	 */
	public function convertMessageValue( MessageValue $mv ) {
		$m = $this->createMessage( $mv->getKey() );
		foreach ( $mv->getParams() as $param ) {
			$m->params( $this->convertMessageParam( $param ) );
		}
		return $m;
	}

	/**
	 * Convert a MessageParam to a Message parameter
	 * @param MessageParam $param
	 * @return array|string|int
	 */
	private function convertMessageParam( MessageParam $param ) {
		if ( $param instanceof ListParam ) {
			$convertedElements = [];
			foreach ( $param->getValue() as $element ) {
				$convertedElements[] = $this->convertMessageParam( $element );
			}
			return Message::listParam( $convertedElements, $param->getListType() );
		}
		$value = $param->getValue();
		if ( $value instanceof MessageValue ) {
			$value = $this->convertMessageValue( $value );
		}

		if ( $param->getType() === ParamType::TEXT ) {
			return $value;
		}
		return [ $param->getType() => $value ];
	}

}
