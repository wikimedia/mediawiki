<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\Jobs\HTMLCacheUpdateJob;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserOutput;

/**
 * page_props
 *
 * Link ID format: string[]
 *   0: Property name (pp_propname)
 *   1: Property value (pp_value)
 *
 * @since 1.38
 */
class PagePropsTable extends LinksTable {
	/** @var JobQueueGroup */
	private $jobQueueGroup;

	/** @var array */
	private $newProps = [];

	/** @var array|null */
	private $existingProps;

	/**
	 * The configured PagePropLinkInvalidations. An associative array where the
	 * key is the property name and the value is a string or array of strings
	 * giving the link table names which will be used for backlink cache
	 * invalidation.
	 *
	 * @var array
	 */
	private $linkInvalidations;

	public const CONSTRUCTOR_OPTIONS = [ MainConfigNames::PagePropLinkInvalidations ];

	public function __construct(
		ServiceOptions $options,
		JobQueueGroup $jobQueueGroup
	) {
		$this->jobQueueGroup = $jobQueueGroup;
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->linkInvalidations = $options->get( MainConfigNames::PagePropLinkInvalidations );
	}

	public function setParserOutput( ParserOutput $parserOutput ) {
		$this->newProps = $parserOutput->getPageProperties();
	}

	protected function getTableName() {
		return 'page_props';
	}

	protected function getFromField() {
		return 'pp_page';
	}

	protected function getExistingFields() {
		return [ 'pp_propname', 'pp_value' ];
	}

	protected function getNewLinkIDs() {
		foreach ( $this->newProps as $name => $value ) {
			yield [ (string)$name, $value ];
		}
	}

	/**
	 * Get the existing page_props as an associative array
	 *
	 * @return array
	 */
	private function getExistingProps() {
		if ( $this->existingProps === null ) {
			$this->existingProps = [];
			foreach ( $this->fetchExistingRows() as $row ) {
				$this->existingProps[$row->pp_propname] = $row->pp_value;
			}
		}
		return $this->existingProps;
	}

	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingProps() as $name => $value ) {
			yield [ (string)$name, $value ];
		}
	}

	protected function isExisting( $linkId ) {
		$existing = $this->getExistingProps();
		[ $name, $value ] = $linkId;
		return \array_key_exists( $name, $existing )
			&& $this->encodeValue( $existing[$name] ) === $this->encodeValue( $value );
	}

	protected function isInNewSet( $linkId ) {
		[ $name, $value ] = $linkId;
		return \array_key_exists( $name, $this->newProps )
			&& $this->encodeValue( $this->newProps[$name] ) === $this->encodeValue( $value );
	}

	/**
	 * @param mixed $value
	 */
	private function encodeValue( $value ): string {
		if ( is_bool( $value ) ) {
			return (string)(int)$value;
		} elseif ( $value === null ) {
			return '';
		} else {
			return (string)$value;
		}
	}

	protected function insertLink( $linkId ) {
		[ $name, $value ] = $linkId;
		$this->insertRow( [
			'pp_propname' => $name,
			'pp_value' => $this->encodeValue( $value ),
			'pp_sortkey' => $this->getPropertySortKeyValue( $value )
		] );
	}

	/**
	 * Determines the sort key for the given property value.
	 * This will return $value if it is a float or int,
	 * 1 or resp. 0 if it is a bool, and null otherwise.
	 *
	 * @note In the future, we may allow the sortkey to be specified explicitly
	 *       in ParserOutput::setPageProperty (T357783).
	 *
	 * @param mixed $value
	 *
	 * @return float|null
	 */
	private function getPropertySortKeyValue( $value ) {
		if ( is_int( $value ) || is_float( $value ) || is_bool( $value ) ) {
			return floatval( $value );
		}

		return null;
	}

	protected function deleteLink( $linkId ) {
		$this->deleteRow( [
			'pp_propname' => $linkId[0]
		] );
	}

	protected function finishUpdate() {
		$changed = array_unique( array_merge(
			array_column( $this->insertedLinks, 0 ),
			array_column( $this->deletedLinks, 0 ) ) );
		$this->invalidateProperties( $changed );
	}

	/**
	 * Invalidate the properties given the list of changed property names
	 *
	 * @param string[] $changed
	 */
	private function invalidateProperties( array $changed ) {
		$jobs = [];
		foreach ( $changed as $name ) {
			if ( isset( $this->linkInvalidations[$name] ) ) {
				$inv = $this->linkInvalidations[$name];
				if ( !is_array( $inv ) ) {
					$inv = [ $inv ];
				}
				foreach ( $inv as $table ) {
					$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
						$this->getSourcePage(),
						$table,
						[ 'causeAction' => 'page-props' ]
					);
				}
			}
		}

		if ( $jobs ) {
			$this->jobQueueGroup->lazyPush( $jobs );
		}
	}

	/**
	 * Get the properties for a given link set as an associative array
	 *
	 * @param int $setType The set type as in LinksTable::getLinkIDs()
	 * @return array
	 */
	public function getAssocArray( $setType ) {
		$props = [];
		foreach ( $this->getLinkIDs( $setType ) as [ $name, $value ] ) {
			$props[$name] = $value;
		}
		return $props;
	}
}
