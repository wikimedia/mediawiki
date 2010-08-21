<?php
/**
 * PostgreSQL-specific updater.
 *
 * @file
 * @ingroup Deployment
 */
 
/**
 * Class for handling updates to Postgres databases.
 *
 * @todo FIXME: Postgres should use sequential updates like Mysql, Sqlite
 * and everybody else. It never got refactored like it should've. For now,
 * just wrap the old do_postgres_updates() in this class so we can clean up
 * the higher-level stuff.
 *
 * @ingroup Deployment
 * @since 1.17
 */

class PostgresUpdater extends DatabaseUpdater {
	protected function getCoreUpdateList() {
		return array();
	}

	public function doUpdates() {
		do_postgres_updates();
	}
}
