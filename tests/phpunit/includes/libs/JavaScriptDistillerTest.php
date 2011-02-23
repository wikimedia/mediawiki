<?php
/**
 * @group Distiller
 */
class JavaScriptDistillerTest extends PHPUnit_Framework_TestCase {
	public function testDistiller() {
		$in = self::read( 'distiller-in.js' );
		$out = self::read( 'distiller-out.js' );
		$outFull = self::read( 'distiller-out-full.js' );
		$this->assertEquals( $out, JavaScriptDistiller::stripWhiteSpace( $in ) );
		$this->assertEquals( $outFull, JavaScriptDistiller::stripWhiteSpace( $in, true ) );
	}
	
	private static function read( $file ) {
		$text = file_get_contents( dirname( __FILE__ ) . "/$file" );
		return str_replace( "\r\n", "\n", $text );
	}
}