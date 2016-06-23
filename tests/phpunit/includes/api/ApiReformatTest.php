<?php

/**
 * @group API
 *
 * @covers ApiReformat
 */
class ApiReformatTest extends ApiTestCase {

	public function testReformatWikitextWithDefaultParameters() {
		$this->assertEquals(
			[
				'serialization' => "fooo\n''bar''",
				'contentmodel' => 'wikitext',
				'contentformat' => 'text/x-wiki'
			],
			$this->doApiRequest( [
				'action' => 'reformat',
				'serialization' => "fooo\n''bar''",
				'inputcontentmodel' => 'wikitext'
			] )
		);
	}

	public function testReformatCustomWithSetParameters() {
		$this->addContentHandlerMock( 'text/x-wiki', 'a b', 'application/json', '{"a", "b"}' );
		$this->assertEquals(
			[
				'serialization' => 'a b',
				'contentmodel' => 'dual-format-content',
				'contentformat' => 'application/json',
			],
			$this->doApiRequest( [
				'action' => 'reformat',
				'serialization' => 'a b',
				'contentmodel' => 'dual-format-content',
				'inputcontentformat' => 'text/x-wiki',
				'outputcontentformat' => 'application/json'
			] )
		);
		$this->removeContentHandlerMock();
	}

	public function testReformatCustomWithDefaultInputFormatParameters() {
		$this->addContentHandlerMock( null, 'a b', 'application/json', '{"a", "b"}' );
		$this->assertEquals(
			[
				'serialization' => 'a b',
				'contentmodel' => 'dual-format-content',
				'contentformat' => 'application/json',
			],
			$this->doApiRequest( [
				'action' => 'reformat',
				'serialization' => 'a b',
				'contentmodel' => 'dual-format-content',
				'outputcontentformat' => 'application/json'
			] )
		);
		$this->removeContentHandlerMock();
	}

	public function testReformatCustomWithDefaultOuputFormatParameters() {
		$this->addContentHandlerMock( 'text/x-wiki', 'a b', null, '{"a", "b"}' );
		$this->assertEquals(
			[
				'serialization' => 'a b',
				'contentmodel' => 'dual-format-content',
				'contentformat' => 'application/json',
			],
			$this->doApiRequest( [
				'action' => 'reformat',
				'serialization' => 'a b',
				'contentmodel' => 'dual-format-content',
				'inputcontentformat' => 'text/x-wiki'
			] )
		);
		$this->removeContentHandlerMock();
	}

	private function addContentHandlerMock( $inputFormat, $inputSerialization, $outputFormat, $outputSerialization ) {
		global $wgContentHandlers;

		$tempContent = $this->getMock( 'Content' );

		$mock = $this->getMock( 'ContentHandler' );
		$mock->expects( $this->once() )
			->method( 'unserializeContent' )
			->with( $this->equalTo( $inputSerialization ), $this->equalTo( $inputFormat ) )
			->willReturn( $tempContent );
		$mock->expects( $this->once() )
			->method( 'serializeContent' )
			->with( $this->equalTo( $tempContent ), $this->equalTo( $outputFormat ) )
			->willReturn( $outputSerialization );
		
		$wgContentHandlers[ 'dual-format-content' ] = $mock;
	}

	private function removeContentHandlerMock() {
		global $wgContentHandlers;

		unset( $wgContentHandlers[ 'dual-format-content' ] );
	}
}
