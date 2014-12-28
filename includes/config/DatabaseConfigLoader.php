<?php

/**
 * Loads configuration settings from the database and registers
 * them
 */
class DatabaseConfigLoader {

	/**
	 * @var DatabaseBase
	 */
	private $db;

	private $loaded = false;
	private $settings = array();

	/**
	 * @param IDatabase $db
	 */
	public function __construct( IDatabase $db) {
		$this->db = $db;
	}

	public static function getDefaultInstance() {
		static $instance;
		if ( !$instance ) {
			$instance = new self( wfGetDB( DB_SLAVE ) );
		}

		return $instance;
	}

	/**
	 * @param string $name
	 * @return array
	 */
	public function get( $name ) {
		$this->load();
		return $this->settings[$name];
	}

	/**
	 * Bulk-load all config options for this wiki and register them
	 */
	protected function load() {
		if ( $this->loaded ) {
			return;
		}
		$rows = $this->db->select(
			'config',
			array( 'cf_group', 'cf_name', 'cf_value' ),
			array( 'cf_wiki' => wfWikiID() ),
			__METHOD__
		);

		foreach ( $rows as $row ) {
			$this->settings[$row->cf_group][$row->cf_name] = FormatJson::decode( $row->cf_value, true );
		}

		$this->loaded = true;
	}
}
