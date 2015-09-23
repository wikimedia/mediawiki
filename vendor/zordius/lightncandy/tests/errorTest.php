<?php

require_once('src/lightncandy.php');
require_once('tests/helpers_for_test.php');

$tmpdir = sys_get_temp_dir();
$errlog_fn = tempnam($tmpdir, 'terr_');

function start_catch_error_log() {
    global $errlog_fn;
    date_default_timezone_set('GMT');
    if (file_exists($errlog_fn)) {
        unlink($errlog_fn);
    }
    return ini_set('error_log', $errlog_fn);
}

function stop_catch_error_log() {
    global $errlog_fn;
    ini_restore('error_log');
    if (!file_exists($errlog_fn)) {
        return null;
    }
    return array_map(function ($l) {
        $l = rtrim($l);
        preg_match('/GMT\] (.+)/', $l, $m);
        return isset($m[1]) ? $m[1] : $l;
    }, file($errlog_fn));
}

class errorTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $this->setExpectedException('Exception', 'Bad token {{{foo}} ! Do you mean {{foo}} or {{{foo}}}?');
        $php = LightnCandy::compile('{{{foo}}', Array('flags' => LightnCandy::FLAG_ERROR_EXCEPTION));
    }

    public function testErrorLog()
    {
        start_catch_error_log();
        $php = LightnCandy::compile('{{{foo}}', Array('flags' => LightnCandy::FLAG_ERROR_LOG));
        $e = stop_catch_error_log();
        if ($e) {
            $this->assertEquals(Array('Bad token {{{foo}} ! Do you mean {{foo}} or {{{foo}}}?'), $e);
        } else {
            $this->markTestIncomplete('skip HHVM');
        }
    }

    /**
     * @dataProvider renderErrorProvider
     */
    public function testRenderingException($test)
    {
        $this->setExpectedException('Exception', $test['expected']);
        $php = LightnCandy::compile($test['template'], $test['options']);
        $renderer = LightnCandy::prepare($php);
        $renderer(null, LCRun3::DEBUG_ERROR_EXCEPTION);
    }

    /**
     * @dataProvider renderErrorProvider
     */
    public function testRenderingErrorLog($test)
    {
        start_catch_error_log();
        $php = LightnCandy::compile($test['template'], $test['options']);
        $renderer = LightnCandy::prepare($php);
        $renderer(null, LCRun3::DEBUG_ERROR_LOG);
        $e = stop_catch_error_log();
        if ($e) {
            $this->assertEquals(Array($test['expected']), $e);
        } else {
            $this->markTestIncomplete('skip HHVM');
        }
    }

    public function renderErrorProvider()
    {
        $errorCases = Array(
             Array(
                 'template' => '{{{foo}}}',
                 'expected' => 'LCRun3: [foo] is not exist',
             ),
             Array(
                 'template' => '{{foo}}',
                 'options' => Array(
                     'hbhelpers' => Array(
                         'foo' => function () {
                             return 1/0;
                         }
                     ),
                 ),
                 'expected' => 'LCRun3: call custom helper \'foo\' error: Division by zero',
             ),
        );

        return array_map(function($i) {
            if (!isset($i['options'])) {
                $i['options'] = Array('flags' => LightnCandy::FLAG_RENDER_DEBUG);
            }
            if (!isset($i['options']['flags'])) {
                $i['options']['flags'] = LightnCandy::FLAG_RENDER_DEBUG;
            }
            return Array($i);
        }, $errorCases);
    }

    /**
     * @dataProvider errorProvider
     */
    public function testErrors($test)
    {
        global $tmpdir;

        $php = LightnCandy::compile($test['template'], $test['options']);
        $context = LightnCandy::getContext();

        // This case should be compiled without error
        if (!isset($test['expected'])) {
            return;
        }

        $this->assertEquals($test['expected'], $context['error'], "Code: $php");
    }

    public function errorProvider()
    {
        $errorCases = Array(
             Array(
                 'template' => '{{testerr1}}}',
                 'expected' => 'Bad token {{testerr1}}} ! Do you mean {{testerr1}} or {{{testerr1}}}?',
             ),
             Array(
                 'template' => '{{{testerr2}}',
                 'expected' => 'Bad token {{{testerr2}} ! Do you mean {{testerr2}} or {{{testerr2}}}?',
             ),
             Array(
                 'template' => '{{{#testerr3}}}',
                 'expected' => 'Bad token {{{#testerr3}}} ! Do you mean {{#testerr3}} ?',
             ),
             Array(
                 'template' => '{{{!testerr4}}}',
                 'expected' => 'Bad token {{{!testerr4}}} ! Do you mean {{!testerr4}} ?',
             ),
             Array(
                 'template' => '{{{^testerr5}}}',
                 'expected' => 'Bad token {{{^testerr5}}} ! Do you mean {{^testerr5}} ?',
             ),
             Array(
                 'template' => '{{{/testerr6}}}',
                 'expected' => 'Bad token {{{/testerr6}}} ! Do you mean {{/testerr6}} ?',
             ),
             Array(
                 'template' => '{{win[ner.test1}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming in {{win[ner.test1}}',
             ),
             Array(
                 'template' => '{{win]ner.test2}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'win]ner.test2\' in {{win]ner.test2}} !',
             ),
             Array(
                 'template' => '{{wi[n]ner.test3}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'wi[n]ner.test3\' in {{wi[n]ner.test3}} !',
             ),
             Array(
                 'template' => '{{winner].[test4]}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'winner].[test4]\' in {{winner].[test4]}} !',
             ),
             Array(
                 'template' => '{{winner[.test5]}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'winner[.test5]\' in {{winner[.test5]}} !',
             ),
             Array(
                 'template' => '{{winner.[.test6]}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
             ),
             Array(
                 'template' => '{{winner.[#te.st7]}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
             ),
             Array(
                 'template' => '{{test8}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
             ),
             Array(
                 'template' => '{{test9]}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'test9]\' in {{test9]}} !',
             ),
             Array(
                 'template' => '{{testA[}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming in {{testA[}}',
             ),
             Array(
                 'template' => '{{[testB}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming in {{[testB}}',
             ),
             Array(
                 'template' => '{{]testC}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \']testC\' in {{]testC}} !',
             ),
             Array(
                 'template' => '{{[testD]}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
             ),
             Array(
                 'template' => '{{te]stE}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'te]stE\' in {{te]stE}} !',
             ),
             Array(
                 'template' => '{{tee[stF}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming in {{tee[stF}}',
             ),
             Array(
                 'template' => '{{te.e[stG}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming in {{te.e[stG}}',
             ),
             Array(
                 'template' => '{{te.e]stH}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'te.e]stH\' in {{te.e]stH}} !',
             ),
             Array(
                 'template' => '{{te.e[st.endI}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming in {{te.e[st.endI}}',
             ),
             Array(
                 'template' => '{{te.e]st.endJ}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'te.e]st.endJ\' in {{te.e]st.endJ}} !',
             ),
             Array(
                 'template' => '{{te.[est].endK}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
             ),
             Array(
                 'template' => '{{te.t[est].endL}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'te.t[est].endL\' in {{te.t[est].endL}} !',
             ),
             Array(
                 'template' => '{{te.t[est]o.endM}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'te.t[est]o.endM\' in {{te.t[est]o.endM}} !',
             ),
             Array(
                 'template' => '{{te.[est]o.endN}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
                 'expected' => 'Wrong variable naming as \'te.[est]o.endN\' in {{te.[est]o.endN}} !',
             ),
             Array(
                 'template' => '{{te.[e.st].endO}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
             ),
             Array(
                 'template' => '{{te.[e.s[t].endP}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
             ),
             Array(
                 'template' => '{{te.[e[s.t].endQ}}',
                 'options' => Array('flags' => LightnCandy::FLAG_ADVARNAME),
             ),
             Array(
                 'template' => '{{helper}}',
                 'options' => Array('helpers' => Array(
                     'helper' => Array('bad input'),
                 )),
                 'expected' => 'I found an array in helpers with key as helper, please fix it.',
             ),
             Array(
                 'template' => '<ul>{{#each item}}<li>{{name}}</li>',
                 'expected' => 'Unclosed token {{{#each item}}} !!',
             ),
             Array(
                 'template' => 'issue63: {{test_join}} Test! {{this}} {{/test_join}}',
                 'expected' => 'Unexpect token: {{/test_join}} !',
             ),
             Array(
                 'template' => '{{#if a}}TEST{{/with}}',
                 'options' => Array('flags' => LightnCandy::FLAG_WITH),
                 'expected' => 'Unexpect token: {{/with}} !',
             ),
             Array(
                 'template' => '{{#foo}}error{{/bar}}',
                 'expected' => 'Unexpect token {{/bar}} ! Previous token {{#[foo]}} is not closed',
             ),
             Array(
                 'template' => '{{../foo}}',
                 'expected' => 'Do not support {{../var}}, you should do compile with LightnCandy::FLAG_PARENT flag',
             ),
             Array(
                 'template' => '{{..}}',
                 'expected' => 'Do not support {{../var}}, you should do compile with LightnCandy::FLAG_PARENT flag',
             ),
             Array(
                 'template' => '{{test_join [a]=b}}',
                 'options' => Array(
                     'flags' => LightnCandy::FLAG_NAMEDARG,
                     'helpers' => Array('test_join')
                 ),
                 'expected' => "Wrong argument name as '[a]' in {{test_join [a]=b}} ! You should fix your template or compile with LightnCandy::FLAG_ADVARNAME flag.",
             ),
             Array(
                 'template' => '{{a=b}}',
                 'options' => Array('flags' => LightnCandy::FLAG_NAMEDARG),
                 'expected' => 'Do not support name=value in {{a=b}}, you should use it after a custom helper.',
             ),
             Array(
                 'template' => '{{test a=b}}',
                 'options' => Array('flags' => LightnCandy::FLAG_NAMEDARG),
                 'expected' => 'Do not support name=value in {{test a=b}}, maybe you missing the custom helper?',
             ),
             Array(
                 'template' => '{{#test a=b}}YA~{{/test}}',
                 'options' => Array('flags' => LightnCandy::FLAG_NAMEDARG),
                 'expected' => 'Do not support name=value in {{#test a=b}}, maybe you missing the block custom helper?',
             ),
             Array(
                 'template' => '{{#foo}}1{{^}}2{{/foo}}',
                 'expected' => 'Do not support {{^}}, you should do compile with LightnCandy::FLAG_ELSE flag',
             ),
             Array(
                 'template' => '{{#with a}OK!{{/with}}',
                 'options' => Array('flags' => LightnCandy::FLAG_WITH),
                 'expected' => 'Unclosed token {{{#with a}OK!{{/with}}} !!',
             ),
             Array(
                 'template' => '{{#each a}OK!{{/each}}',
                 'expected' => 'Unclosed token {{{#each a}OK!{{/each}}} !!',
             ),
             Array(
                 'template' => '{{#with items}}OK!{{/with}}',
                 'options' => Array('flags' => LightnCandy::FLAG_WITH),
             ),
             Array(
                 'template' => '{{#with}}OK!{{/with}}',
                 'options' => Array('flags' => LightnCandy::FLAG_WITH),
                 'expected' => 'No argument after {{#with}} !',
             ),
             Array(
                 'template' => '{{>not_found}}',
                 'expected' => "Can not find partial file for 'not_found', you should set correct basedir and fileext in options",
             ),
             Array(
                 'template' => '{{>tests/test1 foo}}',
                 'options' => Array('basedir' => '.'),
                 'expected' => 'Do not support {{>tests/test1 [foo]}}, you should do compile with LightnCandy::FLAG_RUNTIMEPARTIAL flag',
             ),
             Array(
                 'template' => '{{#with foo}}ABC{{/with}}',
                 'expected' => 'Do not support {{#with var}}, you should do compile with LightnCandy::FLAG_WITH flag',
             ),
             Array(
                 'template' => '{{abc}}',
                 'options' => Array('helpers' => Array('abc')),
                 'expected' => 'Can not find custom helper function defination abc() !',
             ),
             Array(
                 'template' => '{{=~= =~=}}',
                 'expected' => "Can not set delimiter contains '=' , you try to set delimiter as '~=' and '=~'.",
             ),
             Array(
                 'template' => '{{>recursive}}',
                 'options' => Array('basedir' => 'tests', 'flags' => LightnCandy::FLAG_WITH),
                 'expected' => Array(
                     'I found recursive partial includes as the path: recursive -> recursive! You should fix your template or compile with LightnCandy::FLAG_RUNTIMEPARTIAL flag.',
                     "Skip rendering partial 'recursive' again due to recursive detected",
                 )
             ),
             Array(
                 'template' => '{{test_join (foo bar)}}',
                 'options' => Array(
                     'flags' => LightnCandy::FLAG_ADVARNAME,
                     'helpers' => Array('test_join'),
                 ),
                 'expected' => "Can not find custom helper function defination foo() !",
             ),
             Array(
                 'template' => '{{1 + 2}}',
                 'options' => Array(
                     'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                     'helpers' => Array('test_join'),
                 ),
                 'expected' => "Wrong variable naming as '+' in {{1 + 2}} ! You should wrap ! \" # % & ' * + , ; < = > { | } ~ into [ ]",
             ),
            Array(
                'template' => '{{> (foo) bar}}',
                'options' => Array(
                    'flags' => LightnCandy::FLAG_HANDLEBARSJS,
                    'basedir' => '.',
                ),
                'expected' => Array(
                    "Can not find custom helper function defination foo() !",
                    "You use dynamic partial name as '(foo)', this only works with option FLAG_RUNTIMEPARTIAL enabled",
                )
            ),
        );

        return array_map(function($i) {
            if (!isset($i['options'])) {
                $i['options'] = Array('flags' => 0);
            }
            if (!isset($i['options']['flags'])) {
                $i['options']['flags'] = 0;
            }
            if (isset($i['expected']) && !is_array($i['expected'])) {
                $i['expected'] = Array($i['expected']);
            }
            return Array($i);
        }, $errorCases);
    }
}


?>
