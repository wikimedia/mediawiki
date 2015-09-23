<?php

require_once('src/lightncandy.php');
require_once('tests/helpers_for_test.php');

$tmpdir = sys_get_temp_dir();

class regressionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider issueProvider
     */
    public function testIssues($issue)
    {
        global $tmpdir;

        $php = LightnCandy::compile($issue['template'], isset($issue['options']) ? $issue['options'] : null);
        $context = LightnCandy::getContext();
        if (count($context['error'])) {
            $this->fail('Compile failed due to:' . print_r($context['error'], true));
        }
        $renderer = LightnCandy::prepare($php);

        $this->assertEquals($issue['expected'], $renderer($issue['data'], $issue['debug']), "PHP CODE:\n$php");
    }

    public function issueProvider()
    {
        $issues = Array(
            Array(
                'id' => 39,
                'template' => '{{{tt}}}',
                'options' => null,
                'data' => Array('tt' => 'bla bla bla'),
                'expected' => 'bla bla bla'
            ),

            Array(
                'id' => 44,
                'template' => '<div class="terms-text"> {{render "artists-terms"}} </div>',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_ERROR_LOG | LightnCandy::FLAG_EXTHELPER,
                    'helpers' => Array(
                        'url',
                        'render' => function($view,$data = array()) {
                            return 'OK!';
                         }
                    )
                ),
                'data' => Array('tt' => 'bla bla bla'),
                'expected' => '<div class="terms-text"> OK! </div>'
            ),

            Array(
                'id' => 45,
                'template' => '{{{a.b.c}}}, {{a.b.bar}}, {{a.b.prop}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_ERROR_LOG | LightnCandy::FLAG_INSTANCE | LightnCandy::FLAG_HANDLEBARSJS,
                ),
                'data' => Array('a' => Array('b' => new foo)),
                'expected' => ', OK!, Yes!'
            ),

            Array(
                'id' => 46,
                'template' => '{{{this.id}}}, {{a.id}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_THIS,
                ),
                'data' => Array('id' => 'bla bla bla', 'a' => Array('id' => 'OK!')),
                'expected' => 'bla bla bla, OK!'
            ),

            Array(
                'id' => 49,
                'template' => '{{date_format}} 1, {{date_format2}} 2, {{date_format3}} 3, {{date_format4}} 4',
                'options' => Array(
                    'helpers' => Array(
                        'date_format' => 'meetup_date_format',
                        'date_format2' => 'meetup_date_format2',
                        'date_format3' => 'meetup_date_format3',
                        'date_format4' => 'meetup_date_format4'
                    )
                ),
                'data' => null,
                'expected' => 'OKOK~1 1, OKOK~2 2, OKOK~3 3, OKOK~4 4'
            ),

            Array(
                'id' => 52,
                'template' => '{{{test_array tmp}}} should be happy!',
                'options' => Array(
                    'helpers' => Array(
                        'test_array',
                    )
                ),
                'data' => Array('tmp' => Array('A', 'B', 'C')),
                'expected' => 'IS_ARRAY should be happy!'
            ),

            Array(
                'id' => 62,
                'template' => '{{{test_join @root.foo.bar}}} should be happy!',
                'options' => Array(
                     'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_ERROR_EXCEPTION,
                     'helpers' => array('test_join')
                ),
                'data' => Array('foo' => Array('A', 'B', 'bar' => Array('C', 'D'))),
                'expected' => 'C.D should be happy!',
            ),

            Array(
                'id' => 64,
                'template' => '{{#each foo}} Test! {{this}} {{/each}}{{> test1}} ! >>> {{>recursive}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_RUNTIMEPARTIAL,                      
                    'basedir' => 'tests',
                ),
                'data' => Array(
                 'bar' => 1,
                 'foo' => Array(
                  'bar' => 3,
                  'foo' => Array(
                   'bar' => 5,
                   'foo' => Array(
                    'bar' => 7,
                    'foo' => Array(
                     'bar' => 11,
                     'foo' => Array(
                      'no foo here'
                     )
                    )
                   )
                  )
                 )
                ),
                'expected' => " Test! 3  Test! [object Object] 123\n ! >>> 1 -> 3 -> 5 -> 7 -> 11 -> END!\n\n\n\n\n\n",
            ),

            Array(
                'id' => 66,
                'template' => '{{&foo}} , {{foo}}, {{{foo}}}',
                'options' => Array(
                     'flags' => LightnCandy::FLAG_HANDLEBARSJS
                ),
                'data' => Array('foo' => 'Test & " \' :)'),
                'expected' => 'Test & " \' :) , Test &amp; &quot; &#x27; :), Test & " \' :)',
            ),

            Array(
                'id' => 68,
                'template' => '{{#myeach foo}} Test! {{this}} {{/myeach}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'myeach' => function ($context, $options) {
                            $ret = '';
                            foreach ($context as $cx) {
                                $ret .= $options['fn']($cx);
                            }
                            return $ret;
                        }
                    )
                ),
                'data' => Array('foo' => Array('A', 'B', 'bar' => Array('C', 'D', 'E'))),
                'expected' => ' Test! A  Test! B  Test! C,D,E ',
            ),

            Array(
                'id' => 81,
                'template' => '{{#with ../person}} {{^name}} Unknown {{/name}} {{/with}}?!',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_ERROR_EXCEPTION,
                ),
                'data' => Array('parent?!' => Array('A', 'B', 'bar' => Array('C', 'D', 'E'))),
                'expected' => '?!'
            ),

            Array(
                'id' => 83,
                'template' => '{{> tests/test1}}',
                'options' => Array(
                    'basedir' => '.',
                ),
                'data' => null,
                'expected' => "123\n"
            ),

            Array(
                'id' => 85,
                'template' => '{{helper 1 foo bar="q"}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'helper' => function ($arg1, $arg2, $options) {
                            return "ARG1:$arg1, ARG2:$arg2, HASH:{$options['hash']['bar']}";
                        }
                    )
                ),
                'data' => Array('foo' => 'BAR'),
                'expected' => 'ARG1:1, ARG2:BAR, HASH:q',
            ),

            Array(
                'id' => 88,
                'template' => '{{>test2}}',
                'options' => Array(
                    'flags' => 0,
                    'basedir' => 'tests',
                ),
                'data' => null,
                'expected' => "a123\nb\n",
            ),

            Array(
                'id' => 89,
                'template' => '{{#with}}SHOW:{{.}} {{/with}}',
                'data' => Array('with' => Array(1, 3, 7), 'a' => Array(2, 4, 9)),
                'expected' => 'SHOW:1 SHOW:3 SHOW:7 ',
            ),

            Array(
                'id' => 90,
                'template' => '{{#items}}{{#value}}{{.}}{{/value}}{{/items}}',
                'data' => Array('items' => Array(Array('value'=>'123'))),
                'expected' => '123',
            ),

            Array(
                'id' => 109,
                'template' => '{{#if "OK"}}it\'s great!{{/if}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_NOESCAPE,
                ),
                'data' => null,
                'expected' => 'it\'s great!',
            ),

            Array(
                'id' => 110,
                'template' => 'ABC{{#block "YES!"}}DEF{{foo}}GHI{{/block}}JKL',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_BESTPERFORMANCE,
                    'hbhelpers' => Array(
                        'block' => function ($name, $options) {
                            return "1-$name-2-" . $options['fn']() . '-3';
                        }
                    ),
                ),
                'data' => Array('foo' => 'bar'),
                'expected' => 'ABC1-YES!-2-DEFbarGHI-3JKL',
            ),

            Array(
                'id' => 109,
                'template' => '{{foo}} {{> test}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_NOESCAPE,
                    'partials' => Array('test' => '{{foo}}'),
                ),
                'data' => Array('foo' => '<'),
                'expected' => '< <',
            ),

            Array(
                'id' => 114,
                'template' => '{{^myeach .}}OK:{{.}},{{else}}NOT GOOD{{/myeach}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_BESTPERFORMANCE,
                    'hbhelpers' => Array(
                        'myeach' => function ($context, $options) {
                            $ret = '';
                            foreach ($context as $cx) {
                                $ret .= $options['fn']($cx);
                            }
                            return $ret;
                        }
                    ),
                ),
                'data' => Array(1, 'foo', 3, 'bar'),
                'expected' => 'NOT GOODNOT GOODNOT GOODNOT GOOD',
            ),

            Array(
                'id' => 114,
                'template' => '{{^myeach .}}OK:{{.}},{{else}}NOT GOOD{{/myeach}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_BESTPERFORMANCE,
                    'blockhelpers' => Array(
                        'myeach' => function ($input) {
                            return $input;
                        }
                    ),
                ),
                'data' => Array(1, 'foo', 3, 'bar'),
                'expected' => 'NOT GOOD',
            ),

            Array(
                'id' => 114,
                'template' => '{{^myeach .}}OK:{{.}},{{else}}NOT GOOD{{/myeach}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_BESTPERFORMANCE,
                    'blockhelpers' => Array(
                        'myeach' => function ($input) {
                            return;
                        }
                    ),
                ),
                'data' => Array(1, 'foo', 3, 'bar'),
                'expected' => 'OK:,',
            ),

            Array(
                'id' => 124,
                'template' => '{{list foo bar abc=(lt 10 3) def=(lt 3 10)}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'lt' => function ($a, $b) {
                            return ($a > $b) ? Array("$a>$b", 'raw') : '';
                        },
                        'list' => function () {
                            $out = 'List:';
                            $args = func_get_args();
                            $opts = array_pop($args);

                            foreach ($args as $v) {
                                if ($v) {
                                    $out .= ")$v , ";
                                }
                            }

                            foreach ($opts['hash'] as $k => $v) {
                                if ($v) {
                                    $out .= "]$k=$v , ";
                                }
                            }
                            return array($out, 'raw');
                        }
                    ),
                ),
                'data' => Array('foo' => 'OK!', 'bar' => 'OK2', 'abc' => false, 'def' => 123),
                'expected' => 'List:)OK! , )OK2 , ]abc=10>3 , ',
            ),

            Array(
                'id' => 124,
                'template' => '{{#if (equal \'OK\' cde)}}YES!{{/if}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'equal' => function ($a, $b) {
                            return $a === $b;
                        }
                    ),
                ),
                'data' => Array('cde' => 'OK'),
                'expected' => 'YES!'
            ),

            Array(
                'id' => 124,
                'template' => '{{#if (equal true (equal \'OK\' cde))}}YES!{{/if}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'equal' => function ($a, $b) {
                            return $a === $b;
                        }
                    ),
                ),
                'data' => Array('cde' => 'OK'),
                'expected' => 'YES!'
            ),

            Array(
                'id' => 125,
                'template' => '{{#if (equal true ( equal \'OK\' cde))}}YES!{{/if}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'equal' => function ($a, $b) {
                            return $a === $b;
                        }
                    ),
                ),
                'data' => Array('cde' => 'OK'),
                'expected' => 'YES!'
            ),

            Array(
                'id' => 125,
                'template' => '{{#if (equal true (equal \' OK\' cde))}}YES!{{/if}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'equal' => function ($a, $b) {
                            return $a === $b;
                        }
                    ),
                ),
                'data' => Array('cde' => ' OK'),
                'expected' => 'YES!'
            ),

            Array(
                'id' => 125,
                'template' => '{{#if (equal true (equal \' ==\' cde))}}YES!{{/if}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'equal' => function ($a, $b) {
                            return $a === $b;
                        }
                    ),
                ),
                'data' => Array('cde' => ' =='),
                'expected' => 'YES!'
            ),

            Array(
                'id' => 125,
                'template' => '{{#if (equal true (equal " ==" cde))}}YES!{{/if}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'equal' => function ($a, $b) {
                            return $a === $b;
                        }
                    ),
                ),
                'data' => Array('cde' => ' =='),
                'expected' => 'YES!'
            ),

            Array(
                'id' => 125,
                'template' => '{{[ abc]}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'equal' => function ($a, $b) {
                            return $a === $b;
                        }
                    ),
                ),
                'data' => Array(' abc' => 'YES!'),
                'expected' => 'YES!'
            ),

            Array(
                'id' => 125,
                'template' => '{{list [ abc] " xyz" \' def\' "==" \'==\' "OK"}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'list' => function ($a, $b) {
                            $out = 'List:';
                            $args = func_get_args();
                            $opts = array_pop($args);
                            foreach ($args as $v) {
                                if ($v) {
                                    $out .= ")$v , ";
                                }
                            }
                            return $out;
                        }
                    ),
                ),
                'data' => Array(' abc' => 'YES!'),
                'expected' => 'List:)YES! , ) xyz , ) def , )== , )== , )OK , '
            ),

            Array(
                'id' => 127,
                'template' => '{{#each array}}#{{#if true}}{{name}}-{{../name}}-{{../../name}}-{{../../../name}}{{/if}}##{{#myif true}}{{name}}={{../name}}={{../../name}}={{../../../name}}{{/myif}}###{{#mywith true}}{{name}}~{{../name}}~{{../../name}}~{{../../../name}}{{/mywith}}{{/each}}',
                'data' => Array('name' => 'john', 'array' => Array(1,2,3)),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array('myif', 'mywith'),
                ),
                'expected' => '#--john-##==john=###~~john~#--john-##==john=###~~john~#--john-##==john=###~~john~',
            ),

            Array(
                'id' => 128,
                'template' => 'foo: {{foo}} , parent foo: {{../foo}}',
                'data' => Array('foo' => 'OK'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                ),
                'expected' => 'foo: OK , parent foo: ',
            ),

            Array(
                'id' => 132,
                'template' => '{{list (keys .)}}',
                'data' => Array('foo' => 'bar', 'test' => 'ok'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'keys' => function($arg) {
                            return Array(array_keys($arg[0]), 'asis');
                         },
                        'list' => function($arg) {
                            return join(',', $arg[0]);
                         }
                    ),
                ),
                'expected' => 'foo,test',
            ),

            Array(
                'id' => 133,
                'template' => "{{list (keys\n .\n ) \n}}",
                'data' => Array('foo' => 'bar', 'test' => 'ok'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'keys' => function($arg) {
                            return Array(array_keys($arg[0]), 'asis');
                         },
                        'list' => function($arg) {
                            return join(',', $arg[0]);
                         }
                    ),
                ),
                'expected' => 'foo,test',
            ),

            Array(
                'id' => 133,
                'template' => "{{list\n .\n \n \n}}",
                'data' => Array('foo', 'bar', 'test'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'list' => function($arg) {
                            return join(',', $arg[0]);
                         }
                    ),
                ),
                'expected' => 'foo,bar,test',
            ),

            Array(
                'id' => 134,
                'template' => "{{#if 1}}{{list (keys ../names)}}{{/if}}",
                'data' => Array('names' => Array('foo' => 'bar', 'test' => 'ok')),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'keys' => function($arg) {
                            return Array(array_keys($arg[0]), 'asis');
                         },
                        'list' => function($arg) {
                            return join(',', $arg[0]);
                         }
                    ),
                ),
                'expected' => 'foo,test',
            ),

            Array(
                'id' => 138,
                'template' => "{{#each (keys .)}}={{.}}{{/each}}",
                'data' => Array('foo' => 'bar', 'test' => 'ok', 'Haha'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'keys' => function($arg) {
                            return Array(array_keys($arg[0]), 'asis');
                         }
                    ),
                ),
                'expected' => '=foo=test=0',
            ),

            Array(
                'id' => 140,
                'template' => "{{[a.good.helper] .}}",
                'data' => Array('ha', 'hey', 'ho'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'a.good.helper' => function($arg) {
                            return join(',', $arg[0]);
                         }
                    ),
                ),
                'expected' => 'ha,hey,ho',
            ),

            Array(
                'id' => 141,
                'template' => "{{#with foo}}{{#getThis bar}}{{/getThis}}{{/with}}",
                'data' => Array('foo' => Array('bar' => 'Good!')),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'getThis' => function($input, $options) {
                            return $input . '-' . $options['_this']['bar'];
                         }
                    ),
                ),
                'expected' => 'Good!-Good!',
            ),

            Array(
                'id' => 141,
                'template' => "{{#with foo}}{{getThis bar}}{{/with}}",
                'data' => Array('foo' => Array('bar' => 'Good!')),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'getThis' => function($input, $options) {
                            return $input . '-' . $options['_this']['bar'];
                         }
                    ),
                ),
                'expected' => 'Good!-Good!',
            ),

            Array(
                'id' => 143,
                'template' => "{{testString foo bar=\" \"}}",
                'data' => Array('foo' => 'good!'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'testString' => function($args, $named) {
                            return $args[0] . '-' . $named['bar'];
                         }
                    ),
                ),
                'expected' => 'good!- ',
            ),

            Array(
                'id' => 143,
                'template' => "{{testString foo bar=\"\"}}",
                'data' => Array('foo' => 'good!'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'testString' => function($args, $named) {
                            return $args[0] . '-' . $named['bar'];
                         }
                    ),
                ),
                'expected' => 'good!-',
            ),

            Array(
                'id' => 143,
                'template' => "{{testString foo bar=' '}}",
                'data' => Array('foo' => 'good!'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'testString' => function($args, $named) {
                            return $args[0] . '-' . $named['bar'];
                         }
                    ),
                ),
                'expected' => 'good!- ',
            ),

            Array(
                'id' => 143,
                'template' => "{{testString foo bar=''}}",
                'data' => Array('foo' => 'good!'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'testString' => function($args, $named) {
                            return $args[0] . '-' . $named['bar'];
                         }
                    ),
                ),
                'expected' => 'good!-',
            ),

            Array(
                'id' => 143,
                'template' => "{{testString foo bar=\" \"}}",
                'data' => Array('foo' => 'good!'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'testString' => function($arg1, $options) {
                            return $arg1 . '-' . $options['hash']['bar'];
                         }
                    ),
                ),
                'expected' => 'good!- ',
            ),

            Array(
                'id' => 147,
                'template' => '{{> test/test3 foo="bar"}}',
                'data' => Array('test' => 'OK!', 'foo' => 'error'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_RUNTIMEPARTIAL,
                    'partials' => Array('test/test3' => '{{test}}, {{foo}}'),
                ),
                'expected' => 'OK!, bar'
            ),

            Array(
                'id' => 147,
                'template' => '{{> test/test3 foo="bar"}}',
                'data' => new foo(),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_RUNTIMEPARTIAL | LightnCandy::FLAG_INSTANCE,
                    'partials' => Array('test/test3' => '{{bar}}, {{foo}}'),
                ),
                'expected' => 'OK!, bar'
            ),

            Array(
                'id' => 150,
                'template' => '{{{.}}}',
                'data' => Array('hello' => 'world'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'lcrun' => 'MyLCRunClass',
                ),
                'expected' => "[[DEBUG:raw()=>array (\n  'hello' => 'world',\n)]]",
            ),

            Array(
                'id' => 153,
                'template' => '{{echo "test[]"}}',
                'data' => null,
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'echo' => function ($in) {
                            return "-$in[0]-";
                        }
                    )
                ),
                'expected' => "-test[]-",
            ),

            Array(
                'id' => 153,
                'template' => '{{echo \'test[]\'}}',
                'data' => null,
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'echo' => function ($in) {
                            return "-$in[0]-";
                        }
                    )
                ),
                'expected' => "-test[]-",
            ),

            Array(
                'id' => 154,
                'template' => 'O{{! this is comment ! ... }}K!',
                'data' => null,
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                ),
                'expected' => "OK!"
            ),

            Array(
                'template' => '{{testNull null undefined 1}}',
                'data' => 'test',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'testNull' => function($arg1, $arg2) {
                            return (($arg1 === null) && ($arg2 === null)) ? 'YES!' : 'no';
                         }
                    )
                ),
                'expected' => 'YES!'
            ),

            Array(
                'template' => '{{> (pname foo) bar}}',
                'data' => Array('bar' => 'OK! SUBEXP+PARTIAL!', 'foo' => Array('test/test3')),
                'options' => Array(
                    'helpers' => Array(
                        'pname' => function($arg) {
                            return $arg[0];
                         }
                    ),
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_RUNTIMEPARTIAL,
                    'partials' => Array('test/test3' => '{{.}}'),
                ),
                'expected' => 'OK! SUBEXP+PARTIAL!'
            ),

            Array(
                'template' => '{{> testpartial newcontext mixed=foo}}',
                'data' => Array('foo' => 'OK!', 'newcontext' => Array('bar' => 'test')),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_RUNTIMEPARTIAL,
                    'partials' => Array('testpartial' => '{{bar}}-{{mixed}}'),
                ),
                'expected' => 'test-OK!'
            ),

            Array(
                'template' => '{{[helper]}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'helper' => function () {
                            return 'DEF';
                        }
                    )
                ),
                'data' => Array(),
                'expected' => 'DEF'
            ),

            Array(
                'template' => '{{#[helper3]}}ABC{{/[helper3]}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'helper3' => function () {
                            return 'DEF';
                        }
                    )
                ),
                'data' => Array(),
                'expected' => 'DEF'
            ),

            Array(
                'template' => '{{#[helper3]}}ABC{{/[helper3]}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'blockhelpers' => Array(
                        'helper3' => function () {
                            return Array('a', 'b', 'c');
                        }
                    )
                ),
                'data' => Array(),
                'expected' => 'ABC'
            ),

            Array(
                'template' => '{{hash abc=["def=123"]}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_BESTPERFORMANCE,
                    'hbhelpers' => Array(
                        'hash' => function ($options) {
                            $ret = '';
                            foreach ($options['hash'] as $k => $v) {
                                $ret .= "$k : $v,";
                            }
                            return $ret;
                        }
                    ),
                ),
                'data' => Array('"def=123"' => 'La!'),
                'expected' => 'abc : La!,',
            ),

            Array(
                'template' => '{{hash abc=[\'def=123\']}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_BESTPERFORMANCE,
                    'hbhelpers' => Array(
                        'hash' => function ($options) {
                            $ret = '';
                            foreach ($options['hash'] as $k => $v) {
                                $ret .= "$k : $v,";
                            }
                            return $ret;
                        }
                    ),
                ),
                'data' => Array("'def=123'" => 'La!'),
                'expected' => 'abc : La!,',
            ),

            Array(
                'template' => 'ABC{{#block "YES!"}}DEF{{foo}}GHI{{else}}NO~{{/block}}JKL',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_BESTPERFORMANCE,
                    'hbhelpers' => Array(
                        'block' => function ($name, $options) {
                            return "1-$name-2-" . $options['fn']() . '-3';
                        }
                    ),
                ),
                'data' => Array('foo' => 'bar'),
                'expected' => 'ABC1-YES!-2-DEFbarGHI-3JKL',
            ),

            Array(
                'template' => '-{{getroot}}=',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_SPVARS,
                    'hbhelpers' => Array('getroot'),
                ),
                'data' => 'ROOT!',
                'expected' => '-ROOT!=',
            ),

            Array(
                'template' => 'A{{#each .}}-{{#each .}}={{.}},{{@key}},{{@index}},{{@../index}}~{{/each}}%{{/each}}B',
                'data' => Array(Array('a' => 'b'), Array('c' => 'd'), Array('e' => 'f')),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_PARENT | LightnCandy::FLAG_THIS | LightnCandy::FLAG_SPVARS,
                ),
                'expected' => 'A-=b,a,0,0~%-=d,c,0,1~%-=f,e,0,2~%B',
            ),

            Array(
                'template' => 'ABC{{#block "YES!"}}TRUE{{else}}DEF{{foo}}GHI{{/block}}JKL',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_BESTPERFORMANCE,
                    'hbhelpers' => Array(
                        'block' => function ($name, $options) {
                            return "1-$name-2-" . $options['inverse']() . '-3';
                        }
                    ),
                ),
                'data' => Array('foo' => 'bar'),
                'expected' => 'ABC1-YES!-2-DEFbarGHI-3JKL',
            ),

            Array(
                'template' => '{{#each .}}{{..}}>{{/each}}',
                'data' => Array('a', 'b', 'c'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                ),
                'expected' => 'a,b,c>a,b,c>a,b,c>',
            ),

            Array(
                'template' => '{{#each .}}->{{>tests/test3}}{{/each}}',
                'data' => Array('a', 'b', 'c'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'basedir' => '.',
                ),
                'expected' => "->New context:a\n->New context:b\n->New context:c\n",
            ),

            Array(
                'template' => '{{#each .}}->{{>tests/test3 ../foo}}{{/each}}',
                'data' => Array('a', 'foo' => Array('d', 'e', 'f')),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_RUNTIMEPARTIAL,
                    'basedir' => '.',
                ),
                'expected' => "->New context:d,e,f\n->New context:d,e,f\n",
            ),

            Array(
                'template' => '{{{"{{"}}}',
                'data' => null,
                'expected' => '{{',
            ),

            Array(
                'template' => '{{good_helper}}',
                'data' => null,
                'options' => Array(
                    'helpers' => Array('good_helper' => 'foo::bar'),
                ),
                'expected' => 'OK!',
            ),

            Array(
                'template' => '-{{.}}-',
                'options' => Array('flags' => LightnCandy::FLAG_THIS),
                'data' => 'abc',
                'expected' => '-abc-',
            ),

            Array(
                'template' => '-{{this}}-',
                'options' => Array('flags' => LightnCandy::FLAG_THIS),
                'data' => 123,
                'expected' => '-123-',
            ),

            Array(
                'template' => '{{#if .}}YES{{else}}NO{{/if}}',
                'options' => Array('flags' => LightnCandy::FLAG_ELSE),
                'data' => true,
                'expected' => 'YES',
            ),

            Array(
                'template' => '{{foo}}',
                'options' => Array('flags' => LightnCandy::FLAG_RENDER_DEBUG),
                'data' => Array('foo' => 'OK'),
                'expected' => 'OK',
            ),

            Array(
                'template' => '{{foo}}',
                'options' => Array('flags' => LightnCandy::FLAG_RENDER_DEBUG),
                'debug' => LCRun3::DEBUG_TAGS_ANSI,
                'data' => Array('foo' => 'OK'),
                'expected' => pack('H*', '1b5b303b33326d7b7b5b666f6f5d7d7d1b5b306d'),
            ),

            Array(
                'template' => '{{foo}}',
                'options' => Array('flags' => LightnCandy::FLAG_RENDER_DEBUG),
                'debug' => LCRun3::DEBUG_TAGS_HTML,
                'data' => null,
                'expected' => '<!--MISSED((-->{{[foo]}}<!--))-->',
            ),

            Array(
                'template' => '{{#foo}}OK{{/foo}}',
                'options' => Array('flags' => LightnCandy::FLAG_RENDER_DEBUG),
                'debug' => LCRun3::DEBUG_TAGS_HTML,
                'data' => null,
                'expected' => '<!--MISSED((-->{{#[foo]}}<!--))--><!--SKIPPED--><!--MISSED((-->{{/[foo]}}<!--))-->',
            ),

            Array(
                'template' => '{{#foo}}OK{{/foo}}',
                'options' => Array('flags' => LightnCandy::FLAG_RENDER_DEBUG),
                'debug' => LCRun3::DEBUG_TAGS_ANSI,
                'data' => null,
                'expected' => pack('H*', '1b5b303b33316d7b7b235b666f6f5d7d7d1b5b306d1b5b303b33336d534b49505045441b5b306d1b5b303b33316d7b7b2f5b666f6f5d7d7d1b5b306d'),
            ),

            Array(
                'template' => '{{#myif foo}}YES{{else}}NO{{/myif}}',
                'data' => null,
                'options' => Array(
                    'hbhelpers' => Array('myif'),
                ),
                'expected' => 'NO',
            ),

            Array(
                'template' => '{{#myif foo}}YES{{else}}NO{{/myif}}',
                'data' => Array('foo' => 1),
                'options' => Array(
                    'hbhelpers' => Array('myif'),
                ),
                'expected' => 'YES',
            ),

            Array(
                'template' => '{{#mylogic 0 foo bar}}YES:{{.}}{{else}}NO:{{.}}{{/mylogic}}',
                'data' => Array('foo' => 'FOO', 'bar' => 'BAR'),
                'options' => Array(
                    'hbhelpers' => Array('mylogic'),
                ),
                'expected' => 'NO:BAR',
            ),

            Array(
                'template' => '{{#mylogic true foo bar}}YES:{{.}}{{else}}NO:{{.}}{{/mylogic}}',
                'data' => Array('foo' => 'FOO', 'bar' => 'BAR'),
                'options' => Array(
                    'hbhelpers' => Array('mylogic'),
                ),
                'expected' => 'YES:FOO',
            ),

            Array(
                'template' => '{{#mywith foo}}YA: {{name}}{{/mywith}}',
                'data' => Array('name' => 'OK?', 'foo' => Array('name' => 'OK!')),
                'options' => Array(
                    'hbhelpers' => Array('mywith'),
                ),
                'expected' => 'YA: OK!',
            ),

            Array(
                'template' => '{{mydash \'abc\' "dev"}}',
                'data' => Array('a' => 'a', 'b' => 'b', 'c' => Array('c' => 'c'), 'd' => 'd', 'e' => 'e'),
                'options' => Array(
                    'hbhelpers' => Array('mydash'),
                ),
                'expected' => 'abc-dev',
            ),

            Array(
                'template' => '{{mydash \'a b c\' "d e f"}}',
                'data' => Array('a' => 'a', 'b' => 'b', 'c' => Array('c' => 'c'), 'd' => 'd', 'e' => 'e'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_ADVARNAME,
                    'hbhelpers' => Array('mydash'),
                ),
                'expected' => 'a b c-d e f',
            ),

            Array(
                'template' => '{{mydash "abc" (test_array 1)}}',
                'data' => Array('a' => 'a', 'b' => 'b', 'c' => Array('c' => 'c'), 'd' => 'd', 'e' => 'e'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_ADVARNAME,
                    'hbhelpers' => Array('mydash'),
                    'helpers' => Array('test_array'),
                ),
                'expected' => 'abc-NOT_ARRAY',
            ),

            Array(
                'template' => '{{mydash "abc" (myjoin a b)}}',
                'data' => Array('a' => 'a', 'b' => 'b', 'c' => Array('c' => 'c'), 'd' => 'd', 'e' => 'e'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_ADVARNAME,
                    'hbhelpers' => Array('mydash', 'myjoin'),
                ),
                'expected' => 'abc-ab',
            ),

            Array(
                'template' => '{{#with people}}Yes , {{name}}{{else}}No, {{name}}{{/with}}',
                'data' => Array('people' => Array('name' => 'Peter'), 'name' => 'NoOne'),
                'options' => Array('flags' => LightnCandy::FLAG_WITH),
                'expected' => 'Yes , Peter',
            ),

            Array(
                'template' => '{{#with people}}Yes , {{name}}{{else}}No, {{name}}{{/with}}',
                'data' => Array('name' => 'NoOne'),
                'options' => Array('flags' => LightnCandy::FLAG_WITH),
                'expected' => 'No, NoOne',
            ),

            Array(
                'template' => <<<VAREND
<ul>
 <li>1. {{helper1 name}}</li>
 <li>2. {{helper1 value}}</li>
 <li>3. {{myClass::helper2 name}}</li>
 <li>4. {{myClass::helper2 value}}</li>
 <li>5. {{he name}}</li>
 <li>6. {{he value}}</li>
 <li>7. {{h2 name}}</li>
 <li>8. {{h2 value}}</li>
 <li>9. {{link name}}</li>
 <li>10. {{link value}}</li>
 <li>11. {{alink url text}}</li>
 <li>12. {{{alink url text}}}</li>
</ul>
VAREND
                ,
                'data' => Array('name' => 'John', 'value' => 10000, 'url' => 'http://yahoo.com', 'text' => 'You&Me!'),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_ERROR_LOG | LightnCandy::FLAG_HANDLEBARSJS,
                    'helpers' => Array(
                        'helper1',
                        'myClass::helper2',
                        'he' => 'helper1',
                        'h2' => 'myClass::helper2',
                        'link' => function ($arg) {
                            if (is_array($arg)) {
                                $arg = 'Array';
                            }
                            return "<a href=\"{$arg}\">click here</a>";
                        },
                        'alink',
                    )
                ),
                'expected' => <<<VAREND
