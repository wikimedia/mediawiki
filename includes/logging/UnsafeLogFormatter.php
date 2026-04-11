<?php

namespace MediaWiki\Logging;

use LogEntry;
use LogFormatter;
use MediaWiki\Logger\LoggerFactory;

class UnsafeLogFormatter extends LogFormatter {

	public function __construct( LogEntry $entry ) {
		parent::__construct( $entry );

		$params = [ $entry->getParameters() ];
		array_walk_recursive( $params, static function ( $val ) {
			if ( $val instanceof \__PHP_Incomplete_Class ) {
				// Despite being documented, the '__PHP_Incomplete_Class_Name' property can't be accessed,
				// because '__PHP_Incomplete_Class' disallows accessing any of its properties.
				// This works though…
				$className = ( (array)$val )['__PHP_Incomplete_Class_Name'];
				$logger = LoggerFactory::getInstance( 'unsafe-logentry' );
				if ( class_exists( $className ) ) {
					$logger->error( "Log entry params contain forbidden class '$className'" );
				} else {
					// This may be reached after uninstalling an extension that previously allowed a class
					$logger->warning( "Log entry params contain non-existent class '$className'" );
				}
			}
		} );
	}

	/** @inheritDoc */
	protected function getActionMessage() {
		return $this->getPerformerElement() .
			$this->msg( 'word-separator' )->escaped() .
			$this->msg( 'log-unknown-action',
				$this->entry->getTarget()->getPrefixedText(),
				$this->entry->getFullType()
			)->parse() .
			$this->msg( 'word-separator' )->escaped() .
			$this->msg( 'log-unsafe-logentry' )->escaped();
	}

	// Override functions which may access parameters to avoid exceptions and warnings elsewhere

	/** @inheritDoc */
	protected function getMessageKey() {
		return "log-unsafe-logentry";
	}

	/** @inheritDoc */
	protected function getMessageParameters() {
		return [];
	}

	/** @inheritDoc */
	protected function getParametersForApi() {
		return [];
	}

	/** @inheritDoc */
	public function getIRCActionText() {
		return '';
	}

	/** @inheritDoc */
	protected function extractParameters() {
		return [];
	}

}
