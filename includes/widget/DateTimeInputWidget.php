<?php

namespace MediaWiki\Widget;

use InvalidArgumentException;
use OOUI\InputWidget;
use OOUI\Tag;

/**
 * Date-time input widget.
 *
 * @copyright 2016 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class DateTimeInputWidget extends InputWidget {

	/** @var string|null */
	protected $type = null;
	/** @var string|null */
	protected $min = null;
	/** @var string|null */
	protected $max = null;
	/** @var bool|null */
	protected $clearable = null;

	/**
	 * @param array $config Configuration options
	 *   - string $config['type'] 'date', 'time', or 'datetime'
	 *   - string $config['min'] Minimum date, time, or datetime
	 *   - string $config['max'] Maximum date, time, or datetime
	 *   - bool $config['clearable'] Whether to provide for blanking the value.
	 */
	public function __construct( array $config = [] ) {
		// We need $this->type set before calling the parent constructor
		if ( !isset( $config['type'] ) ) {
			throw new InvalidArgumentException( '$config[\'type\'] must be specified' );
		}
		$this->type = $config['type'];

		parent::__construct( $config );

		// Properties, which are ignored in PHP and just shipped back to JS
		if ( isset( $config['min'] ) ) {
			$this->min = $config['min'];
		}
		if ( isset( $config['max'] ) ) {
			$this->max = $config['max'];
		}
		if ( isset( $config['clearable'] ) ) {
			$this->clearable = $config['clearable'];
		}

		// Initialization
		$this->addClasses( [ 'mw-widgets-datetime-dateTimeInputWidget' ] );
		$this->appendContent( new PendingTextInputWidget() );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.datetime.DateTimeInputWidget';
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		$config['type'] = $this->type;
		if ( $this->min !== null ) {
			$config['min'] = $this->min;
		}
		if ( $this->max !== null ) {
			$config['max'] = $this->max;
		}
		if ( $this->clearable !== null ) {
			$config['clearable'] = $this->clearable;
		}
		return parent::getConfig( $config );
	}

	/** @inheritDoc */
	protected function getInputElement( $config ) {
		return ( new Tag( 'input' ) )->setAttributes( [ 'type' => $this->type ] );
	}
}
