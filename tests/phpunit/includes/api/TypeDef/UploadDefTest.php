<?php

namespace MediaWiki\Api\TypeDef;

use ApiMain;
use ApiUsageException;
use FauxRequest;
use MediaWikiLangTestCase;
use MockApi;
use RequestContext;
use WebRequestUpload;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\UploadDef
 */
class UploadDefTest extends MediaWikiLangTestCase {

	public function testGet() {
		$this->setMwGlobals( '_FILES', [
			'ttfoo' => [
				'name' => 'example.txt',
				'type' => 'text/plain',
				'size' => 0,
				'tmp_name' => $this->getNewTempFile(),
				'error' => UPLOAD_ERR_OK,
			],
		] );

		$api = new MockApi;
		$req = new FauxRequest( [ 'ttbar' => 'xyz' ] );
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain( $req );
		$w->mModulePrefix = 'tt';
		TestingAccessWrapper::newFromObject( $api )->mMainModule = new ApiMain( $req );

		$typeDef = new UploadDef;
		$this->assertInstanceOf( WebRequestUpload::class, $typeDef->get( 'foo', [], $api ) );
		$this->assertInstanceOf( WebRequestUpload::class, $typeDef->get( 'bar', [], $api ) );
		$this->assertNull( $typeDef->get( 'baz', [], $api ) );
	}

	/** @dataProvider provideValidate */
	public function testValidate( $name, $expect ) {
		$this->setMwGlobals( '_FILES', [
			'ttfoo' => [
				'name' => 'example.txt',
				'type' => 'text/plain',
				'size' => 0,
				'tmp_name' => $this->getNewTempFile(),
				'error' => UPLOAD_ERR_OK,
			],
		] );

		$api = new MockApi;
		$req = new FauxRequest( [ 'ttbar' => 'xyz' ] );
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain( $req );
		$w->mModulePrefix = 'tt';
		TestingAccessWrapper::newFromObject( $api )->mMainModule = new ApiMain( $req );

		$typeDef = new UploadDef;

		$value = $typeDef->get( $name, [], $api );
		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( $name, $value, [], $api );
		} else {
			$this->assertSame(
				$expect === true ? $value : $expect,
				$typeDef->validate( $name, $value, [], $api )
			);
			$this->assertEquals( [], $api->warnings );
		}
	}

	public static function provideValidate() {
		return [
			'Valid upload' => [ 'foo', true ],
			'Not an upload' => [ 'bar',
				ApiUsageException::newWithMessage( null, [ 'apierror-badupload', 'ttbar' ] ) ],
			'Nothing at all' => [ 'baz', null ],
		];
	}

	public function testGetHelpInfo() {
		$typeDef = new UploadDef;
		$this->assertSame(
			[
				'Must be posted as a file upload using multipart/form-data.',
			],
			$typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', [], new MockApi )
		);
	}

}
