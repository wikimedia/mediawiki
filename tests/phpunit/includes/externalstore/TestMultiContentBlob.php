<?php

namespace MediaWiki\ExternalStore;

class TestMultiContentBlob extends MultiContentBlob {

	public $metadata = null;
	public $items = [];
	public $size = 0;

	public function getSize() {
		return $this->size;
	}

	public function getCount() {
		return count( $this->items );
	}

	public function addItem( $text ) {
		$i = count( $this->items );
		$this->items[$i] = $text;
		return $i;
	}

	public function getItem( $i ) {
		return $this->items[$i];
	}

	protected function getData() {
		return [ $this->items, $this->metadata ];
	}

	protected function setData( array $data, array $metadata = null ) {
		$this->items = $data;
		$this->metadata = $metadata;
	}

}
