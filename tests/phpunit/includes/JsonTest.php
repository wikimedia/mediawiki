<?php

class JsonTest extends MediaWikiTestCase {
	
	function testPHPBug46944Test() {
		
		$this->assertNotEquals( 
			'\ud840\udc00',			
			strtolower( FormatJson::encode( "\xf0\xa0\x80\x80" ) ),
			'Test encoding an broken json_encode character (U+20000)'
		);
		
		
	}
	
	function testDecodeVarTypes() {
		
		$this->assertInternalType( 
			'object',			
			FormatJson::decode( '{"Name": "Cheeso", "Rank": 7}' ),
			'Default to object'
		);
		
		$this->assertInternalType( 
			'array',			
			FormatJson::decode( '{"Name": "Cheeso", "Rank": 7}', true ),
			'Optional array'
		);
		
	}
	
}

