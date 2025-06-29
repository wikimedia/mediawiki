<?php

class ExternalStoreForTesting {

	/** @var array */
	protected $data = [
		'cluster1' => [
			'200' => 'Hello',
			'300' => [
				'Hello', 'World',
			],
			// gzip string below generated with gzdeflate( 'AAAABBAAA' )
			'12345' => "sttttr\002\022\000",
		],
	];

	/**
	 * Fetch data from given URL
	 * @param string $url An url of the form FOO://cluster/id or FOO://cluster/id/itemid.
	 * @return mixed
	 */
	public function fetchFromURL( $url ) {
		// Based on ExternalStoreDB
		$path = explode( '/', $url );
		$cluster = $path[2];
		$id = $path[3];
		$itemID = $path[4] ?? false;

		if ( !isset( $this->data[$cluster][$id] ) ) {
			return null;
		}

		if ( $itemID !== false
			&& is_array( $this->data[$cluster][$id] )
			&& isset( $this->data[$cluster][$id][$itemID] )
		) {
			return $this->data[$cluster][$id][$itemID];
		}

		return $this->data[$cluster][$id];
	}

	/** @inheritDoc */
	public function store( $location, $data ) {
		$itemId = mt_rand( 500, 1000 );
		$this->data[$location][$itemId] = $data;
		return "ForTesting://$location/$itemId";
	}

	/** @inheritDoc */
	public function isReadOnly() {
		return false;
	}

}