<ul>
 <li>1. -Array-</li>
 <li>2. -Array-</li>
 <li>3. =Array=</li>
 <li>4. =Array=</li>
 <li>5. -Array-</li>
 <li>6. -Array-</li>
 <li>7. =Array=</li>
 <li>8. =Array=</li>
 <li>9. &lt;a href=&quot;Array&quot;&gt;click here&lt;/a&gt;</li>
 <li>10. &lt;a href=&quot;Array&quot;&gt;click here&lt;/a&gt;</li>
 <li>11. &lt;a href=&quot;Array&quot;&gt;Array&lt;/a&gt;</li>
 <li>12. <a href="Array">Array</a></li>
</ul>
VAREND
            ),

            Array(
                'template' => '{{test.test}} == {{test.test3}}',
                'data' => Array('test' => new myClass()),
                'options' => Array('flags' => LightnCandy::FLAG_INSTANCE),
                'expected' => "testMethod OK! == -- test3:Array\n(\n)\n",
            ),

            Array(
                'template' => '{{test.test}} == {{test.bar}}',
                'data' => Array('test' => new foo()),
                'options' => Array('flags' => LightnCandy::FLAG_INSTANCE),
                'expected' => ' == OK!',
            ),

            Array(
                'template' => '{{#each foo}}{{@key}}: {{.}},{{/each}}',
                'data' => Array('foo' => Array(1,'a'=>'b',5)),
                'expected' => ': 1,: b,: 5,',
            ),

            Array(
                'template' => '{{#each foo}}{{@key}}: {{.}},{{/each}}',
                'data' => Array('foo' => Array(1,'a'=>'b',5)),
                'options' => Array('flags' => LightnCandy::FLAG_SPVARS),
                'expected' => '0: 1,a: b,1: 5,',
            ),

            Array(
                'template' => '{{#each foo}}{{@key}}: {{.}},{{/each}}',
                'data' => Array('foo' => new twoDimensionIterator(2, 3)),
                'options' => Array('flags' => LightnCandy::FLAG_SPVARS),
                'expected' => '0x0: 0,1x0: 0,0x1: 0,1x1: 1,0x2: 0,1x2: 2,',
            ),

            Array(
                'template' => "   {{#foo}}\n {{name}}\n{{/foo}}\n  ",
                'data' => Array('foo' => Array(Array('name' => 'A'),Array('name' => 'd'),Array('name' => 'E'))),
                'options' => Array('flags' => LightnCandy::FLAG_MUSTACHESP),
                'expected' => " A\n d\n E\n  ",
            ),

            Array(
                'template' => "{{bar}}\n   {{#foo}}\n {{name}}\n{{/foo}}\n  ",
                'data' => Array('bar' => 'OK', 'foo' => Array(Array('name' => 'A'),Array('name' => 'd'),Array('name' => 'E'))),
                'options' => Array('flags' => LightnCandy::FLAG_MUSTACHESP),
                'expected' => "OK\n A\n d\n E\n  ",
            ),

            Array(
                'template' => "   {{#if foo}}\nYES\n{{else}}\nNO\n{{/if}}\n",
                'data' => null,
                'options' => Array('flags' => LightnCandy::FLAG_MUSTACHESP | LightnCandy::FLAG_ELSE),
                'expected' => "NO\n",
            ),

            Array(
                'template' => "  {{#each foo}}\n{{@key}}: {{.}}\n{{/each}}\nDONE",
                'data' => Array('foo' => Array('a' => 'A', 'b' => 'BOY!')),
                'options' => Array('flags' => LightnCandy::FLAG_SPVARS | LightnCandy::FLAG_MUSTACHESP),
                'expected' => "a: A\nb: BOY!\nDONE",
            ),

            Array(
                'template' => "{{>test1}}\n  {{>test1}}\nDONE\n",
                'data' => null,
                'options' => Array(
                    'flags' => LightnCandy::FLAG_MUSTACHESP | LightnCandy::FLAG_MUSTACHEPAIN,
                    'partials' => Array('test1' => "1:A\n 2:B\n  3:C\n 4:D\n5:E\n"),
                ),
                'expected' => "1:A\n 2:B\n  3:C\n 4:D\n5:E\n  1:A\n   2:B\n    3:C\n   4:D\n  5:E\nDONE\n",
            ),

            Array(
                'template' => "{{>test1}}\n  {{>test1}}\nDONE\n",
                'data' => null,
                'options' => Array(
                    'flags' => LightnCandy::FLAG_MUSTACHESP,
                    'partials' => Array('test1' => "1:A\n 2:B\n  3:C\n 4:D\n5:E\n"),
                ),
                'expected' => "1:A\n 2:B\n  3:C\n 4:D\n5:E\n1:A\n 2:B\n  3:C\n 4:D\n5:E\nDONE\n",
            ),

            Array(
                'template' => "{{>test1}}\n  {{>test1}}\nDONE\n",
                'data' => null,
                'options' => Array(
                    'flags' => LightnCandy::FLAG_MUSTACHESP | LightnCandy::FLAG_RUNTIMEPARTIAL,
                    'partials' => Array('test1' => "1:A\n 2:B\n  3:C\n 4:D\n5:E\n"),
                ),
                'expected' => "1:A\n 2:B\n  3:C\n 4:D\n5:E\n1:A\n 2:B\n  3:C\n 4:D\n5:E\nDONE\n",
            ),

            Array(
                'template' => "ST:\n{{#foo}}\n {{>test1}}\n{{/foo}}\nOK\n",
                'data' => Array('foo' => Array(1, 2)),
                'options' => Array(
                    'flags' => LightnCandy::FLAG_MUSTACHESP | LightnCandy::FLAG_MUSTACHEPAIN | LightnCandy::FLAG_HANDLEBARSJS,
                    'partials' => Array('test1' => "1:A\n 2:B({{@index}})\n"),
                ),
                'expected' => "ST:\n 1:A\n  2:B(0)\n 1:A\n  2:B(1)\nOK\n",
            ),

            Array(
                'template' => "A\n {{#if 1}} \n\na\n{{#with 2}}\n123\n\n{{/with}}\n{{/if}}  \n \n\n456",
                'data' => null,
                'options' => Array('flags' => LightnCandy::FLAG_WITH | LightnCandy::FLAG_MUSTACHESP),
                'expected' => "A\n\na\n123\n\n \n\n456",
            ),

            Array(
                'template' => "\n{{#with 1}}\n\n{{#with 1}}\nb\n\n{{/with}}\n{{/with}}\nC",
                'data' => null,
                'options' => Array('flags' => LightnCandy::FLAG_WITH | LightnCandy::FLAG_MUSTACHESP),
                'expected' => "\n\nb\n\nC",
            ),

            Array(
                'template' => ">{{helper1 \"===\"}}<",
                'data' => null,
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'helper1',
                    )
                ),
                'expected' => ">-===-<",
            ),

            Array(
                'template' => "{{foo}}",
                'data' => Array('foo' => 'A&B " \''),
                'options' => Array('flags' => LightnCandy::FLAG_NOESCAPE),
                'expected' => "A&B \" '",
            ),

            Array(
                'template' => "{{foo}}",
                'data' => Array('foo' => 'A&B " \''),
                'options' => null,
                'expected' => "A&amp;B &quot; &#039;",
            ),
        );

        return array_map(function($i) {
            if (!isset($i['debug'])) {
                $i['debug'] = 0;
            }
            return Array($i);
        }, $issues);
    }
}

?>
