<?php

use MatthiasMullie\Minify;

/**
 * JS minifier test case.
 */
class JSTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Minify\JS
     */
    private $minifier;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // override save method, there's no point in writing the result out here
        $this->minifier = $this->getMockBuilder('\MatthiasMullie\Minify\JS')
            ->setMethods(array('save'))
            ->getMock();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->minifier = null;
        parent::tearDown();
    }

    /**
     * Test JS minifier rules, provided by dataProvider.
     *
     * @test
     * @dataProvider dataProvider
     */
    public function minify($input, $expected)
    {
        $this->minifier->add($input);
        $result = $this->minifier->minify();

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array [input, expected result]
     */
    public function dataProvider()
    {
        $tests = array();

        // adding multiple files
        $tests[] = array(
            [
                __DIR__.'/sample/source/script1.js',
                __DIR__.'/sample/source/script2.js',
            ],
            'var test=1;var test=2',
        );

        // adding multiple files and string
        $tests[] = array(
            [
                __DIR__.'/sample/source/script1.js',
                'console.log(test)',
                __DIR__.'/sample/source/script2.js',
            ],
            'var test=1;console.log(test);var test=2',
        );

        // escaped quotes should not terminate string
        $tests[] = array(
            'alert("Escaped quote which is same as string quotes: \"; should not match")',
            'alert("Escaped quote which is same as string quotes: \"; should not match")',
        );

        // backtick string (allow string interpolation)
        $tests[] = array(
            'var str=`Hi, ${name}`',
            'var str=`Hi, ${name}`',
        );

        // regex delimiters need to be treated as strings
        // (two forward slashes could look like a comment)
        $tests[] = array(
            '/abc\/def\//.test("abc")',
            '/abc\/def\//.test("abc")',
        );
        $tests[] = array(
            '/abc\/def\//.test("abc\/def\/")',
            '/abc\/def\//.test("abc\/def\/")',
        );
        $tests[] = array(
            // there's an escape mess here; below regex represent this JS line:
            // /abc\/def\\\//.test("abc/def\\/")
            '/abc\/def\\\\\//.test("abc/def\\\/")',
            '/abc\/def\\\\\//.test("abc/def\\\/")',
        );
        $tests[] = array(
            // escape mess, this represents:
            // /abc\/def\\\\\//.test("abc/def\\\\/")
            '/abc\/def\\\\\\\\\//.test("abc/def\\\\\\\\/")',
            '/abc\/def\\\\\\\\\//.test("abc/def\\\\\\\\/")',
        );
        $tests[] = array(
            'var a = /abc\/def\//.test("abc")',
            'var a=/abc\/def\//.test("abc")',
        );

        // don't confuse multiple slashes for regexes
        $tests[] = array(
            'a = b / c; d = e / f',
            'a=b/c;d=e/f',
        );
        $tests[] = array(
            '(2 + 4) / 3 + 5 / 1',
            '(2+4)/3+5/1',
        );

        $tests[] = array(
            'a=4/
            2',
            'a=4/2',
        );

        // mixture of quotes starting in comment/regex, to make sure strings are
        // matched correctly, not inside comment/regex
        // additionally test catching of empty strings as well
        $tests[] = array(
            '/abc"def/.test("abc")',
            '/abc"def/.test("abc")',
        );
        $tests[] = array(
            '/abc"def/.test(\'\')',
            '/abc"def/.test(\'\')',
        );

        $tests[] = array(
            '/* Bogus " */var test="test";',
            'var test="test"',
        );

        // replace comments
        $tests[] = array(
            '/* This is a JS comment */',
            '',
        );

        // make sure no ; is added in places it shouldn't
        $tests[] = array(
            'if(true){}else{}',
            'if(!0){}else{}',
        );
        $tests[] = array(
            'do{i++}while(i<1)',
            'do{i++}while(i<1)',
        );

        $tests[] = array(
            'if(true)statement;else statement',
            'if(!0)statement;else statement',
        );

        $tests[] = array(
          'for ( i = 0; ; i++ ) statement',
          'for(i=0;;i++)statement',
        );
        $tests[] = array(
            'for (i = 0; (i < 10); i++) statement',
            'for(i=0;(i<10);i++)statement',
        );
        $tests[] = array(
          'alert("test");;alert("test2")',
          'alert("test");alert("test2")',
        );
        $tests[] = array(
            '-1
             +2',
            '-1+2',
        );
        $tests[] = array(
            '-1+
             2',
            '-1+2',
        );
        $tests[] = array(
            'alert("this is a test");',
            'alert("this is a test")',
        );

        // test where newline should be preserved (for ASI) or semicolon added
        $tests[] = array(
            'function(){console.log("this is a test");}',
            'function(){console.log("this is a test")}',
        );
        $tests[] = array(
            'alert("this is a test")
alert("this is another test")',
            'alert("this is a test")
alert("this is another test")',
        );
        $tests[] = array(
            'a=b+c
             d=e+f',
            'a=b+c
d=e+f',
        );
        $tests[] = array(
            'a++

             ++b',
            'a++
++b',
        );
        $tests[] = array(
            '!a
             !b',
            '!a
!b',
        );
        $tests[] = array(
            // don't confuse with 'if'
            'digestif
            (true)
            statement',
            'digestif(!0)
statement',
        );
        $tests[] = array(
            'if
             (
                 (
                     true
                 )
                 &&
                 (
                     true
                 )
            )
            statement',
            'if((!0)&&(!0))
statement',
        );
        $tests[] = array(
            'if
             (
                 true
             )
             {
             }
             else
             {
             }',
            'if(!0){}else{}',
        );
        $tests[] = array(
            'do
             {
                 i++
             }
             while
             (
                 i<1
             )',
            'do{i++}
while(i<1)',
        );
        $tests[] = array(
            'if ( true )
                 statement
             else
                 statement',
            'if(!0)
statement
else statement',
        );

        // test if whitespace around keywords is properly collapsed
        $tests[] = array(
            'var
             variable
             =
             "value";',
            'var variable="value"',
        );
        $tests[] = array(
            'var variable = {
                 test:
                 {
                 }
             }',
            'var variable={test:{}}',
        );
        $tests[] = array(
            'if ( true ) {
             } else {
             }',
            'if(!0){}else{}',
        );
        $tests[] = array(
            '53  instanceof  String',
            '53 instanceof String',
        );

        // remove whitespace around operators
        $tests[] = array(
            'a = 1 + 2',
            'a=1+2',
        );
        $tests[] = array(
            'object  .  property',
            'object.property',
        );
        $tests[] = array(
            'object
                .property',
            'object.property',
        );
        $tests[] = array(
            'alert ( "this is a test" );',
            'alert("this is a test")',
        );

        // mix of ++ and +: three consecutive +es will be interpreted as ++ +
        $tests[] = array(
            'a++ +b',
            'a++ +b',
        );
        $tests[] = array(
            'a+ ++b',
            'a+ ++b', // +++ would actually be allowed as well
        );

        // SyntaxError: identifier starts immediately after numeric literal
        $tests[] = array(
            '42 .toString()',
            '42 .toString()',
        );

        // add comment in between whitespace that needs to be stripped
        $tests[] = array(
            'object
                // haha, some comment, just to make things harder!
                .property',
            'object.property',
        );

        // add comment in between whitespace that needs to be stripped
        $tests[] = array(
            'var test=true,test2=false',
            'var test=!0,test2=!1',
        );
        $tests[] = array(
            'var testtrue="testing if true as part of varname is ignored as it should"',
            'var testtrue="testing if true as part of varname is ignored as it should"',
        );

        // random bits of code that tripped errors during development
        $tests[] = array(
            '
                // check if it isn\'t a text-element
                if(currentElement.attr(\'type\') != \'text\')
                {
                    // remove the current one
                    currentElement.remove();
                }

                // already a text element
                else newElement = currentElement;
',
            'if(currentElement.attr(\'type\')!=\'text\'){currentElement.remove()}else newElement=currentElement',
        );
        $tests[] = array(
            'var jsBackend =
             {
                 debug: false,
                 current: {}
             }',
            'var jsBackend={debug:!1,current:{}}',
        );
        $tests[] = array(
            'var utils =
             {
                 debug: false
             }
             utils.array =
             {
             }',
            'var utils={debug:!1}
utils.array={}',
        );
        $tests[] = array(
            'rescape = /\'|\\\\/g,

            // blablabla here was some more code but the point was that somewhere
            // down below, there would be a closing quote which would cause the
            // regex (confused for escaped closing tag) not to be recognized,
            // taking the opening single quote & looking for a string.
            // So here\'s <-- the closing quote
            runescape = \'blabla\'',
            'rescape=/\'|\\\\/g,runescape=\'blabla\'',
        );
        $tests[] = array(
            'var rsingleTag = (/^<(\w+)\s*\/?>(?:<\/\1>|)$/)',
            'var rsingleTag=(/^<(\w+)\s*\/?>(?:<\/\1>|)$/)',
        );
        $tests[] = array(
            'if (this.sliding)       return this.$element.one(\'slid.bs.carousel\', function () { that.to(pos) }) // yes, "slid"
if (activeIndex == pos) return this.pause().cycle()',
            'if(this.sliding)return this.$element.one(\'slid.bs.carousel\',function(){that.to(pos)})
if(activeIndex==pos)return this.pause().cycle()',
        );
        $tests[] = array(
            'if (e.which == 38 && index > 0)                 index--                        // up
if (e.which == 40 && index < $items.length - 1) index++                        // down',
            'if(e.which==38&&index>0)index--
if(e.which==40&&index<$items.length-1)index++',
        );

        // replace associative array key references by property notation
        $tests[] = array(
            'array["key"][\'key2\']',
            'array.key.key2',
        );
        $tests[] = array(
            'array[ "key" ][ \'key2\' ]',
            'array.key.key2',
        );
        $tests[] = array(
            'array["a","b","c"]',
            'array["a","b","c"]',
        );

        $tests[] = array(
            "['loader']",
            "['loader']",
        );
        $tests[] = array(
            'array["dont-replace"][\'key2\']',
            'array["dont-replace"].key2',
        );

        // shorten bools
        $tests[] = array(
            'while(true){break}',
            'for(;;){break}',
        );
        // make sure we don't get "missing while after do-loop body"
        $tests[] = array(
            'do{break}while(true)',
            'do{break}while(!0)',
        );
        $tests[] = array(
            "do break\nwhile(true)",
            "do break\nwhile(!0)",
        );
        $tests[] = array(
            "do{break}while(true){alert('test')}",
            "do{break}while(!0){alert('test')}",
        );
        $tests[] = array(
            "do break\nwhile(true){alert('test')}",
            "do break\nwhile(!0){alert('test')}",
        );
        // nested do-while & while
        $tests[] = array(
            "do{while(true){break}break}while(true){alert('test')}",
            "do{for(;;){break}break}while(!0){alert('test')}",
        );
        $tests[] = array(
            "do{while(true){break}break}while(true){alert('test')}while(true){break}",
            "do{for(;;){break}break}while(!0){alert('test')}for(;;){break}",
        );
        $tests[] = array(
            "do{while(true){break}break}while(true){alert('test')}while(true){break}do{while(true){break}break}while(true){alert('test')}while(true){break}",
            "do{for(;;){break}break}while(!0){alert('test')}for(;;){break}do{for(;;){break}break}while(!0){alert('test')}for(;;){break}",
        );

        // https://github.com/matthiasmullie/minify/issues/10
        $tests[] = array(
            '// first mutation patch
// second mutation patch
// third mutation patch
// fourth mutation patch',
            '',
        );
        $tests[] = array(
            '/////////////////////////
// first mutation patch
// second mutation patch
// third mutation patch
// fourth mutation patch
/////////////////////////',
            '',
        );

        // https://github.com/matthiasmullie/minify/issues/14
        $tests[] = array(
            'function foo (a, b)
{
    return a / b;
}
function foo (a, b)
{
    return a / b;
}',
            'function foo(a,b){return a/b}
function foo(a,b){return a/b}',
        );

        // https://github.com/matthiasmullie/minify/issues/15
        $tests[] = array(
            'if ( !data.success )
    deferred.reject(); else
    deferred.resolve(data);',
            'if(!data.success)
deferred.reject();else deferred.resolve(data)',
        );
        $tests[] = array(
            "if ( typeof jQuery === 'undefined' )
    throw new Error('.editManager.js: jQuery is required and must be loaded first');",
            "if(typeof jQuery==='undefined')
throw new Error('.editManager.js: jQuery is required and must be loaded first')",
        );

        // https://github.com/matthiasmullie/minify/issues/27
        $tests[] = array(
            '$.expr[":"]',
            '$.expr[":"]',
        );

        // https://github.com/matthiasmullie/minify/issues/31
        $tests[] = array(
            "$(_this).attr('src',this.src).trigger('adapt',['loader'])",
            "$(_this).attr('src',this.src).trigger('adapt',['loader'])",
        );

        // https://github.com/matthiasmullie/minify/issues/33
        $tests[] = array(
            '$.fn.alert             = Plugin
$.fn.alert.Constructor = Alert',
            '$.fn.alert=Plugin
$.fn.alert.Constructor=Alert',
        );

        // https://github.com/matthiasmullie/minify/issues/34
        $tests[] = array(
            'a.replace("\\\\","");hi="This   is   a   string"',
            'a.replace("\\\\","");hi="This   is   a   string"',
        );

        // https://github.com/matthiasmullie/minify/issues/35
        $tests[] = array(
            array(
                '// script that ends with comment',
                'var test=1',
            ),
            'var test=1',
        );

        // https://github.com/matthiasmullie/minify/issues/37
        $tests[] = array(
            'function () { ;;;;;;;; }',
            'function(){}',
        );

        // https://github.com/matthiasmullie/minify/issues/40
        $tests[] = array(
            'for(v=1,_=b;;){}',
            'for(v=1,_=b;;){}',
        );

        // https://github.com/matthiasmullie/minify/issues/41
        $tests[] = array(
            "conf.zoomHoverIcons['default']",
            "conf.zoomHoverIcons['default']",
        );

        // https://github.com/matthiasmullie/minify/issues/42
        $tests[] = array(
            'for(i=1;i<2;i++);',
            'for(i=1;i<2;i++);',
        );
        $tests[] = array(
            'if(1){for(i=1;i<2;i++);}',
            'if(1){for(i=1;i<2;i++);}',
        );
        $tests[] = array(
            'for(i in list);',
            'for(i in list);',
        );
        $tests[] = array(
            'if(1){for(i in list);}',
            'if(1){for(i in list);}',
        );

        // https://github.com/matthiasmullie/minify/issues/43
        $tests[] = array(
            '{"key":"3","key2":"value","key3":"3"}',
            '{"key":"3","key2":"value","key3":"3"}',
        );

        // https://github.com/matthiasmullie/minify/issues/44
        $tests[] = array(
            'return ["x"]',
            'return["x"]',
        );

        // https://github.com/matthiasmullie/minify/issues/50
        $tests[] = array(
            'do{var dim=this._getDaysInMonth(year,month-1);if(day<=dim){break}month++;day-=dim}while(true)}',
            'do{var dim=this._getDaysInMonth(year,month-1);if(day<=dim){break}month++;day-=dim}while(!0)}',
        );

        // https://github.com/matthiasmullie/minify/issues/53
        $tests[] = array(
            'a.validator.addMethod("accept", function (b, c, d) {
    var e, f, g = "string" == typeof d ?
        d.replace(/\s/g, "").replace(/,/g, "|") :
        "image/*", h = this.optional(c);
    if (h)return h;
    if ("file" === a(c).attr("type") && (g = g.replace(/\*/g, ".*"), c.files && c.files.length))
        for (e = 0; e < c.files.length; e++)
            if (f = c.files[e], !f.type.match(new RegExp(".?(" + g + ")$", "i")))
                return !1;
    return !0
}',
            'a.validator.addMethod("accept",function(b,c,d){var e,f,g="string"==typeof d?d.replace(/\s/g,"").replace(/,/g,"|"):"image/*",h=this.optional(c);if(h)return h;if("file"===a(c).attr("type")&&(g=g.replace(/\*/g,".*"),c.files&&c.files.length))
for(e=0;e<c.files.length;e++)
if(f=c.files[e],!f.type.match(new RegExp(".?("+g+")$","i")))
return!1;return!0}',
        );

        // https://github.com/matthiasmullie/minify/issues/54
        $tests[] = array(
            'function a() {
  if (true)
    return
  if (false)
    return
}',
            'function a(){if(!0)
