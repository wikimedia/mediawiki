<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

namespace Wikimedia\FileBackend\FileIteration;

use AppendIterator;
use FilterIterator;
use Iterator;
use Wikimedia\FileBackend\FileBackendStore;

/**
 * FileBackendStore helper function to handle listings that span container shards.
 * Do not use this class from places outside of FileBackendStore.
 *
 * @ingroup FileBackend
 */
abstract class FileBackendStoreShardListIterator extends FilterIterator {
	/** @var FileBackendStore */
	protected $backend;

	/** @var array */
	protected $params;

	/** @var string Full container name */
	protected $container;

	/** @var string Resolved relative path */
	protected $directory;

	/** @var array */
	protected $multiShardPaths = []; // (rel path => 1)

	/**
	 * @param FileBackendStore $backend
	 * @param string $container Full storage container name
	 * @param string $dir Storage directory relative to container
	 * @param array $suffixes List of container shard suffixes
	 * @param array $params
	 */
	public function __construct(
		FileBackendStore $backend, $container, $dir, array $suffixes, array $params
	) {
		$this->backend = $backend;
		$this->container = $container;
		$this->directory = $dir;
		$this->params = $params;

		$iter = new AppendIterator();
		foreach ( $suffixes as $suffix ) {
			$iter->append( $this->listFromShard( $this->container . $suffix ) );
		}

		parent::__construct( $iter );
	}

	public function accept(): bool {
		$inner = $this->getInnerIterator();
		'@phan-var AppendIterator $inner';
		$rel = $inner->current(); // path relative to given directory
		$path = $this->params['dir'] . "/{$rel}"; // full storage path
		if ( $this->backend->isSingleShardPathInternal( $path ) ) {
			return true; // path is only on one shard; no issue with duplicates
		} elseif ( isset( $this->multiShardPaths[$rel] ) ) {
			// Don't keep listing paths that are on multiple shards
			return false;
		} else {
			$this->multiShardPaths[$rel] = 1;

			return true;
		}
	}

	public function rewind(): void {
		parent::rewind();
		$this->multiShardPaths = [];
	}

	/**
	 * Get the list for a given container shard
	 *
	 * @param string $container Resolved container name
	 * @return Iterator
	 */
	abstract protected function listFromShard( $container );
}

/** @deprecated class alias since 1.43 */
class_alias( FileBackendStoreShardListIterator::class, 'FileBackendStoreShardListIterator' );
