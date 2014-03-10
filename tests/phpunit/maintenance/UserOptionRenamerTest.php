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
	public function testExecute( $old, $new, $batchSize ) {
		$this->initData( $old, $new );

		$GLOBALS['argv'] = array(
			'renameUserOption.php',
			'--quiet',
			'--batch-size',
			$batchSize,
			$old,
			$new
		);

		$renamer = new UserOptionRenamer();
		$renamer->loadParamsAndArgs();
		$renamer->execute();

		$this->assertEquals( 3, $this->countUsersWithOption( $new ), '3 users with new option' );
		$this->assertEquals( 0, $this->countUsersWithOption( $old ), 'no users with old option' );
	}

	public function provideExecute() {
		return array(
			array( 'favoritepage', 'favoritewikipage', 2 ),
			array( 'favoriteanimal', 'favoritearticle', 100 )
		);
	}

	/**
	 * @param string $old
	 * @param string $new
	 */
	private function initData( $old, $new ) {
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
			),
			array(
				'up_user' => 3,
				'up_property' => $old,
				'up_value' => 'Panda'
			),
			array(
				'up_user' => 1,
				'up_property' => $new,
				'up_value' => 'Kitten'
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