return
if(!1)
return}',
        );

        // https://github.com/matthiasmullie/minify/issues/56
        $tests[] = array(
            'var timeRegex = /^([2][0-3]|[01]?[0-9])(:[0-5][0-9])?$/
if (start_time.match(timeRegex) == null) {}',
            'var timeRegex=/^([2][0-3]|[01]?[0-9])(:[0-5][0-9])?$/
if(start_time.match(timeRegex)==null){}',
        );

        // https://github.com/matthiasmullie/minify/issues/58
        // stripped of redundant code to expose problem case
        $tests[] = array(
            <<<'BUG'
function inspect() {
    escapedString.replace(/abc/g, '\\\'');
}
function isJSON() {
    str.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
}
BUG
,
            <<<'BUG'
function inspect(){escapedString.replace(/abc/g,'\\\'')}
function isJSON(){str.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']')}
BUG
        );

        // https://github.com/matthiasmullie/minify/issues/59
        $tests[] = array(
            'isPath:function(e) {
    return /\//.test(e);
}',
            'isPath:function(e){return/\//.test(e)}',
        );

        // https://github.com/matthiasmullie/minify/issues/64
        $tests[] = array(
            '    var d3_nsPrefix = {
        svg: "http://www.w3.org/2000/svg",
        xhtml: "http://www.w3.org/1999/xhtml",
        xlink: "http://www.w3.org/1999/xlink",
        xml: "http://www.w3.org/XML/1998/namespace",
        xmlns: "http://www.w3.org/2000/xmlns/"
    };',
            'var d3_nsPrefix={svg:"http://www.w3.org/2000/svg",xhtml:"http://www.w3.org/1999/xhtml",xlink:"http://www.w3.org/1999/xlink",xml:"http://www.w3.org/XML/1998/namespace",xmlns:"http://www.w3.org/2000/xmlns/"}',
        );

        // https://github.com/matthiasmullie/minify/issues/66
        $tests[] = array(
            "$(coming.wrap).bind('onReset', function () {
    try {
        $(this).find('iframe').hide().attr('src', '//about:blank').end().empty();
    } catch (e) {}
});",
            "$(coming.wrap).bind('onReset',function(){try{\$(this).find('iframe').hide().attr('src','//about:blank').end().empty()}catch(e){}})",
        );

        // https://github.com/matthiasmullie/minify/issues/89
        $tests[] = array(
            'for(;;ja||(ja=true)){}',
            'for(;;ja||(ja=!0)){}',
        );

        // https://github.com/matthiasmullie/minify/issues/91
        $tests[] = array(
            'if(true){if(true)console.log("test")else;}',
            'if(!0){if(!0)console.log("test")}',
        );

        // https://github.com/matthiasmullie/minify/issues/99
        $tests[] = array(
            '"object";"object2";"0";"1"',
            '"object";"object2";"0";"1"',
        );

        // https://github.com/matthiasmullie/minify/issues/102
        $tests[] = array(
            'var pb = {};',
            'var pb={}',
        );
        $tests[] = array(
            'pb.Initialize = function(settings) {};',
            'pb.Initialize=function(settings){}',
        );

        // https://github.com/matthiasmullie/minify/issues/108
        $tests[] = array(
            'function isHtmlNamespace(node) {
            var ns;
            return typeof node.namespaceURI == UNDEF || ((ns = node.namespaceURI) === null || ns == "http://www.w3.org/1999/xhtml");
        }',
            'function isHtmlNamespace(node){var ns;return typeof node.namespaceURI==UNDEF||((ns=node.namespaceURI)===null||ns=="http://www.w3.org/1999/xhtml")}',
        );

        // https://github.com/matthiasmullie/minify/issues/115
        $tests[] = array(
            'if(typeof i[s].token=="string")/keyword|support|storage/.test(i[s].token)&&n.push(i[s].regex);else if(typeof i[s].token=="object")for(var u=0,a=i[s].token.length;u<a;u++)if(/keyword|support|storage/.test(i[s].token[u])){}',
            'if(typeof i[s].token=="string")/keyword|support|storage/.test(i[s].token)&&n.push(i[s].regex);else if(typeof i[s].token=="object")for(var u=0,a=i[s].token.length;u<a;u++)if(/keyword|support|storage/.test(i[s].token[u])){}',
        );

        // https://github.com/matthiasmullie/minify/issues/120
        $tests[] = array(
            'function myFuncName() {
    function otherFuncName() {
        if (condition) {
            a = b / 1; // comment 1
        } else if (condition) {
            a = c / 2; // comment 2
        } else if (condition) {
            a = d / 3; // comment 3
        } else {
            a = 0;
        }
    }
};',
            'function myFuncName(){function otherFuncName(){if(condition){a=b/1}else if(condition){a=c/2}else if(condition){a=d/3}else{a=0}}}',
        );

        // https://github.com/matthiasmullie/minify/issues/128
        $tests[] = array(
            'angle = (i - 3) * (Math.PI * 2) / 12; // THE ANGLE TO MARK.',
            'angle=(i-3)*(Math.PI*2)/12',
        );

        // https://github.com/matthiasmullie/minify/issues/124
        $tests[] = array(
            'return cond ? document._getElementsByXPath(\'.//*\' + cond, element) : [];',
            'return cond?document._getElementsByXPath(\'.//*\'+cond,element):[]',
        );
        $tests[] = array(
            'Sizzle.selectors = {
    match: {
        PSEUDO: /:((?:[\w\u00c0-\uFFFF-]|\\.)+)(?:\(([\'"]*)((?:\([^\)]+\)|[^\2\(\)]*)+)\2\))?/
    },
    attrMap: {
        "class": "className"
    }
}',
            'Sizzle.selectors={match:{PSEUDO:/:((?:[\w\u00c0-\uFFFF-]|\\.)+)(?:\(([\'"]*)((?:\([^\)]+\)|[^\2\(\)]*)+)\2\))?/},attrMap:{"class":"className"}}',
        );

        // https://github.com/matthiasmullie/minify/issues/130
        $tests[] = array(
            'function func(){}
func()
{ alert(\'hey\'); }',
            'function func(){}
func(){alert(\'hey\')}',
        );

        // https://github.com/matthiasmullie/minify/issues/133
        $tests[] = array(
            'if ( args[\'message\'] instanceof Array ) { args[\'message\'] = args[\'message\'].join( \' \' );}',
            'if(args.message instanceof Array){args.message=args.message.join(\' \')}',
        );

        // https://github.com/matthiasmullie/minify/issues/134
        $tests[] = array(
            'e={true:!0,false:!1}',
            'e={true:!0,false:!1}',
        );

        // https://github.com/matthiasmullie/minify/issues/134
        $tests[] = array(
            'if (\'x\'+a in foo && \'y\'+b[a].z in bar)',
            'if(\'x\'+a in foo&&\'y\'+b[a].z in bar)',
        );

        // https://github.com/matthiasmullie/minify/issues/136
        $tests[] = array(
            'XPRSHelper.isManagable = function(presetId){ if (presetId in XPRSHelper.presetTypes){ return (XPRSHelper.presetTypes[presetId]["GROUP"] in {"FEATURES":true,"SLIDESHOWS":true,"GALLERIES":true}); } return false; };',
            'XPRSHelper.isManagable=function(presetId){if(presetId in XPRSHelper.presetTypes){return(XPRSHelper.presetTypes[presetId].GROUP in{"FEATURES":!0,"SLIDESHOWS":!0,"GALLERIES":!0})}return!1}',
        );

        // https://github.com/matthiasmullie/minify/issues/138
        $tests[] = array(
            'matchers.push(/^[0-9]*$/.source);',
            'matchers.push(/^[0-9]*$/.source)',
        );
        $tests[] = array(
            'matchers.push(/^[0-9]*$/.source);
String(dateString).match(/^[0-9]*$/);',
            'matchers.push(/^[0-9]*$/.source);String(dateString).match(/^[0-9]*$/)',
        );

        // https://github.com/matthiasmullie/minify/issues/139
        $tests[] = array(
            __DIR__.'/sample/line_endings/lf/script.js',
            'var a=1',
        );
        $tests[] = array(
            __DIR__.'/sample/line_endings/cr/script.js',
            'var a=1',
        );
        $tests[] = array(
            __DIR__.'/sample/line_endings/crlf/script.js',
            'var a=1',
        );

        // https://github.com/matthiasmullie/minify/issues/142
        $tests[] = array(
            'return {
    l: ((116 * y) - 16) / 100,  // [0,100]
    a: ((500 * (x - y)) + 128) / 255,   // [-128,127]
    b: ((200 * (y - z)) + 128) / 255    // [-128,127]
};',
            'return{l:((116*y)-16)/100,a:((500*(x-y))+128)/255,b:((200*(y-z))+128)/255}',
        );

        // https://github.com/matthiasmullie/minify/issues/143
        $tests[] = array(
            "if(nutritionalPortionWeightUnit == 'lbs' && blockUnit == 'oz'){
itemFat = (qty * (fat/nutritionalPortionWeight))/16;
itemProtein = (qty * (protein/nutritionalPortionWeight))/16;
itemCarbs = (qty * (carbs/nutritionalPortionWeight))/16;
itemKcal = (qty * (kcal/nutritionalPortionWeight))/16;
}",
            "if(nutritionalPortionWeightUnit=='lbs'&&blockUnit=='oz'){itemFat=(qty*(fat/nutritionalPortionWeight))/16;itemProtein=(qty*(protein/nutritionalPortionWeight))/16;itemCarbs=(qty*(carbs/nutritionalPortionWeight))/16;itemKcal=(qty*(kcal/nutritionalPortionWeight))/16}",
        );
        $tests[] = array(
            'itemFat = (qty * (fat/nutritionalPortionWeight))/16;
itemFat = (qty * (fat/nutritionalPortionWeight))/(28.3495*16);',
            'itemFat=(qty*(fat/nutritionalPortionWeight))/16;itemFat=(qty*(fat/nutritionalPortionWeight))/(28.3495*16)',
        );

        // https://github.com/matthiasmullie/minify/issues/146
        $tests[] = array(
            'rnoContent = /^(?:GET|HEAD)$/,
rprotocol = /^\/\//,
/* ...
 */
prefilters = {};',
            'rnoContent=/^(?:GET|HEAD)$/,rprotocol=/^\/\//,prefilters={}',
        );
        $tests[] = array(
            'elem.getAttribute("type")!==null)+"/"+elem.type
var rprotocol=/^\/\//,prefilters={}',
            'elem.getAttribute("type")!==null)+"/"+elem.type
var rprotocol=/^\/\//,prefilters={}',
        );
        $tests[] = array(
            'map: function( elems, callback, arg ) {
                for ( i in elems ) {
                    value = callback( elems[ i ], i, arg );
                    if ( value != null ) {
                        ret.push( value );
                    }
                }

                return concat.apply( [], ret );
            }',
            'map:function(elems,callback,arg){for(i in elems){value=callback(elems[i],i,arg);if(value!=null){ret.push(value)}}
return concat.apply([],ret)}',
        );

        // https://github.com/matthiasmullie/minify/issues/167
        $tests[] = array(
            'this.valueMap.false',
            'this.valueMap.false',
        );
        $tests[] = array(
            'this.valueMap . false',
            'this.valueMap.false',
        );
        $tests[] = array(
            'false!==true',
            '!1!==!0',
        );

        // https://github.com/matthiasmullie/minify/issues/164
        $tests[] = array(
            'Calendar.createElement = function(type, parent) {
    var el = null;
    if (document.createElementNS) {
        // use the XHTML namespace; IE won\'t normally get here unless
        // _they_ "fix" the DOM2 implementation.
        el = document.createElementNS("http://www.w3.org/1999/xhtml", type);
    } else {
        el = document.createElement(type);
    }
    if (typeof parent != "undefined") {
        parent.appendChild(el);
    }
    return el;
};',
            'Calendar.createElement=function(type,parent){var el=null;if(document.createElementNS){el=document.createElementNS("http://www.w3.org/1999/xhtml",type)}else{el=document.createElement(type)}
if(typeof parent!="undefined"){parent.appendChild(el)}
return el}',
        );
        $tests[] = array(
            "$(this).find('iframe').hide().attr('src', '//about:blank').end().empty();",
            "$(this).find('iframe').hide().attr('src','//about:blank').end().empty()",
        );

        // https://github.com/matthiasmullie/minify/issues/163
        $tests[] = array(
            'q = d / 4 / b.width()',
            'q=d/4/b.width()',
        );

        // https://github.com/matthiasmullie/minify/issues/182
        $tests[] = array(
            'label = input.val().replace(/\\\\/g, \'/\').replace(/.*\//, \'\');',
            'label=input.val().replace(/\\\\/g,\'/\').replace(/.*\//,\'\')',
        );

        // https://github.com/matthiasmullie/minify/issues/178
        $tests[] = array(
            'lunr.SortedSet.prototype.add = function () {
  var i, element

  for (i = 0; i < arguments.length; i++) {
    element = arguments[i]
    if (~this.indexOf(element)) continue
    this.elements.splice(this.locationFor(element), 0, element)
  }

  this.length = this.elements.length
}',
            'lunr.SortedSet.prototype.add=function(){var i,element
for(i=0;i<arguments.length;i++){element=arguments[i]
if(~this.indexOf(element))continue
this.elements.splice(this.locationFor(element),0,element)}
this.length=this.elements.length}',
        );

        // https://github.com/matthiasmullie/minify/issues/185
        $tests[] = array(
            'var thisPos = indexOf(stack, this);
~thisPos ? stack.splice(thisPos + 1) : stack.push(this)
~thisPos ? keys.splice(thisPos, Infinity, key) : keys.push(key)
if (~indexOf(stack, value)) value = cycleReplacer.call(this, key, value)',
            'var thisPos=indexOf(stack,this);~thisPos?stack.splice(thisPos+1):stack.push(this)
~thisPos?keys.splice(thisPos,Infinity,key):keys.push(key)
if(~indexOf(stack,value))value=cycleReplacer.call(this,key,value)',
        );

        // https://github.com/matthiasmullie/minify/issues/186
        $tests[] = array(
            'd/=60;z("/foo/.")
/*! This comment should be removed by the minify process */

var str1 = "//this-text-shoudl-remain-intact";
var str2 = "some other string here";',
            'd/=60;z("/foo/.")
var str1="//this-text-shoudl-remain-intact";var str2="some other string here"',
        );

        // https://github.com/matthiasmullie/minify/issues/189
        $tests[] = array(
            '(function() {
  window.Selector = Class.create({
    initialize: function(expression) {
      this.expression = expression.strip();
    },

    findElements: function(rootElement) {
      return Prototype.Selector.select(this.expression, rootElement);
    },

    match: function(element) {
      return Prototype.Selector.match(element, this.expression);
    },

    toString: function() {
      return this.expression;
    },

    inspect: function() {
      return "#<Selector: " + this.expression + ">";
    }
  });

  Object.extend(Selector, {
    matchElements: function(elements, expression) {
      var match = Prototype.Selector.match,
          results = [];

      for (var i = 0, length = elements.length; i < length; i++) {
        var element = elements[i];
        if (match(element, expression)) {
          results.push(Element.extend(element));
        }
      }
      return results;
    },

    findElement: function(elements, expression, index) {
      index = index || 0;
      var matchIndex = 0, element;
      for (var i = 0, length = elements.length; i < length; i++) {
        element = elements[i];
        if (Prototype.Selector.match(element, expression) && index === matchIndex++) {
          return Element.extend(element);
        }
      }
    },

    findChildElements: function(element, expressions) {
      var selector = expressions.toArray().join(\', \');
      return Prototype.Selector.select(selector, element || document);
    }
  });
})();

