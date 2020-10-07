<?php

namespace Wikimedia\Rdbms;

/**
 * Helper class to handle automatically marking connections as reusable (via RAII pattern)
 * as well handling deferring the actual network connection until the handle is used
 *
 * @note: proxy methods are defined explicity to avoid interface errors
 * @ingroup Database
 * @since 1.29
 */
class MaintainableDBConnRef extends DBConnRef implements IMaintainableDatabase {
	public function tableName( $name, $format = 'quoted' ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function tableNames( ...$tables ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function tableNamesN( ...$tables ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function sourceFile(
		$filename,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = false,
		callable $inputCallback = null
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function sourceStream(
		$fp,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = __METHOD__,
		callable $inputCallback = null
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function dropTable( $table, $fname = __METHOD__ ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function truncate( $tables, $fname = __METHOD__ ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function deadlockLoop( ...$args ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function listViews( $prefix = null, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function textFieldSize( $table, $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function streamStatementEnd( &$sql, &$newLine ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function tableLocksHaveTransactionScope() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function lockTables( array $read, array $write, $method ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function unlockTables( $method ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function indexUnique( $table, $index, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function listTables( $prefix = null, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function fieldInfo( $table, $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}
}

/**
 * @deprecated since 1.33
 */
class_alias( MaintainableDBConnRef::class, 'MaintainableDBConnRef' );
