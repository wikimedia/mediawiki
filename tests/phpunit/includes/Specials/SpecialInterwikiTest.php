<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialInterwiki;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialInterwiki
 */
class SpecialInterwikiTest extends SpecialPageTestBase {
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialInterwiki(
			$services->getContentLanguage(),
			$services->getInterwikiLookup(),
			$services->getLanguageNameUtils(),
			$services->getUrlUtils(),
			$services->getConnectionProvider(),
		);
	}

	private function populateDB( $iwrows ) {
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'interwiki' )
			->rows( $iwrows )
			->caller( __METHOD__ )->execute();
	}

	public static function provideModifyTable() {
		return [
			'add aaaawiki' => [
				'add',
				'aaaa',
				false,
				false,
				'http://aaaawiki.org/wiki/$1',
				'http://aaaawiki.org/w/api.php',
				[
					'iw_prefix' => 'aaaa',
					'iw_url' => 'http://aaaawiki.org/wiki/$1',
					'iw_api' => 'http://aaaawiki.org/w/api.php',
					'iw_wikiid' => '',
					'iw_local' => '0',
					'iw_trans' => '0'
				]
			],
			'edit zzzzwiki' => [
				'edit',
				'zzzz',
				true,
				true,
				'https://zzzzwiki.org/wiki/$1',
				'https://zzzzwiki.org/w/api.php',
				[
					'iw_prefix' => 'zzzz',
					'iw_url' => 'https://zzzzwiki.org/wiki/$1',
					'iw_api' => 'https://zzzzwiki.org/w/api.php',
					'iw_wikiid' => '',
					'iw_local' => '1',
					'iw_trans' => '1'
				]
			],
			'delete zzzzwiki' => [
				'delete',
				'zzzz',
				null,
				null,
				null,
				null,
				false
			]
		];
	}

	/**
	 * @dataProvider provideModifyTable
	 * @param string $action
	 * @param string $prefix
	 * @param bool|null $local
	 * @param bool|null $trans
	 * @param string|null $url
	 * @param string|null $api
	 * @param array|false $expected
	 */
	public function testModifyTable(
		$action,
		$prefix,
		$local = null,
		$trans = null,
		$url = null,
		$api = null,
		$expected = false
	) {
		$this->overrideConfigValue(
			MainConfigNames::GroupPermissions,
			wfArrayPlus2d(
				$this->getServiceContainer()->getMainConfig()->get( MainConfigNames::GroupPermissions ),
				[
					'sysop' => [
						'interwiki' => true,
					]
				]
			)
		);

		$zzzzwiki = [
			'iw_prefix' => 'zzzz',
			'iw_url' => 'http://zzzzwiki.org/wiki/$1',
			'iw_api' => 'http://zzzzwiki.org/w/api.php',
			'iw_wikiid' => '',
			'iw_local' => 0,
			'iw_trans' => 0
		];

		$this->populateDB( [ $zzzzwiki ] );

		$performer = $this->getTestSysop()->getUser();

		$formData = [
			'action' => $action,
			'prefix' => $prefix,
			'reason' => 'r',
		];
		if ( $action !== 'delete' ) {
			$formData['url'] = $url;
			$formData['api'] = $api;
			if ( $local ) {
				$formData['local'] = '1';
			}
			if ( $trans ) {
				$formData['trans'] = '1';
			}
		}

		$this->executeSpecialPage(
			$action,
			new FauxRequest( $formData, true ),
			null,
			$performer
		);

		$row = $this->getDb()->newSelectQueryBuilder()
			->select( [ 'iw_prefix', 'iw_url', 'iw_api', 'iw_wikiid', 'iw_local', 'iw_trans' ] )
			->from( 'interwiki' )
			->where( [ 'iw_prefix' => $prefix ] )
			->caller( __METHOD__ )
			->fetchRow();
		$this->assertSame( $expected, $row ? (array)$row : $row );
	}
}