function someOtherFunction() {
}',
            '(function(){window.Selector=Class.create({initialize:function(expression){this.expression=expression.strip()},findElements:function(rootElement){return Prototype.Selector.select(this.expression,rootElement)},match:function(element){return Prototype.Selector.match(element,this.expression)},toString:function(){return this.expression},inspect:function(){return"#<Selector: "+this.expression+">"}});Object.extend(Selector,{matchElements:function(elements,expression){var match=Prototype.Selector.match,results=[];for(var i=0,length=elements.length;i<length;i++){var element=elements[i];if(match(element,expression)){results.push(Element.extend(element))}}
return results},findElement:function(elements,expression,index){index=index||0;var matchIndex=0,element;for(var i=0,length=elements.length;i<length;i++){element=elements[i];if(Prototype.Selector.match(element,expression)&&index===matchIndex++){return Element.extend(element)}}},findChildElements:function(element,expressions){var selector=expressions.toArray().join(\', \');return Prototype.Selector.select(selector,element||document)}})})();function someOtherFunction(){}',
        );

        // https://github.com/matthiasmullie/minify/issues/190
        $tests[] = array(
            'function fullwidth_portfolio_carousel_slide( $arrow ) {
                    var $the_portfolio = $arrow.parents(\'.et_pb_fullwidth_portfolio\'),
                        $portfolio_items = $the_portfolio.find(\'.et_pb_portfolio_items\'),
                        $the_portfolio_items = $portfolio_items.find(\'.et_pb_portfolio_item\'),
                        $active_carousel_group = $portfolio_items.find(\'.et_pb_carousel_group.active\'),
                        slide_duration = 700,
                        items = $portfolio_items.data(\'items\'),
                        columns = $portfolio_items.data(\'portfolio-columns\'),
                        item_width = $active_carousel_group.innerWidth() / columns, //$active_carousel_group.children().first().innerWidth(),
                        original_item_width = ( 100 / columns ) + \'%\';

                    if ( \'undefined\' == typeof items ) {
                        return;
                    }

                    if ( $the_portfolio.data(\'carouseling\') ) {
                        return;
                    }

                    $the_portfolio.data(\'carouseling\', true);

                    $active_carousel_group.children().each(function(){
                        $(this).css({\'width\': $(this).innerWidth() + 1, \'position\':\'absolute\', \'left\': ( $(this).innerWidth() * ( $(this).data(\'position\') - 1 ) ) });
                    });
                }',
            'function fullwidth_portfolio_carousel_slide($arrow){var $the_portfolio=$arrow.parents(\'.et_pb_fullwidth_portfolio\'),$portfolio_items=$the_portfolio.find(\'.et_pb_portfolio_items\'),$the_portfolio_items=$portfolio_items.find(\'.et_pb_portfolio_item\'),$active_carousel_group=$portfolio_items.find(\'.et_pb_carousel_group.active\'),slide_duration=700,items=$portfolio_items.data(\'items\'),columns=$portfolio_items.data(\'portfolio-columns\'),item_width=$active_carousel_group.innerWidth()/columns,original_item_width=(100/columns)+\'%\';if(\'undefined\'==typeof items){return}
if($the_portfolio.data(\'carouseling\')){return}
$the_portfolio.data(\'carouseling\',!0);$active_carousel_group.children().each(function(){$(this).css({\'width\':$(this).innerWidth()+1,\'position\':\'absolute\',\'left\':($(this).innerWidth()*($(this).data(\'position\')-1))})})}',
        );

        $tests[] = array(
            'if("some   string" /*or comment*/)/regex/',
            'if("some   string")/regex/',
        );

        // https://github.com/matthiasmullie/minify/issues/195
        $tests[] = array(
            '"function"!=typeof/./&&"object"!=typeof Int8Array',
            '"function"!=typeof/./&&"object"!=typeof Int8Array',
        );
        $tests[] = array(
            'if (true || /^(https?:)?\/\//.test(\'xxx\')) alert(1);',
            'if(!0||/^(https?:)?\/\//.test(\'xxx\'))alert(1)',
        );

        // https://github.com/matthiasmullie/minify/issues/196
        $tests[] = array(
            'if ( true ) {
    console.log(true);
// ...comment number 2 (something with dots?)
} else {
    console.log(false);
}',
            'if(!0){console.log(!0)}else{console.log(!1)}',
        );

        // https://github.com/matthiasmullie/minify/issues/197
        $tests[] = array(
            'if(!e.allow_html_data_urls&&V.test(k)&&!/^data:image\//i.test(k))return',
            'if(!e.allow_html_data_urls&&V.test(k)&&!/^data:image\//i.test(k))return',
        );

        // https://github.com/matthiasmullie/minify/issues/199
        $tests[] = array(
            '// This case was fixed on version 1.3.50
// function () {
//    return false;
// };

// Next two cases failed since version 1.3.49
// function () {
//    return false; //.click();
// };

// function () {
//    ;//;
// }',
            '',
        );

        // https://github.com/matthiasmullie/minify/issues/204
        $tests[] = array(
            'data = data.replace(this.video.reUrlYoutube, iframeStart + \'//www.youtube.com/embed/$1\' + iframeEnd);',
            'data=data.replace(this.video.reUrlYoutube,iframeStart+\'//www.youtube.com/embed/$1\'+iframeEnd)'
        );
        $tests[] = array(
            'pattern = /(\/)\'/;
a = \'b\';',
            'pattern=/(\/)\'/;a=\'b\'',
        );

        // https://github.com/matthiasmullie/minify/issues/205
        $tests[] = array(
            'return { lineComment: parserConfig.slashComments ? "//" : null }',
            'return{lineComment:parserConfig.slashComments?"//":null}',
        );
        $tests[] = array(
            '\'//\'.match(/\/|\'/);',
            '\'//\'.match(/\/|\'/)',
        );

        // https://github.com/matthiasmullie/minify/issues/209
        $tests[] = array(
            'var my_regexes = [/[a-z]{3}\//g, \'a string\', 1];',
            'var my_regexes=[/[a-z]{3}\//g,\'a string\',1]',
        );

        // https://github.com/matthiasmullie/minify/issues/211
        $tests[] = array(
            'if (last){
  for(i=1;i<3;i++);
} else if (first){
  for(i in list);
} else {
  while(this.rm(name, check, false));
}',
            'if(last){for(i=1;i<3;i++);}else if(first){for(i in list);}else{while(this.rm(name,check,!1));}',
        );
        $tests[] = array(
            'if(0){do{}while(1)}',
            'if(0){do{}while(1)}',
        );
        $tests[] = array(
            'if(0){do{}while(1);}',
            'if(0){do{}while(1);}',
        );

        // https://github.com/matthiasmullie/minify/issues/214
        $tests[] = array(
            '/\/|\'/;
\'.ctd_panel_content .ctd_preview\';',
            '/\/|\'/;\'.ctd_panel_content .ctd_preview\'',
        );

        // https://github.com/matthiasmullie/minify/issues/218
        $tests[] = array(
            "inside: {
    'rule': /@[\w-]+/
    // See rest below
}",
            "inside:{'rule':/@[\w-]+/}",
        );
        $tests[] = array(
            "inside: {
    'rule': /@[\w-]+/ // See rest below
}",
            "inside:{'rule':/@[\w-]+/}",
        );
        $tests[] = array(
            "inside: {
    'rule': /@[\w-]+/// See rest below
}",
            "inside:{'rule':/@[\w-]+/}",
        );
        $tests[] = array(
            "(1 + 2) / 3 / 4",
            "(1+2)/3/4",
        );

        // https://github.com/matthiasmullie/minify/issues/221
        $tests[] = array(
            '$export.F*/Version\/10\.\d+(\.\d+)? Safari\//.test(userAgent)',
            '$export.F*/Version\/10\.\d+(\.\d+)? Safari\//.test(userAgent)',
        );
        $tests[] = array(
            'new RegExp(/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/)',
            'new RegExp(/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/)',
        );

        // https://github.com/matthiasmullie/minify/issues/227
        $tests[] = array(
            __DIR__.'/sample/bugs/227/original.js',
            file_get_contents(__DIR__.'/sample/bugs/227/minified.js'),
        );

        // https://github.com/matthiasmullie/minify/issues/229
        $tests[] = array(
            __DIR__.'/sample/bugs/229/original.js',
            file_get_contents(__DIR__.'/sample/bugs/229/minified.js'),
        );

        // https://github.com/matthiasmullie/minify/issues/231
        $tests[] = array(
            'var x = (2 + 2) / 2; /// =2',
            'var x=(2+2)/2',
        );
        $tests[] = array(
            'if(1)/real + regex/.test("real   regex")',
            'if(1)/real + regex/.test("real   regex")',
        );

        // https://github.com/matthiasmullie/minify/issues/234
        $tests[] = array(
            'if ( !item.hasClass(\'megamenu\') && (menuPositionX + subMenuWidth) > ( windowWidth - containerWidth )/2 + containerWidth) {
    subMenu.addClass(\'eut-position-right\');
}

//////////////////////////////////////////////////////////////////////////////////////////////////////
// GLOBAL VARIABLES
//////////////////////////////////////////////////////////////////////////////////////////////////////
var largeScreen = 2048;',
            'if(!item.hasClass(\'megamenu\')&&(menuPositionX+subMenuWidth)>(windowWidth-containerWidth)/2+containerWidth){subMenu.addClass(\'eut-position-right\')}
var largeScreen=2048',
        );

        // known minified files to help doublecheck changes in places not yet
        // anticipated in these tests
        $files = glob(__DIR__.'/sample/minified/*.js');
        foreach ($files as $file) {
            $content = trim(file_get_contents($file));
            $tests[] = array($content, $content);
        }
        // update tests' expected results for cross-system compatibility
        foreach ($tests as &$test) {
            if (!empty($test[1])) {
                $test[1] = str_replace("\r", '', $test[1]);
            }
        }

        return $tests;
    }
}
