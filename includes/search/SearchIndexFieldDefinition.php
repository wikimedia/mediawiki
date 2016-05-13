<?php

/**
 * Field definition for search index.
 */
class SearchIndexFieldDefinition {

	/**
	 * Field types
	 */
	const INDEX_TYPE_TEXT = 0;
	const INDEX_TYPE_KEYWORD = 1;
	const INDEX_TYPE_INTEGER = 2;
	const INDEX_TYPE_NUMBER = 3;
	const INDEX_TYPE_GEOPOINT = 4;
	const INDEX_TYPE_DATETIME = 5;
	const INDEX_TYPE_NESTED = 6;

	const ALL_OPTIONS = '_all';

	/**
	 * Generic field flags.
	 */
	/**
	 * This field is case-insensitive
	 */
	const FLAG_CASEFOLD = 1;
	const FLAG_SECONDARY = 2;

	/**
	 * Name of the field
	 *
	 * @var string
	 */
	private $name;
	/**
	 * Type of the field, one of the constants above
	 *
	 * @var int
	 */
	private $type;
	/**
	 * Bit flags for the field.
	 *
	 * @var int
	 */
	private $flags = 0;
	/**
	 * Subfields
	 * @var SearchIndexFieldDefinition[]
	 */
	private $subfields = [];
	/**
	 * List of options per engine, in format:
	 * engine => options
	 * Key '_all' is applied to all engines.
	 * FIXME: I don't like the coupling that requires knowing engine names. Look for better solution.
	 *
	 * @var array[]
	 */
	private $options;

	/**
	 * SearchIndexFieldDefinition constructor.
	 * @param $name Field name
	 * @param $type Index type
	 */
	public function __construct( $name, $type ) {
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * Get field name
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get index type
	 * @return int
	 */
	public function getIndexType() {
		return $this->type;
	}

	/**
	 * Get options for specific search engine
	 *
	 * @param string $engine Search engine name
	 * @return array
	 */
	public function getEngineOptions( $engine ) {
		if ( !empty( $this->options[self::ALL_OPTIONS] ) ) {
			$options = $this->options[self::ALL_OPTIONS];
		} else {
			$options = [ ];
		}
		if ( !empty( $this->options[$engine] ) ) {
			$options = array_merge( $options, $this->options[$engine] );
		}
		return $options;
	}

	/**
	 * Set global flag for this field.
	 *
	 * @param int  $flag Bit flag to set/unset
	 * @param bool $unset True if flag should be unset, false by default
	 * @return $this
	 */
	public function setFlag( $flag, $unset = false ) {
		if ( $unset ) {
			$this->flags &= ~$flag;
		} else {
			$this->flags |= $flag;
		}
		return $this;
	}

	/**
	 * Check if flag is set.
	 * @param $flag
	 * @return int 0 if unset, !=0 if set
	 */
	public function checkFlag( $flag ) {
		return $this->flags & $flag;
	}

	/**
	 * Set engine-specific option for this field.
	 *
	 * @param string $engine
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function setEngineOption( $engine, $name, $value ) {
		$this->options[$engine][$name] = $value;
		return $this;
	}

	/**
	 * Merge two field definitions if possible.
	 *
	 * @param SearchIndexFieldDefinition $that
	 * @return SearchIndexFieldDefinition|false New definition or false if not mergeable.
	 */
	public function merge( SearchIndexFieldDefinition $that ) {
		// TODO: which definitions may be compatible?
		return false;
	}

	/**
	 * Get subfields
	 * @return SearchIndexFieldDefinition[]
	 */
	public function getSubfields() {
		return $this->subfields;
	}

	/**
	 * Set subfields
	 * @param SearchIndexFieldDefinition[] $subfields
	 * @return $this
	 */
	public function setSubfields( array $subfields ) {
		$this->subfields = $subfields;
		return $this;
	}
}
