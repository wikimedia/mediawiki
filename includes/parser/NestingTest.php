<?php

/**
 * @group Parser
 */
class NestingTest extends MediaWikiTestCase {

   function provideCases() {
       return [
           [ "<foo>123</foo>", "foo", "123", [] ],
           [ "<foo>foo</foo>", "foo", "foo", [] ],
           [ "A<foo>foo</Foo>B", "foo", "foo", [] ],
           [ "A<foo a=1 b='foo'>123<foo>456</foo>789</foo>B", "foo", "123<foo>456</foo>789", [ "a" => "1", "b" => "foo" ] ],
           [ "<foo<foo", "foo", null, null ],
           [
               "<ref name=\"citation:1\">" .
                       "<ref name=\"Other2001\"/>" .
                    "<ref name=\"YetAnother2004\"/>" .
                "</ref>",
                "ref", "<ref name=\"Other2001\"/><ref name=\"YetAnother2004\"/>", [ "name" => "citation:1" ]
           ],
           [ "<ref name='x' foo ><ref/></ref >", "ref", "<ref/>", [ "name" => "x", "foo" => "foo" ] ]
       ];
   }

   function provideNotWorkingCases() {
       return [
           [ "<foo a='<foo>'>123</foo>", "foo", null, null ],
           [ "<foo foo='123'>123<!--</foo>-->456</foo>", "foo", "123456", [ "foo" => "123" ] ],
       );
   }

   var $mInnerText;
   var $mParams;

   /**
    * @dataProvider provideCases
    */
   function testCases( $wikiText, $tag, $expectedInnerText, $expectedParams ) {
       global $wgParserConf;
       $parser = new Parser( $wgParserConf );

       $parser->setHook( $tag, [ $this, 'tagCallback' ] );
       $parserOutput = $parser->parse( $wikiText, Title::newFromText( 'Test' ), new ParserOptions );

       $this->assertEquals( $expectedInnerText, $this->mInnerText );
       $this->assertEquals( $expectedParams, $this->mParams );

       $parser->mPreprocessor = null; # Break the Parser <-> Preprocessor cycle
   }

   function tagCallback( $innerText, $params, $parser ) {
       $this->mInnerText = $innerText;
       $this->mParams = $params;

       return "dummy";
   }
}