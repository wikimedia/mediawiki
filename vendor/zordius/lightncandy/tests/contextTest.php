<?php

require_once('src/lightncandy.php');
require_once('tests/helpers_for_test.php');

class contextTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider compileProvider
     */
    public function testUsedFeature($test)
    {
        LightnCandy::compile($test['template'], $test['options']);
        $context = LightnCandy::getContext();
        $this->assertEquals($test['expected'], $context['usedFeature']);
    }

    public function compileProvider()
    {
        $default = Array(
            'rootthis' => 0,
            'enc' => 0,
            'raw' => 0,
            'sec' => 0,
            'isec' => 0,
            'if' => 0,
            'else' => 0,
            'unless' => 0,
            'each' => 0,
            'this' => 0,
            'parent' => 0,
            'with' => 0,
            'comment' => 0,
            'partial' => 0,
            'dynpartial' => 0,
            'helper' => 0,
            'bhelper' => 0,
            'hbhelper' => 0,
            'delimiter' => 0,
            'subexp' => 0,
        );

        $compileCases = Array(
             Array(
                 'template' => 'abc',
             ),

             Array(
                 'template' => 'abc{{def',
             ),

             Array(
                 'template' => 'abc{{def}}',
                 'expected' => Array(
                     'enc' => 1
                 ),
             ),

             Array(
                 'template' => 'abc{{{def}}}',
                 'expected' => Array(
                     'raw' => 1
                 ),
             ),

             Array(
                 'template' => 'abc{{&def}}',
                 'expected' => Array(
                     'raw' => 1
                 ),
             ),

             Array(
                 'template' => 'abc{{this}}',
                 'expected' => Array(
                     'enc' => 1
                 ),
             ),

             Array(
                 'template' => 'abc{{this}}',
                 'options' => Array('flags' => LightnCandy::FLAG_THIS),
                 'expected' => Array(
                     'enc' => 1,
                     'this' => 1,
                     'rootthis' => 1,
                 ),
             ),

             Array(
                 'template' => '{{#if abc}}OK!{{/if}}',
                 'expected' => Array(
                     'if' => 1
                 ),
             ),

             Array(
                 'template' => '{{#unless abc}}OK!{{/unless}}',
                 'expected' => Array(
                     'unless' => 1
                 ),
             ),

             Array(
                 'template' => '{{#with abc}}OK!{{/with}}',
                 'expected' => Array(
                     'with' => 1
                 ),
             ),

             Array(
                 'template' => '{{#abc}}OK!{{/abc}}',
                 'expected' => Array(
                     'sec' => 1
                 ),
             ),

             Array(
                 'template' => '{{^abc}}OK!{{/abc}}',
                 'expected' => Array(
                     'isec' => 1
                 ),
             ),

             Array(
                 'template' => '{{#each abc}}OK!{{/each}}',
                 'expected' => Array(
                     'each' => 1
                 ),
             ),

             Array(
                 'template' => '{{! test}}OK!{{! done}}',
                 'expected' => Array(
                     'comment' => 2
                 ),
             ),

             Array(
                 'template' => '{{../OK}}',
                 'expected' => Array(
                     'parent' => 1,
                     'enc' => 1,
                 ),
             ),

             Array(
                 'template' => '{{&../../OK}}',
                 'expected' => Array(
                     'parent' => 1,
                     'raw' => 1,
                 ),
             ),

             Array(
                 'template' => '{{&../../../OK}} {{../OK}}',
                 'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'mytest' => function ($context) {
                            return $context;
                        }
                    )
                ),
                 'expected' => Array(
                     'parent' => 2,
                     'enc' => 1,
                     'raw' => 1,
                 ),
             ),

             Array(
                 'template' => '{{mytest ../../../OK}} {{../OK}}',
                 'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'mytest' => function ($context) {
                            return $context;
                        }
                    )
                ),
                 'expected' => Array(
                     'parent' => 2,
                     'enc' => 2,
                     'hbhelper' => 1,
                 ),
             ),

             Array(
                 'template' => '{{mytest . .}}',
                 'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'mytest' => function ($a, $b) {
                            return '';
                        }
                    )
                ),
                 'expected' => Array(
                     'rootthis' => 2,
                     'this' => 2,
                     'enc' => 1,
                     'hbhelper' => 1,
                 ),
             ),

             Array(
                 'template' => '{{mytest (mytest ..)}}',
                 'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'mytest' => function ($context) {
                            return $context;
                        }
                    )
                ),
                 'expected' => Array(
                     'parent' => 1,
                     'enc' => 1,
                     'hbhelper' => 2,
                     'subexp' => 1,
                 ),
             ),

             Array(
                 'template' => '{{mytest (mytest ..) .}}',
                 'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'mytest' => function ($context) {
                            return $context;
                        }
                    )
                ),
                 'expected' => Array(
                     'parent' => 1,
                     'rootthis' => 1,
                     'this' => 1,
                     'enc' => 1,
                     'hbhelper' => 2,
                     'subexp' => 1,
                 ),
             ),

             Array(
                 'template' => '{{mytest (mytest (mytest ..)) .}}',
                 'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'mytest' => function ($context) {
                            return $context;
                        }
                    )
                ),
                 'expected' => Array(
                     'parent' => 1,
                     'rootthis' => 1,
                     'this' => 1,
                     'enc' => 1,
                     'hbhelper' => 3,
                     'subexp' => 2,
                 ),
             ),

             Array(
                 'id' => '134',
                 'template' => '{{#if 1}}{{keys (keys ../names)}}{{/if}}',
                 'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'hbhelpers' => Array(
                        'keys' => function ($context) {
                            return $context;
                        }
                    )
                ),
                 'expected' => Array(
                     'parent' => 1,
                     'enc' => 1,
                     'if' => 1,
                     'hbhelper' => 2,
                     'subexp' => 1,
                 ),
             ),
        );

        return array_map(function($i) use ($default) {
            if (!isset($i['options'])) {
                $i['options'] = Array('flags' => 0);
            }
            if (!isset($i['options']['flags'])) {
                $i['options']['flags'] = 0;
            }
            $i['expected'] = array_merge($default, isset($i['expected']) ? $i['expected'] : array());
            return Array($i);
        }, $compileCases);
    }
}


?>
