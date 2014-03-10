<?php

require_once __DIR__ . "/../../../maintenance/renameUserOption.php";

/**
 * @covers UserOptionRenamer
 * @group Database
 */
class UserOptionRenamerTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->tablesUsed[] = 'user_properties';
	}

	/**
	 * @dataProvider provideExecute
	 */
	public function testExecute( $old, $new ) {
		$this->initData( $old );

		$GLOBALS['argv'] = array( 'renameUserOption.php', '--quiet', $old, $new );

		$renamer = new UserOptionRenamer();
		$renamer->loadParamsAndArgs();
		$renamer->execute();

		$this->assertEquals( 2, $this->countUsersWithOption( $new ), 'one user with new option' );
		$this->assertEquals( 0, $this->countUsersWithOption( $old ), 'no users with old option' );
	}

	public function provideExecute() {
		return array(
			array( 'favoritepage', 'favoritearticle' )
		);
	}

	private function initData( $old ) {
		$values = array(
			array(
				'up_user' => 1,
				'up_property' => $old,
				'up_value' => 'Cat'
			),
			array(
				'up_user' => 2,
				'up_property' => $old,
				'up_value' => 'Dog'
			)
		);

		$this->db->insert( 'user_properties', $values );
	}

	/**
	 * @param string $option
	 */
	private function countUsersWithOption( $option ) {
		$result = $this->db->select(
			'user_properties',
			'up_user',
			array( 'up_property' => $option ),
			__METHOD__
		);

		$count = $result->numRows();

		return $count;
	}

}
