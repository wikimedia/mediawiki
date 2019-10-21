<?php

namespace MediaWiki\Message;

use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Message;

/**
 * The MediaWiki-specific implementation of ITextFormatter
 */
class TextFormatter implements ITextFormatter {
	/** @var string */
	private $langCode;

	/**
	 * Construct a TextFormatter.
	 *
	 * The type signature may change without notice as dependencies are added
	 * to the constructor. External callers should use
	 * MediaWikiServices::getMessageFormatterFactory()
	 *
	 * @internal
	 */
	public function __construct( $langCode ) {
		$this->langCode = $langCode;
	}

	/**
	 * Allow the Message class to be mocked in tests by constructing objects in
	 * a protected method.
	 *
	 * @internal
	 * @param string $key
	 * @return Message
	 */
	protected function createMessage( $key ) {
		return new Message( $key );
	}

	public function getLangCode() {
		return $this->langCode;
	}

	private function convertParam( MessageParam $param ) {
		if ( $param instanceof ListParam ) {
			$convertedElements = [];
			foreach ( $param->getValue() as $element ) {
				$convertedElements[] = $this->convertParam( $element );
			}
			return Message::listParam( $convertedElements, $param->getListType() );
		} elseif ( $param instanceof MessageParam ) {
			$value = $param->getValue();
			if ( $value instanceof MessageValue ) {
				$mv = $value;
				$value = $this->createMessage( $mv->getKey() );
				foreach ( $mv->getParams() as $mvParam ) {
					$value->params( $this->convertParam( $mvParam ) );
				}
			}

			if ( $param->getType() === ParamType::TEXT ) {
				return $value;
			} else {
				return [ $param->getType() => $value ];
			}
		} else {
			throw new \InvalidArgumentException( 'Invalid message parameter type' );
		}
	}

	public function format( MessageValue $mv ) {
		$message = $this->createMessage( $mv->getKey() );
		foreach ( $mv->getParams() as $param ) {
			$message->params( $this->convertParam( $param ) );
		}
		$message->inLanguage( $this->langCode );
		return $message->text();
	}
}
