<?php
/*

Copyrights for code authored by Yahoo! Inc. is licensed under the following terms:
MIT License
Copyright (c) 2013-2015 Yahoo! Inc. All Rights Reserved.
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Origin: https://github.com/zordius/lightncandy
*/

/**
 * This is abstract engine which defines must-have methods.
 *
 * @package    LightnCandy
 * @author     Zordius <zordius@yahoo-inc.com>
 */

/**
 * LightnCandy static core class.
 */
class LightnCandy {
    // Compile time error handling flags
    const FLAG_ERROR_LOG = 1;
    const FLAG_ERROR_EXCEPTION = 2;
    const FLAG_ERROR_SKIPPARTIAL = 4194304;

    // Compile the template as standalone PHP code which can execute without including LightnCandy
    const FLAG_STANDALONE = 4;
    const FLAG_BARE = 33554432;
    const FLAG_NOESCAPE = 67108864;

    // JavaScript compatibility
    const FLAG_JSTRUE = 8;
    const FLAG_JSOBJECT = 16;

    // Handlebars.js compatibility
    const FLAG_THIS = 32;
    const FLAG_WITH = 64;
    const FLAG_PARENT = 128;
    const FLAG_JSQUOTE = 256;
    const FLAG_ADVARNAME = 512;
    const FLAG_SPACECTL = 1024;
    const FLAG_NAMEDARG = 2048;
    const FLAG_SPVARS = 4096;
    const FLAG_SLASH = 8388608;
    const FLAG_ELSE = 16777216;

    // PHP behavior flags
    const FLAG_EXTHELPER = 8192;
    const FLAG_ECHO = 16384;
    const FLAG_PROPERTY = 32768;
    const FLAG_METHOD = 65536;
    const FLAG_RUNTIMEPARTIAL = 1048576;

    // Mustache compatibility
    const FLAG_MUSTACHESP = 131072;
    const FLAG_MUSTACHELOOKUP = 262144;
    const FLAG_MUSTACHEPAIN = 2097152;

    // Template rendering time debug flags
    const FLAG_RENDER_DEBUG = 524288;

    // alias flags
    const FLAG_BESTPERFORMANCE = 16384; // FLAG_ECHO
    const FLAG_JS = 24; // FLAG_JSTRUE + FLAG_JSOBJECT
    const FLAG_MUSTACHE = 6684672; // FLAG_ERROR_SKIPPARTIAL + FLAG_MUSTACHESP + FLAG_MUSTACHELOOKUP + FLAG_MUSTACHEPAIN
    const FLAG_HANDLEBARS = 27402208; // FLAG_THIS + FLAG_WITH + FLAG_PARENT + FLAG_JSQUOTE + FLAG_ADVARNAME + FLAG_SPACECTL + FLAG_NAMEDARG + FLAG_SPVARS + FLAG_SLASH + FLAG_ELSE + FLAG_MUSTACHESP + FLAG_MUSTACHEPAIN
    const FLAG_HANDLEBARSJS = 27402232; // FLAG_JS + FLAG_HANDLEBARS
    const FLAG_INSTANCE = 98304; // FLAG_PROPERTY + FLAG_METHOD

    // RegExps
    const VARNAME_SEARCH = '/(\\[[^\\]]+\\]|[^\\[\\]\\.]+)/';
    const EXTENDED_COMMENT_SEARCH = '/{{!--.*?--}}/s';
    const IS_SUBEXP_SEARCH = '/^\(.+\)$/s';

    // Positions of matched token
    const POS_LOTHER = 1;
    const POS_LSPACE = 2;
    const POS_BEGINTAG = 3;
    const POS_LSPACECTL = 4;
    const POS_OP = 5;
    const POS_INNERTAG = 6;
    const POS_RSPACECTL = 7;
    const POS_ENDTAG = 8;
    const POS_RSPACE = 9;
    const POS_ROTHER = 10;

    protected static $lastContext;

    /**
     * Compile handlebars template into PHP code.
     *
     * @param string $template handlebars template string
     * @param array<string,array|string|integer> $options LightnCandy compile time and run time options, default is array('flags' => LightnCandy::FLAG_BESTPERFORMANCE)
     *
     * @return string|false Compiled PHP code when successed. If error happened and compile failed, return false.
     */
    public static function compile($template, $options = array('flags' => self::FLAG_BESTPERFORMANCE)) {
        $context = static::buildContext($options);

        if (static::handleError($context)) {
            return false;
        }

        // Strip extended comments
        $template = preg_replace(static::EXTENDED_COMMENT_SEARCH, '{{! }}', $template);

        // Do first time scan to find out used feature, detect template error.
        static::setupToken($context);
        static::verifyTemplate($context, $template);

        if (static::handleError($context)) {
            return false;
        }

        $context['scan'] = false;

        // Do PHP code generation.
        static::setupToken($context);

        // Handle dynamic partials
        static::handleDynamicPartial($context);

        $code = static::compileTemplate($context, static::escapeTemplate($template));

        // return false when fatal error
        if (static::handleError($context)) {
            return false;
        }

        // Or, return full PHP render codes as string
        return static::composePHPRender($context, $code);
    }

    /**
     * Include all partials when using dynamic partials
     */
    protected static function handleDynamicPartial(&$context) {
        if ($context['usedFeature']['dynpartial'] == 0) {
            return;
        }

        foreach ($context['partials'] as $name => $code) {
            static::readPartial($name, $context);
        }
    }

    /**
     * Escape template
     *
     * @param string $template handlebars template string
     *
     * @return string Escaped template
     *
     * @expect 'abc' when input 'abc'
     * @expect 'a\\\\bc' when input 'a\bc'
     * @expect 'a\\\'bc' when input 'a\'bc'
     */
    protected static function escapeTemplate($template) {
        return addcslashes(addcslashes($template, '\\'), "'");
    }

    /**
     * Setup token delimiter by default or provided string
     *
     * @param array<string,array|string|integer> $context Current context
     * @param string $left left string of a token
     * @param string $right right string of a token
     */
    protected static function setupToken(&$context, $left = '{{', $right = '}}') {
        if (preg_match('/=/', "$left$right")) {
            $context['error'][] = "Can not set delimiter contains '=' , you try to set delimiter as '$left' and '$right'.";
            return;
        }

        $context['tokens']['startchar'] = substr($left, 0, 1);
        $context['tokens']['left'] = $left;
        $context['tokens']['right'] = $right;

        if (($left === '{{') && ($right === '}}')) {
            $left = '\\{{2,3}';
            $right = '\\}{2,3}';
        } else {
            $left = preg_quote($left);
            $right = preg_quote($right);
        }

        $context['tokens']['search'] = "/^(.*?)(\\s*)($left)(~?)([\\^#\\/!&>]?)(.*?)(~?)($right)(\\s*)(.*)\$/s";
    }

    /**
     * Verify template and scan for used features
     *
     * @param array<string,array|string|integer> $context Current context
     * @param string $template handlebars template
     */
    protected static function verifyTemplate(&$context, $template) {
        while (preg_match($context['tokens']['search'], $template, $matches)) {
            $context['tokens']['count']++;
            static::scanFeatures($matches, $context);
            $template = $matches[self::POS_ROTHER];
        }
    }

    /**
     * Compile template into PHP code (internal method)
     *
     * @param array<string,array|string|integer> $context Current context
     * @param string $template handlebars template
     * @param string $partial partial name when $template is come from the template
     *
     * @return string generated PHP code
     */
    protected static function compileTemplate(&$context, $template, $partial = '') {
        // Check for recursive partial
        if ($partial && !$context['flags']['runpart']) {
            $context['partialStack'][] = $partial;
            $diff = count($context['partialStack']) - count(array_unique($context['partialStack']));
            if ($diff > 1) {
                $context['error'][] = "Skip rendering partial '$partial' again due to recursive detected";
                return '';
            }
            if ($diff) {
                $context['error'][] = 'I found recursive partial includes as the path: ' . implode(' -> ', $context['partialStack']) . '! You should fix your template or compile with LightnCandy::FLAG_RUNTIMEPARTIAL flag.';
            }
        }

        $code = '';
        while (preg_match($context['tokens']['search'], $template, $matches)) {
            // Skip a token when it is slash escaped
            if ($context['flags']['slash'] && ($matches[self::POS_LSPACE] === '') && preg_match('/^(.*?)(\\\\+)$/s', $matches[self::POS_LOTHER], $escmatch)) {
                if (strlen($escmatch[2]) % 4) {
                    $code .= substr($matches[self::POS_LOTHER], 0, -2) . $context['tokens']['startchar'];
                    $matches[self::POS_BEGINTAG] = substr($matches[self::POS_BEGINTAG], 1);
                    $template = implode('', array_slice($matches, self::POS_BEGINTAG));
                    continue;
                } else {
                    $matches[self::POS_LOTHER] = $escmatch[1] . str_repeat('\\', strlen($escmatch[2]) / 2);
                }
            }

            $context['tokens']['current']++;
            $tmpl = static::compileToken($matches, $context);
            if ($tmpl == $context['ops']['seperator']) {
                $tmpl = '';
            } else {
                $tmpl = "'$tmpl'";
            }
            $code .= "{$matches[self::POS_LOTHER]}{$matches[self::POS_LSPACE]}$tmpl";
            $template = "{$matches[self::POS_RSPACE]}{$matches[self::POS_ROTHER]}";
        }

        if ($partial && !$context['flags']['runpart']) {
            array_pop($context['partialStack']);
        }

        return "$code$template";
    }

    /**
     * Compose LightnCandy render codes for include()
     *
     * @param array<string,array|string|integer> $context Current context
     * @param string $code generated PHP code
     *
     * @return string Composed PHP code
     */
    protected static function composePHPRender($context, $code) {
        $flagJStrue = static::getBoolStr($context['flags']['jstrue']);
        $flagJSObj = static::getBoolStr($context['flags']['jsobj']);
        $flagSPVar = static::getBoolStr($context['flags']['spvar']);
        $flagProp = static::getBoolStr($context['flags']['prop']);
        $flagMethod = static::getBoolStr($context['flags']['method']);
        $flagMustlok = static::getBoolStr($context['flags']['mustlok']);
        $flagEcho = static::getBoolStr($context['flags']['echo']);

        $libstr = static::exportLCRun($context);
        $constants = static::exportLCRunConstant($context);
        $helpers = static::exportHelper($context);
        $bhelpers = static::exportHelper($context, 'blockhelpers');
        $hbhelpers = static::exportHelper($context, 'hbhelpers');
        $debug = LCRun3::DEBUG_ERROR_LOG;
        $phpstart = $context['flags']['bare'] ? '' : '<?php ';
        $phpend = $context['flags']['bare'] ? '' : "\n?>";

        // Return generated PHP code string.
        return "{$phpstart}return function (\$in, \$debugopt = $debug) {
    \$cx = array(
        'flags' => array(
            'jstrue' => $flagJStrue,
            'jsobj' => $flagJSObj,
            'spvar' => $flagSPVar,
            'prop' => $flagProp,
            'method' => $flagMethod,
            'mustlok' => $flagMustlok,
            'echo' => $flagEcho,
            'debug' => \$debugopt,
        ),
        'constants' => $constants,
        'helpers' => $helpers,
        'blockhelpers' => $bhelpers,
        'hbhelpers' => $hbhelpers,
        'partials' => array({$context['partialCode']}),
        'scopes' => array(),
        'sp_vars' => array('root' => \$in),
        'lcrun' => '{$context['lcrun']}',
$libstr
    );
    {$context['renderex']}
    {$context['ops']['op_start']}'$code'{$context['ops']['op_end']}
}$phpend";
    }

    /**
     * Build context from options
     *
     * @param array<string,array|string|integer> $options input options
     *
     * @return array<string,array|string|integer> Context from options
     */
    protected static function buildContext($options) {
        if (!is_array($options)) {
            $options = array();
        }

        $flags = isset($options['flags']) ? $options['flags'] : self::FLAG_BESTPERFORMANCE;

        $context = array(
            'flags' => array(
                'errorlog' => $flags & self::FLAG_ERROR_LOG,
                'exception' => $flags & self::FLAG_ERROR_EXCEPTION,
                'skippartial' => $flags & self::FLAG_ERROR_SKIPPARTIAL,
                'standalone' => $flags & self::FLAG_STANDALONE,
                'bare' => $flags & self::FLAG_BARE,
                'noesc' => $flags & self::FLAG_NOESCAPE,
                'jstrue' => $flags & self::FLAG_JSTRUE,
                'jsobj' => $flags & self::FLAG_JSOBJECT,
                'jsquote' => $flags & self::FLAG_JSQUOTE,
                'this' => $flags & self::FLAG_THIS,
                'with' => $flags & self::FLAG_WITH,
                'parent' => $flags & self::FLAG_PARENT,
                'echo' => $flags & self::FLAG_ECHO,
                'advar' => $flags & self::FLAG_ADVARNAME,
                'namev' => $flags & self::FLAG_NAMEDARG,
                'spvar' => $flags & self::FLAG_SPVARS,
                'slash' => $flags & self::FLAG_SLASH,
                'else' => $flags & self::FLAG_ELSE,
                'exhlp' => $flags & self::FLAG_EXTHELPER,
                'mustsp' => $flags & self::FLAG_MUSTACHESP,
                'mustlok' => $flags & self::FLAG_MUSTACHELOOKUP,
                'mustpi' => $flags & self::FLAG_MUSTACHEPAIN,
                'debug' => $flags & self::FLAG_RENDER_DEBUG,
                'prop' => $flags & self::FLAG_PROPERTY,
                'method' => $flags & self::FLAG_METHOD,
                'runpart' => $flags & self::FLAG_RUNTIMEPARTIAL,
            ),
            'level' => 0,
            'scan' => true,
            'stack' => array(),
            'error' => array(),
            'basedir' => static::buildCXBasedir($options),
            'fileext' => static::buildCXFileext($options),
            'tokens' => array(
                'standalone' => true,
                'ahead' => false,
                'current' => 0,
                'count' => 0,
                'partialind' => '',
            ),
            'usedPartial' => array(),
            'partialStack' => array(),
            'partialCode' => '',
            'usedFeature' => array(
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
            ),
            'usedCount' => array(
                'var' => array(),
                'helpers' => array(),
                'blockhelpers' => array(),
                'hbhelpers' => array(),
                'lcrun' => array(),
            ),
            'partials' => (isset($options['partials']) && is_array($options['partials'])) ? $options['partials'] : array(),
            'helpers' => array(),
            'blockhelpers' => array(),
            'hbhelpers' => array(),
            'renderex' => isset($options['renderex']) ? $options['renderex'] : '',
            'lcrun' => isset($options['lcrun']) ? $options['lcrun'] : 'LCRun3',
        );

        $context['ops'] = $context['flags']['echo'] ? array(
            'seperator' => ',',
            'f_start' => 'echo ',
            'f_end' => ';',
            'op_start' => 'ob_start();echo ',
            'op_end' => ';return ob_get_clean();',
            'cnd_start' => ';if ',
            'cnd_then' => '{echo ',
            'cnd_else' => ';}else{echo ',
            'cnd_end' => ';}echo ',
        ) : array(
            'seperator' => '.',
            'f_start' => 'return ',
            'f_end' => ';',
            'op_start' => 'return ',
            'op_end' => ';',
            'cnd_start' => '.(',
            'cnd_then' => ' ? ',
            'cnd_else' => ' : ',
            'cnd_end' => ').',
        );

        $context['ops']['enc'] = $context['flags']['jsquote'] ? 'encq' : 'enc';
        $context = static::buildHelperTable($context, $options);
        $context = static::buildHelperTable($context, $options, 'blockhelpers');
        $context = static::buildHelperTable($context, $options, 'hbhelpers');

        return $context;
    }

    /**
     * Build custom helper table
     *
     * @param array<string,array|string|integer> $context prepared context
     * @param array<string,array|string|integer> $options input options
     * @param string $tname helper table name
     *
     * @return array<string,array|string|integer> context with generated helper table
     *
     * @expect array() when input array(), array()
     * @expect array('flags' => array('exhlp' => 1)) when input array('flags' => array('exhlp' => 1)), array('helpers' => array('abc'))
     * @expect array('error' => array('Can not find custom helper function defination abc() !'), 'flags' => array('exhlp' => 0)) when input array('error' => array(), 'flags' => array('exhlp' => 0)), array('helpers' => array('abc'))
     * @expect array('flags' => array('exhlp' => 1), 'helpers' => array('LCRun3::raw' => 'LCRun3::raw')) when input array('flags' => array('exhlp' => 1), 'helpers' => array()), array('helpers' => array('LCRun3::raw'))
     * @expect array('flags' => array('exhlp' => 1), 'helpers' => array('test' => 'LCRun3::raw')) when input array('flags' => array('exhlp' => 1), 'helpers' => array()), array('helpers' => array('test' => 'LCRun3::raw'))
     */
    protected static function buildHelperTable($context, $options, $tname = 'helpers') {
        if (isset($options[$tname]) && is_array($options[$tname])) {
            foreach ($options[$tname] as $name => $func) {
                if (is_callable($func)) {
                    $context[$tname][is_int($name) ? $func : $name] = $func;
                } else {
                    if (is_array($func)) {
                        $context['error'][] = "I found an array in $tname with key as $name, please fix it.";
                    } else {
                        if (!$context['flags']['exhlp']) {
                            $context['error'][] = "Can not find custom helper function defination $func() !";
                        }
                    }
                }
            }
        }
        return $context;
    }

    /**
     * Read partial file content as string and store in context
     *
     * @param string $name partial name
     * @param array<string,array|string|integer> $context Current context of compiler progress.
     */
    protected static function readPartial($name, &$context) {
        $context['usedFeature']['partial']++;

        if (isset($context['usedPartial'][$name])) {
            return;
        }

        $cnt = static::resolvePartial($name, $context);

        if ($cnt !== null) {
            return static::compilePartial($name, $context, $cnt);
        }

        if (preg_match(static::IS_SUBEXP_SEARCH, $name)) {
            if ($context['flags']['runpart']) {
                $context['usedFeature']['dynpartial']++;
                return;
            } else {
                $context['error'][] = "You use dynamic partial name as '$name', this only works with option FLAG_RUNTIMEPARTIAL enabled";
                return;
            }
        }

        if (!$context['flags']['skippartial']) {
            $context['error'][] = "Can not find partial file for '$name', you should set correct basedir and fileext in options";
        }
    }

    /**
     * locate partial file, return the file name
     *
     * @param string $name partial name
     * @param array<string,array|string|integer> $context Current context of compiler progress.
     *
     * @return string|null $content partial content
     */
    protected static function resolvePartial(&$name, &$context) {
        if (isset($context['partials'][$name])) {
            return $context['partials'][$name];
        }

        foreach ($context['basedir'] as $dir) {
            foreach ($context['fileext'] as $ext) {
                $fn = "$dir/$name$ext";
                if (file_exists($fn)) {
                    return file_get_contents($fn);
                }
            }
        }
        return null;
    }

    /**
     * compile partial file, stored in context
     *
     * @param string $name partial name
     * @param array<string,array|string|integer> $context Current context of compiler progress.
     * @param string $content partial content
     */
    protected static function compilePartial(&$name, &$context, $content) {
        $context['usedPartial'][$name] = static::escapeTemplate($content);

        $originalAhead = $context['tokens']['ahead'];
        $tmpContext = $context;
        $tmpContext['level'] = 0;
        static::setupToken($tmpContext);

        static::verifyTemplate($tmpContext, $content);
        $originalToken = $context['tokens'];
        $context = $tmpContext;
        $context['tokens'] = $originalToken;
        $context['tokens']['ahead'] = $originalAhead;

        if ($context['flags']['runpart']) {
            $code = static::compileTemplate($context, $context['usedPartial'][$name], $name);
            if ($context['flags']['mustpi']) {
                $sp = ', $sp';
                $code = preg_replace('/^/m', "'{$context['ops']['seperator']}\$sp{$context['ops']['seperator']}'", $code);
                // callbacks inside partial should be aware of $sp
                $code = preg_replace('/\bfunction\s*\((.*?)\)\s*{/', 'function(\\1)use($sp){', $code);
            } else {
                $sp = '';
            }
            $context['partialCode'] .= "'$name' => function (\$cx, \$in{$sp}) {{$context['ops']['op_start']}'$code'{$context['ops']['op_end']}},";
        }
    }

    /**
     * Internal method used by compile(). Check options and handle fileext.
     *
     * @param array<string,array|string|integer> $options current compile option
     *
     * @return array<string> file extensions
     *
     * @expect array('.tmpl') when input array()
     * @expect array('test') when input array('fileext' => 'test')
     * @expect array('test1') when input array('fileext' => array('test1'))
     * @expect array('test2', 'test3') when input array('fileext' => array('test2', 'test3'))
     */
    protected static function buildCXFileext($options) {
        $exts = isset($options['fileext']) ? $options['fileext'] : '.tmpl';
        return is_array($exts) ? $exts : array($exts);
    }

    /**
     * Internal method used by compile(). Check options and handle basedir.
     *
     * @param array<string,array|string|integer> $options current compile option
     *
     * @return array<string> base directories
     *
     * @expect array() when input array()
     * @expect array() when input array('basedir' => array())
     * @expect array('src') when input array('basedir' => array('src'))
     * @expect array('src') when input array('basedir' => array('src', 'dir_not_found'))
     * @expect array('src', 'tests') when input array('basedir' => array('src', 'tests'))
     */
    protected static function buildCXBasedir($options) {
        $dirs = isset($options['basedir']) ? $options['basedir'] : 0;
        $dirs = is_array($dirs) ? $dirs : array($dirs);
        $ret = array();

        foreach ($dirs as $dir) {
            if (is_string($dir) && is_dir($dir)) {
                $ret[] = $dir;
            }
        }

        return $ret;
    }

    /**
     * Internal method used by compile(). Get PHP code from a closure of function as string.
     *
     * @param object $closure Closure object
     *
     * @return string
     *
     * @expect 'function($a) {return;}' when input function ($a) {return;}
     * @expect 'function($a) {return;}' when input    function ($a) {return;}
     */
    protected static function getPHPCode($closure) {
        if (is_string($closure) && preg_match('/(.+)::(.+)/', $closure, $matched)) {
            $ref = new ReflectionMethod($matched[1], $matched[2]);
        } else {
            $ref = new ReflectionFunction($closure);
        }
        $fname = $ref->getFileName();

        $lines = file_get_contents($fname);
        $file = new SplFileObject($fname);
        $file->seek($ref->getStartLine() - 2);
        $spos = $file->ftell();
        $file->seek($ref->getEndLine() - 1);
        $epos = $file->ftell();

        return preg_replace('/^.*?function(\s+[^\s\\(]+?)?\s*?\\((.+?)\\}[,\\s]*;?$/s', 'function($2}', substr($lines, $spos, $epos - $spos));
    }

    /**
     * Internal method used by compile(). Export required custom helper functions.
     *
     * @param string $tname   helper table name
     * @param array<string,array|string|integer> $context current compile context
     *
     * @return string
     */
    protected static function exportHelper($context, $tname = 'helpers') {
        $ret = '';
        foreach ($context[$tname] as $name => $func) {
            if (!isset($context['usedCount'][$tname][$name])) {
                continue;
            }
            if ((is_object($func) && ($func instanceof Closure)) || ($context['flags']['exhlp'] == 0)) {
                $ret .= ("            '$name' => " . static::getPHPCode($func) . ",\n");
                continue;
            }
            $ret .= "            '$name' => '$func',\n";
        }

        return "array($ret)";
    }

    /**
     * Internal method used by compile(). Export required standalone functions.
     *
     * @param array<string,array|string|integer> $context current compile context
     *
     * @return string
     */
    protected static function exportLCRun($context) {
        if ($context['flags']['standalone'] == 0) {
            return '';
        }

        $class = new ReflectionClass($context['lcrun']);
        $fname = $class->getFileName();
        $lines = file_get_contents($fname);
        $file = new SplFileObject($fname);
        $methods = array();
        $ret = "'funcs' => array(\n";

        foreach ($class->getMethods() as $method) {
            $name = $method->getName();
            $file->seek($method->getStartLine() - 2);
            $spos = $file->ftell();
            $file->seek($method->getEndLine() - 2);
            $epos = $file->ftell();
            $methods[$name] = static::scanLCRunDependency($context, preg_replace('/public static function (.+)\\(/', '\'$1\' => function (', substr($lines, $spos, $epos - $spos)));
        }
        unset($file);

        $exports = array_keys($context['usedCount']['lcrun']);

        while (true) {
            if (array_sum(array_map(function ($name) use (&$exports, $methods) {
                $n = 0;
                foreach ($methods[$name][1] as $child => $count) {
                    if (!in_array($child, $exports)) {
                       $exports[] = $child;
                       $n++;
                    }
                }
                return $n;
            }, $exports)) == 0) {
                break;
            }
        }

        foreach ($exports as $export) {
            $ret .= ($methods[$export][0] . "    },\n");
        }

        return "$ret)\n";
    }

    /**
     * Internal method used by compile(). Export standalone constants.
     *
     * @param array<string,array|string|integer> $context current compile context
     *
     * @return string
     */
    protected static function exportLCRunConstant($context) {
        if ($context['flags']['standalone'] == 0) {
            return 'array()';
        }

        $class = new ReflectionClass($context['lcrun']);
        $constants = $class->getConstants();
        $ret = " array(\n";
        foreach($constants as $name => $value) {
            $ret .= "            '$name' => ".  (is_string($value) ? "'$value'" : $value ) . ",\n";
        }
        $ret .= "        )";
        return $ret;
    }

    /**
     * Internal method used by compile(). Export required standalone functions.
     *
     * @param array<string,array|string|integer> $context current compile context
     * @param string $code PHP code string of the method
     *
     * @return array<string|array> list of converted code and children array
     */
    protected static function scanLCRunDependency($context, $code) {
        $child = array();

        $code = preg_replace_callback('/self::(\w+?)\s*\(/', function ($matches) use ($context, &$child) {
            if (!isset($child[$matches[1]])) {
                $child[$matches[1]] = 0;
            }
            $child[$matches[1]]++;

            return "\$cx['funcs']['{$matches[1]}'](";
        }, $code);

        // replace the constants
        $code = preg_replace('/self::([A-Z0-9_]+)/', "\$cx['constants']['$1']", $code);
        return array($code, $child);
    }

    /**
     * Internal method used by compile(). Handle exists error and return error status.
     *
     * @param array<string,array|string|integer> $context Current context of compiler progress.
     *
     * @throws Exception
     * @return boolean True when error detected
     *
     * @expect true when input array('level' => 1, 'stack' => array('X'), 'flags' => array('errorlog' => 0, 'exception' => 0), 'error' => array())
     * @expect false when input array('level' => 0, 'error' => array())
     * @expect true when input array('level' => 0, 'error' => array('some error'), 'flags' => array('errorlog' => 0, 'exception' => 0))
     */
    protected static function handleError(&$context) {
        if ($context['level'] > 0) {
            $token = array_pop($context['stack']);
            $context['error'][] = "Unclosed token {{{#$token}}} !!";
        }

        static::$lastContext = $context;

        if (count($context['error'])) {
            if ($context['flags']['errorlog']) {
                error_log(implode("\n", $context['error']));
            }
            if ($context['flags']['exception']) {
                throw new Exception(implode("\n", $context['error']));
            }
            return true;
        }
        return false;
    }

    /**
     * Internal method used by compile(). Return 'true' or 'false' string.
     *
     * @param integer $v value
     *
     * @return string 'true' when the value larger then 0
     *
     * @expect 'true' when input 1
     * @expect 'true' when input 999
     * @expect 'false' when input 0
     * @expect 'false' when input -1
     */
    protected static function getBoolStr($v) {
        return ($v > 0) ? 'true' : 'false';
    }

    /**
     * Get last compiler context.
     *
     * @return array<string,array|string|integer> Context data
     */
    public static function getContext() {
        return static::$lastContext;
    }

    /**
     * Get a working render function by a string of PHP code. This method may requires php setting allow_url_include=1 and allow_url_fopen=1 , or access right to tmp file system.
     *
     * @param string      $php PHP code
     * @param string|null $tmpDir Optional, change temp directory for php include file saved by prepare() when cannot include PHP code with data:// format.
     * @param boolean     $delete Optional, delete temp php file when set to tru. Default is true, set it to false for debug propose
     *
     * @return Closure|false result of include()
     *
     * @deprecated
     */
    public static function prepare($php, $tmpDir = null, $delete = true) {
        if (!ini_get('allow_url_include') || !ini_get('allow_url_fopen')) {
            if (!is_string($tmpDir) || !is_dir($tmpDir)) {
                $tmpDir = sys_get_temp_dir();
            }
        }

        if (is_dir($tmpDir)) {
            $fn = tempnam($tmpDir, 'lci_');
            if (!$fn) {
                error_log("Can not generate tmp file under $tmpDir!!\n");
                return false;
            }
            if (!file_put_contents($fn, $php)) {
                error_log("Can not include saved temp php code from $fn, you should add $tmpDir into open_basedir!!\n");
                return false;
            }

            $phpfunc = include($fn);

            if ($delete) {
                unlink($fn);
            }

            return $phpfunc;
        }

        return include('data://text/plain,' . urlencode($php));
    }

    /**
     * Internal method used by compile(). Get function name for standalone or none standalone template.
     *
     * @param array<string,array|string|integer> $context Current context of compiler progress.
     * @param string $name base function name
     * @param string $tag original handlabars tag for debug
     *
     * @return string compiled Function name
     *
     * @expect 'LCRun3::test(' when input array('flags' => array('standalone' => 0, 'debug' => 0), 'lcrun' => 'LCRun3'), 'test', ''
     * @expect 'LCRun3::test2(' when input array('flags' => array('standalone' => 0, 'debug' => 0), 'lcrun' => 'LCRun3'), 'test2', ''
     * @expect "\$cx['funcs']['test3'](" when input array('flags' => array('standalone' => 1, 'debug' => 0), 'lcrun' => 'LCRun3'), 'test3', ''
     * @expect 'LCRun3::debug(\'abc\', \'test\', ' when input array('flags' => array('standalone' => 0, 'debug' => 1), 'lcrun' => 'LCRun3'), 'test', 'abc'
     */
    protected static function getFuncName(&$context, $name, $tag) {
        static::addUsageCount($context, 'lcrun', $name);

        if ($context['flags']['debug'] && ($name != 'miss')) {
            $dbg = "'$tag', '$name', ";
            $name = 'debug';
            static::addUsageCount($context, 'lcrun', 'debug');
        } else {
            $dbg = '';
        }

        return $context['flags']['standalone'] ? "\$cx['funcs']['$name']($dbg" : "{$context['lcrun']}::$name($dbg";
    }

    /**
     * Internal method used by getArrayCode(). Get variable names translated string.
     *
     * @param array<string> $scopes an array of variable names with single quote
     *
     * @return string PHP array names string
     *
     * @expect '' when input array()
     * @expect '[a]' when input array('a')
     * @expect '[a][b][c]' when input array('a', 'b', 'c')
     */
    protected static function getArrayStr($scopes) {
        return count($scopes) ? '[' . implode('][', $scopes) . ']' : '';
    }

    /**
     * Internal method used by getVariableName(). Get variable names translated string.
     *
     * @param array<string> $list an array of variable names.
     *
     * @return string PHP array names string
     *
     * @expect '' when input array()
     * @expect "['a']" when input array('a')
     * @expect "['a']['b']['c']" when input array('a', 'b', 'c')
     */
    protected static function getArrayCode($list) {
        return static::getArrayStr(array_map(function ($v) {
            return "'$v'";
        }, $list));
    }

    /**
     * Internal method used by compile().
     *
     * @param array<array> $vn variable name array.
     * @param array<string,array|string|integer> $context current compile context
     *
     * @return array<string|array> variable names
     *
     * @expect array('array(array($in),array())', array('this')) when input array(null), array('flags'=>array('spvar'=>true))
     * @expect array('array(array($in,$in),array())', array('this', 'this')) when input array(null, null), array('flags'=>array('spvar'=>true))
     * @expect array('array(array(),array(\'a\'=>$in))', array('this')) when input array('a' => null), array('flags'=>array('spvar'=>true))
     */
    protected static function getVariableNames($vn, &$context) {
        $vars = array(array(), array());
        $exps = array();
        foreach ($vn as $i => $v) {
            $V = static::getVariableNameOrSubExpression($v, $context);
            if (is_string($i)) {
                $vars[1][] = "'$i'=>{$V[0]}";
            } else {
                $vars[0][] = $V[0];
            }
            $exps[] = $V[1];
        }
        return array('array(array(' . implode(',', $vars[0]) . '),array(' . implode(',', $vars[1]) . '))', $exps);
    }

    /**
     * Internal method used by compile().
     *
     * @param string $subExpression subExpression to compile
     * @param array<string,array|string|integer> $context current compile context
     * @param boolean $keepCount keep original usage count
     *
     * @return array<string> code representing passed expression
     */
    protected static function compileSubExpression($subExpression, &$context, $keepCount = false) {
        // mock up a token for this expression
        $token = array_fill(self::POS_LOTHER, self::POS_ROTHER, '');

        // strip outer ( ) from subexpression
        $token[self::POS_INNERTAG] = substr($subExpression, 1, -1);
        $oldCount = $context['usedFeature'];
        list(, $vars) = static::parseTokenArgs($token, $context);

        // no separator is needed, this code will be used as a function argument
        $origSeperator = $context['ops']['seperator'];
        $context['ops']['seperator'] = '';
        // override $raw, subexpressions are never escaped
        $ret = static::compileCustomHelper($context, $vars, true, true);
        $context['ops']['seperator'] = $origSeperator;

        if ($keepCount) {
            $context['usedFeature'] = $oldCount;
        } else {
            $context['usedFeature']['subexp']++;
            // detect handlebars custom helpers.
            if (isset($context['hbhelpers'][$vars[0][0]])) {
                $context['usedFeature']['hbhelper']++;
            } else {
                // detect custom helpers.
                if (isset($context['helpers'][$vars[0][0]])) {
                    $context['usedFeature']['helper']++;
                }
            }
        }

        return array($ret ? $ret : '', $subExpression);
    }

    /**
     * Internal method used by compile().
     *
     * @param array<array|string|integer> $var variable parsed path
     * @param array<array|string|integer> $context current compile context
     *
     * @return array<string> variable names
     */
    protected static function getVariableNameOrSubExpression($var, &$context) {
        if (isset($var[0]) && preg_match(static::IS_SUBEXP_SEARCH, $var[0])) {
            return static::compileSubExpression($var[0], $context, true);
        }
        return static::getVariableName($var, $context);
    }

    /**
     * Internal method used by compile().
     *
     * @param array<array|string|integer> $var variable parsed path
     * @param array<array|string|integer> $context current compile context
     *
     * @return array<string> variable names
     *
     * @expect array('$in', 'this') when input array(null), array('flags'=>array('spvar'=>true,'debug'=>0))
     * @expect array('((isset($in[\'true\']) && is_array($in)) ? $in[\'true\'] : null)', '[true]') when input array('true'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('((isset($in[\'false\']) && is_array($in)) ? $in[\'false\'] : null)', '[false]') when input array('false'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('true', 'true') when input array(0, 'true'), array('flags'=>array('spvar'=>true,'debug'=>0))
     * @expect array('false', 'false') when input array(0, 'false'), array('flags'=>array('spvar'=>true,'debug'=>0))
     * @expect array('((isset($in[\'2\']) && is_array($in)) ? $in[\'2\'] : null)', '[2]') when input array('2'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('2', '2') when input array(0, '2'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0))
     * @expect array('((isset($in[\'@index\']) && is_array($in)) ? $in[\'@index\'] : null)', '[@index]') when input array('@index'), array('flags'=>array('spvar'=>false,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array("((isset(\$cx['sp_vars']['index']) && is_array(\$cx['sp_vars'])) ? \$cx['sp_vars']['index'] : null)", '@[index]') when input array('@index'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array("((isset(\$cx['sp_vars']['key']) && is_array(\$cx['sp_vars'])) ? \$cx['sp_vars']['key'] : null)", '@[key]') when input array('@key'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array("((isset(\$cx['sp_vars']['first']) && is_array(\$cx['sp_vars'])) ? \$cx['sp_vars']['first'] : null)", '@[first]') when input array('@first'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array("((isset(\$cx['sp_vars']['last']) && is_array(\$cx['sp_vars'])) ? \$cx['sp_vars']['last'] : null)", '@[last]') when input array('@last'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('((isset($in[\'"a"\']) && is_array($in)) ? $in[\'"a"\'] : null)', '["a"]') when input array('"a"'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('"a"', '"a"') when input array(0, '"a"'), array('flags'=>array('spvar'=>true,'debug'=>0))
     * @expect array('((isset($in[\'a\']) && is_array($in)) ? $in[\'a\'] : null)', '[a]') when input array('a'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('((isset($cx[\'scopes\'][count($cx[\'scopes\'])-1][\'a\']) && is_array($cx[\'scopes\'][count($cx[\'scopes\'])-1])) ? $cx[\'scopes\'][count($cx[\'scopes\'])-1][\'a\'] : null)', '../[a]') when input array(1,'a'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('((isset($cx[\'scopes\'][count($cx[\'scopes\'])-3][\'a\']) && is_array($cx[\'scopes\'][count($cx[\'scopes\'])-3])) ? $cx[\'scopes\'][count($cx[\'scopes\'])-3][\'a\'] : null)', '../../../[a]') when input array(3,'a'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('((isset($in[\'id\']) && is_array($in)) ? $in[\'id\'] : null)', 'this.[id]') when input array(null, 'id'), array('flags'=>array('spvar'=>true,'debug'=>0,'prop'=>0,'method'=>0,'mustlok'=>0))
     * @expect array('LCRun3::v($cx, $in, array(\'id\'))', 'this.[id]') when input array(null, 'id'), array('flags'=>array('prop'=>true,'spvar'=>true,'debug'=>0,'method'=>0,'mustlok'=>0,'standalone'=>0), 'lcrun' => 'LCRun3')
     */
    protected static function getVariableName($var, &$context) {
        if (isset($var[0]) && ($var[0] === 0)) {
            return array($var[1], preg_replace('/\'(.*)\'/', '$1', $var[1]));
        }

        $levels = 0;
        $base = '$in';
        $spvar = false;

        if (isset($var[0])) {
            // trace to parent
            if (!is_string($var[0]) && is_int($var[0])) {
                $levels = array_shift($var);
            }
        }

        if (isset($var[0])) {
            // handle @root, @index, @key, @last, etc
            if ($context['flags']['spvar']) {
                if (substr($var[0], 0, 1) === '@') {
                    $spvar = true;
                    $base = "\$cx['sp_vars']";
                    $var[0] = substr($var[0], 1);
                }
            }
        }

        // change base when trace to parent
        if ($levels > 0) {
            if ($spvar) {
                $base .= str_repeat("['_parent']", $levels);
            } else {
                $base = "\$cx['scopes'][count(\$cx['scopes'])-$levels]";
            }
        }

        // Generate normalized expression for debug
        $exp = static::getExpression($levels, $spvar, $var);

        if ((count($var) == 0) || (is_null($var[0]) && (count($var) == 1))) {
            return array($base, $exp);
        }

        if (is_null($var[0])) {
            array_shift($var);
        }

        // 1. To support recursive context lookup...
        // 2. To support instance properties or methods...
        // the only way is using slower rendering time variable resolver.
        if ($context['flags']['prop'] || $context['flags']['method'] || $context['flags']['mustlok']) {
            return array(static::getFuncName($context, 'v', $exp) . "\$cx, $base, array(" . implode(',', array_map(function ($V) {
                return "'$V'";
            }, $var)) . '))', $exp);
        }

        $n = static::getArrayCode($var);
        array_pop($var);
        $p = count($var) ? static::getArrayCode($var) : '';

        return array("((isset($base$n) && is_array($base$p)) ? $base$n : " . ($context['flags']['debug'] ? (static::getFuncName($context, 'miss', '') . "\$cx, '$exp')") : 'null' ) . ')', $exp);
    }

    /**
     * Internal method used by compile().
     *
     * @param integer $levels trace N levels top parent scope
     * @param boolean $spvar is the path start with @ or not
     * @param array<string|integer> $var variable parsed path
     *
     * @return string normalized expression for debug display
     *
     * @expect '[a].[b]' when input 0, false, array('a', 'b')
     * @expect '@[root]' when input 0, true, array('root')
     * @expect 'this' when input 0, false, null
     * @expect 'this.[id]' when input 0, false, array(null, 'id')
     * @expect '@[root].[a].[b]' when input 0, true, array('root', 'a', 'b')
     * @expect '../../[a].[b]' when input 2, false, array('a', 'b')
     * @expect '../[a\'b]' when input 1, false, array('a\'b')
     */
    protected static function getExpression($levels, $spvar, $var) {
        return ($spvar ? '@' : '') . str_repeat('../', $levels) . ((is_array($var) && count($var)) ? implode('.', array_map(function($v) {
            return is_null($v) ? 'this' : "[$v]";
        }, $var)) : 'this');
    }

    /**
     * Internal method used by compile(). Return array presentation for a variable name
     *
     * @param string $v variable name to be fixed.
     * @param array<string,array|string|integer> $context Current compile content.
     *
     * @return array<integer,string> Return variable name array
     *
     * @expect array('this') when input 'this', array('flags' => array('advar' => 0, 'this' => 0))
     * @expect array() when input 'this', array('flags' => array('advar' => 0, 'this' => 1))
     * @expect array(1) when input '../', array('flags' => array('advar' => 0, 'this' => 1, 'parent' => 1), 'usedFeature' => array('parent' => 0), 'scan' => true)
     * @expect array(1) when input '../.', array('flags' => array('advar' => 0, 'this' => 1, 'parent' => 1), 'usedFeature' => array('parent' => 0), 'scan' => true)
     * @expect array(1) when input '../this', array('flags' => array('advar' => 0, 'this' => 1, 'parent' => 1), 'usedFeature' => array('parent' => 0), 'scan' => true)
     * @expect array(1, 'a') when input '../a', array('flags' => array('advar' => 0, 'this' => 1, 'parent' => 1), 'usedFeature' => array('parent' => 0), 'scan' => true)
     * @expect array(2, 'a', 'b') when input '../../a.b', array('flags' => array('advar' => 0, 'this' => 0, 'parent' => 1), 'usedFeature' => array('parent' => 0), 'scan' => true)
     * @expect array(2, '[a]', 'b') when input '../../[a].b', array('flags' => array('advar' => 0, 'this' => 0, 'parent' => 1), 'usedFeature' => array('parent' => 0), 'scan' => true)
     * @expect array(2, 'a', 'b') when input '../../[a].b', array('flags' => array('advar' => 1, 'this' => 0, 'parent' => 1), 'usedFeature' => array('parent' => 0), 'scan' => true)
     * @expect array('id') when input 'this.id', array('flags' => array('advar' => 1, 'this' => 1, 'parent' => 1), 'usedFeature' => array('parent' => 0))
     * @expect array(0, '\'a.b\'') when input '"a.b"', array('flags' => array('advar' => 1, 'this' => 0, 'parent' => 1), 'usedFeature' => array('parent' => 0))
     * @expect array(0, '123') when input '123', array('flags' => array('advar' => 1, 'this' => 0, 'parent' => 1), 'usedFeature' => array('parent' => 0))
     * @expect array(0, 'null') when input 'null', array('flags' => array('advar' => 1, 'this' => 0, 'parent' => 1), 'usedFeature' => array('parent' => 0))
     */
    protected static function fixVariable($v, &$context) {
        // handle number
        if (is_numeric($v)) {
            // convert 0x00 or 0b00 numbers to decimal
            return array(0, (string) 1 * $v);
        }

        // handle double quoted string
        if (preg_match('/^"(.*)"$/', $v, $matched)) {
            return array(0, "'" . preg_replace('/([^\\\\])\\\\\\\\"/', '$1"', preg_replace('/^\\\\\\\\"/', '"', $matched[1])) . "'");
        }

        // handle single quoted string
        if (preg_match('/^\\\\\'(.*)\\\\\'$/', $v, $matched)) {
            return array(0, "'$matched[1]'");
        }

        // handle boolean, null and undefined
        if (preg_match('/^(true|false|null|undefined)$/', $v)) {
            return array(0, ($v === 'undefined') ? 'null' : $v);
        }

        $ret = array();
        $levels = 0;

        // handle ..
        if ($v === '..') {
            $v = '../';
        }

        // Trace to parent for ../ N times
        $v = preg_replace_callback('/\\.\\.\\//', function() use (&$levels) {
            $levels++;
            return '';
        }, trim($v));

        if ($levels) {
            $ret[] = $levels;
            if (!$context['flags']['parent']) {
                $context['error'][] = 'Do not support {{../var}}, you should do compile with LightnCandy::FLAG_PARENT flag';
            }
            $context['usedFeature']['parent'] += ($context['scan'] ? 1 : 0);
        }

        if ($context['flags']['advar'] && preg_match('/\\]/', $v)) {
            preg_match_all(self::VARNAME_SEARCH, $v, $matchedall);
        } else {
            preg_match_all('/([^\\.\\/]+)/', $v, $matchedall);
        }

        foreach ($matchedall[1] as $m) {
            if ($context['flags']['advar'] && substr($m, 0, 1) === '[') {
                $ret[] = substr($m, 1, -1);
            } else if ((!$context['flags']['this'] || ($m !== 'this')) && ($m !== '.')) {
                $ret[] = $m;
            }
        }

        return $ret;
    }

    /**
     * Internal method used by scanFeatures() and compile(). Parse the token and return parsed result.
     *
     * @param array<string> $token preg_match results
     * @param array<string,array|string|integer> $context current compile context
     *
     * @return array<boolean|array> Return parsed result
     *
     * @expect array(false, array(array())) when input array(0,0,0,0,0,0,''), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(true, array(array())) when input array(0,0,0,'{{{',0,0,''), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(true, array(array())) when input array(0,0,0,0,0,0,''), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 0, 'noesc' => 1), 'scan' => false)
     * @expect array(false, array(array('a'))) when input array(0,0,0,0,0,0,'a'), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array('b'))) when input array(0,0,0,0,0,0,'a  b'), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array('"b'), array('c"'))) when input array(0,0,0,0,0,0,'a "b c"'), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array(0, '\'b c\''))) when input array(0,0,0,0,0,0,'a "b c"'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array('[b'), array('c]'))) when input array(0,0,0,0,0,0,'a [b c]'), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array('[b'), array('c]'))) when input array(0,0,0,0,0,0,'a [b c]'), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array('b c'))) when input array(0,0,0,0,0,0,'a [b c]'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array('b c'))) when input array(0,0,0,0,0,0,'a [b c]'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), 'q' => array('b c'))) when input array(0,0,0,0,0,0,'a q=[b c]'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array('q=[b c'))) when input array(0,0,0,0,0,0,'a [q=[b c]'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), 'q' => array('[b'), array('c]'))) when input array(0,0,0,0,0,0,'a q=[b c]'), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), 'q' => array('b'), array('c'))) when input array(0,0,0,0,0,0,'a [q]=b c'), array('flags' => array('advar' => 0, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), 'q' => array(0, '\'b c\''))) when input array(0,0,0,0,0,0,'a q="b c"'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('(foo bar)'))) when input array(0,0,0,0,0,0,'(foo bar)'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0, 'exhlp' => 1), 'ops' => array('seperator' => ''), 'usedFeature' => array('subexp' => 0), 'scan' => false)
     * @expect array(false, array(array('foo'), array("'=='"), array('bar'))) when input array(0,0,0,0,0,0,"foo '==' bar"), array('flags' => array('advar' => 1, 'namev' => 1, 'noesc' => 0, 'this' => 0), 'scan' => false)
     * @expect array(false, array(array('( foo bar)'))) when input array(0,0,0,0,0,0,'( foo bar)'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0, 'exhlp' => 1), 'ops' => array('seperator' => ''), 'usedFeature' => array('subexp' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array(0, '\' b c\''))) when input array(0,0,0,0,0,0,'a " b c"'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 0, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), 'q' => array(0, '\' b c\''))) when input array(0,0,0,0,0,0,'a q=" b c"'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('foo'), array(0, "' =='"), array('bar'))) when input array(0,0,0,0,0,0,"foo \' ==\' bar"), array('flags' => array('advar' => 1, 'namev' => 1, 'noesc' => 0, 'this' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), array(' b c'))) when input array(0,0,0,0,0,0,'a [ b c]'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array(array('a'), 'q' => array(0, "' d e'"))) when input array(0,0,0,0,0,0,"a q=\' d e\'"), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     * @expect array(false, array('q' => array('( foo bar)'))) when input array(0,0,0,0,0,0,'q=( foo bar)'), array('flags' => array('advar' => 1, 'this' => 1, 'namev' => 1, 'noesc' => 0), 'scan' => false)
     */
    protected static function parseTokenArgs(&$token, &$context) {
        trim($token[self::POS_INNERTAG]);

        // Handle delimiter change
        if (preg_match('/^=\s*([^ ]+)\s+([^ ]+)\s*=$/', $token[self::POS_INNERTAG], $matched)) {
            static::setupToken($context, $matched[1], $matched[2]);
            $token[self::POS_OP] = ' ';
            return array(false, array());
        }

        // Skip validation on comments
        if ($token[self::POS_OP] === '!') {
            return array(false, array());
        }

        $vars = array();
        $count = preg_match_all('/(\s*)([^\s]+)/', $token[self::POS_INNERTAG], $matchedall);

        // Parse arguments and deal with "..." or [...] or (...)
        if (($count > 0) && $context['flags']['advar']) {
            $prev = '';
            $expect = 0;
            foreach ($matchedall[2] as $index => $t) {
                // continue from previous match when expect something
                if ($expect) {
                    $prev .= "{$matchedall[1][$index]}$t";
                    // end an argument when end with expected charactor
                    if (substr($t, -1, 1) === $expect) {
                        $vars[] = $prev;
                        $prev = '';
                        $expect = 0;
                    }
                    continue;
                }

                // continue to next match when begin with '(' without ending ')'
                if (preg_match('/^\([^\)]*$/', $t)) {
                    $prev = $t;
                    $expect = ')';
                    continue;
                }

                // continue to next match when begin with '"' without ending '"'
                if (preg_match('/^"[^"]*$/', $t)) {
                    $prev = $t;
                    $expect = '"';
                    continue;
                }

                // continue to next match when begin with \' without ending '
                if (preg_match('/^\\\\\'[^\']*$/', $t)) {
                    $prev = $t;
                    $expect = '\'';
                    continue;
                }

                // continue to next match when '="' exists without ending '"'
                if (preg_match('/^[^"]*="[^"]*$/', $t)) {
                    $prev = $t;
                    $expect = '"';
                    continue;
                }

                // continue to next match when '[' exists without ending ']'
                if (preg_match('/\\[[^\\]]*$/', $t)) {
                    $prev = $t;
                    $expect = ']';
                    continue;
                }

                // continue to next match when =\' exists without ending '
                if (preg_match('/^[^\']*=\\\\\'[^\']*$/', $t)) {
                    $prev = $t;
                    $expect = '\'';
                    continue;
                }

                // continue to next match when =( exists without ending )
                if (preg_match('/.+\([^\)]*$/', $t)) {
                    $prev = $t;
                    $expect = ')';
                    continue;
                }

                $vars[] = $t;
            }
        } else {
            $vars = ($count > 0) ? $matchedall[2] : explode(' ', $token[self::POS_INNERTAG]);
        }

        // Check for advanced variable.
        $ret = array();
        $i = 0;
        foreach ($vars as $idx => $var) {
            // Skip advanced processing for subexpressions
            if (preg_match(static::IS_SUBEXP_SEARCH, $var)) {
                static::compileSubExpression($var, $context, !$context['scan']);
                $ret[$i] = array($var);
                $i++;
                continue;
            }

            if ($context['flags']['namev']) {
                if (preg_match('/^((\\[([^\\]]+)\\])|([^=^["\']+))=(.+)$/', $var, $m)) {
                    if (!$context['flags']['advar'] && $m[3]) {
                        $context['error'][] = "Wrong argument name as '[$m[3]]' in " . static::tokenString($token) . ' ! You should fix your template or compile with LightnCandy::FLAG_ADVARNAME flag.';
                    }
                    $idx = $m[3] ? $m[3] : $m[4];
                    $var = $m[5];
                }
            }

            $esc = $context['scan'] ? '' : '\\\\';
            if ($context['flags']['advar'] && !preg_match("/^(\"|$esc')(.*)(\"|$esc')$/", $var)) {
                    // foo]  Rule 1: no starting [ or [ not start from head
                if (preg_match('/^[^\\[\\.]+[\\]\\[]/', $var)
                    // [bar  Rule 2: no ending ] or ] not in the end
                    || preg_match('/[\\[\\]][^\\]\\.]+$/', $var)
                    // ]bar. Rule 3: middle ] not before .
                    || preg_match('/\\][^\\]\\[\\.]+\\./', $var)
                    // .foo[ Rule 4: middle [ not after .
                    || preg_match('/\\.[^\\]\\[\\.]+\\[/', preg_replace('/^(..\\/)+/', '', preg_replace('/\\[[^\\]]+\\]/', '[XXX]', $var)))
                ) {
                    $context['error'][] = "Wrong variable naming as '$var' in " . static::tokenString($token) . ' !';
                } else {
                    if (!$context['scan']) {
                        $name =  preg_replace('/(\\[.+?\\])/', '', $var);
                        // Scan for invalid charactors which not be protected by [ ]
                        // now make ( and ) pass, later fix
                        if (preg_match('/[!"#%\'*+,;<=>{|}~]/', $name)) {
                            $context['error'][] = "Wrong variable naming as '$var' in " . static::tokenString($token) . ' ! You should wrap ! " # % & \' * + , ; < = > { | } ~ into [ ]';
                        }
                    }
                }
            }

            if (($idx === 0) && ($token[self::POS_OP] === '>')) {
                $var = array(preg_replace('/^("(.+)")|(\\[(.+)\\])$/', '$2$4', $var));
            } else if (preg_match('/^\(.+\)$/', $var)) {
                $var = array($var);
            } else {
                $var = static::fixVariable($var, $context);
            }

            if (is_string($idx)) {
                $ret[$idx] = $var;
            } else {
                $ret[$i] = $var;
                $i++;
            }
        }

        return array(($token[self::POS_BEGINTAG] === '{{{') || ($token[self::POS_OP] === '&') || $context['flags']['noesc'], $ret);
    }

    /**
     * Internal method used by scanFeatures(). return token string
     *
     * @param string[] $token detected handlebars {{ }} token
     * @param integer $remove remove how many heading and ending token
     *
     * @return string Return whole token
     *
     * @expect 'b' when input array(0, 'a', 'b', 'c'), 1
     * @expect 'c' when input array(0, 'a', 'b', 'c', 'd', 'e')
     */
    protected static function tokenString($token, $remove = 2) {
        return implode('', array_slice($token, 1 + $remove, -$remove));
    }

    /**
     * Internal method used by scanFeatures(). Validate start and and.
     *
     * @param string[] $token detected handlebars {{ }} token
     * @param array<string,array|string|integer> $context current compile context
     *
     * @return boolean|null Return true when invalid
     *
     * @expect null when input array_fill(0, 9, ''), array()
     * @expect null when input array_fill(0, 9, '}}'), array()
     * @expect true when input array_fill(0, 9, '{{{'), array()
     */
    protected static function validateStartEnd($token, &$context) {
        // {{ }}} or {{{ }} are invalid
        if (strlen($token[self::POS_BEGINTAG]) !== strlen($token[self::POS_ENDTAG])) {
            $context['error'][] = 'Bad token ' . static::tokenString($token) . ' ! Do you mean {{' . static::tokenString($token, 4) . '}} or {{{' . static::tokenString($token, 4) . '}}}?';
            return true;
        }
        // {{{# }}} or {{{! }}} or {{{/ }}} or {{{^ }}} are invalid.
        if ((strlen($token[self::POS_BEGINTAG]) === 3) && $token[self::POS_OP] && ($token[self::POS_OP] !== '&')) {
            $context['error'][] = 'Bad token ' . static::tokenString($token) . ' ! Do you mean {{' . static::tokenString($token, 4) . '}} ?';
            return true;
        }
    }

    /**
     * Internal method used by compile(). Collect handlebars usage information, detect template error.
     *
     * @param string[] $token detected handlebars {{ }} token
     * @param array<string,array|string|integer> $context current compile context
     * @param array<array> $vars parsed arguments list
     *
     * @return boolean|integer|null Return true when invalid or detected
     *
     * @expect null when input array(0, 0, 0, 0, 0, ''), array(), array()
     * @expect 2 when input array(0, 0, 0, 0, 0, '^', '...'), array('usedFeature' => array('isec' => 1), 'level' => 0), array(array('foo'))
     * @expect 3 when input array(0, 0, 0, 0, 0, '!', '...'), array('usedFeature' => array('comment' => 2)), array()
     * @expect true when input array(0, 0, 0, 0, 0, '/'), array('stack' => array(1), 'level' => 1), array()
     * @expect 4 when input array(0, 0, 0, 0, 0, '#', '...'), array('usedFeature' => array('sec' => 3), 'level' => 0), array(array('x'))
     * @expect 5 when input array(0, 0, 0, 0, 0, '#', '...'), array('usedFeature' => array('if' => 4), 'level' => 0), array(array('if'))
     * @expect 6 when input array(0, 0, 0, 0, 0, '#', '...'), array('usedFeature' => array('with' => 5), 'level' => 0, 'flags' => array('with' => 1)), array(array('with'))
     * @expect 7 when input array(0, 0, 0, 0, 0, '#', '...'), array('usedFeature' => array('each' => 6), 'level' => 0), array(array('each'))
     * @expect 8 when input array(0, 0, 0, 0, 0, '#', '...'), array('usedFeature' => array('unless' => 7), 'level' => 0), array(array('unless'))
     * @expect 9 when input array(0, 0, 0, 0, 0, '#', '...'), array('blockhelpers' => array('abc' => ''), 'usedFeature' => array('bhelper' => 8), 'level' => 0), array(array('abc'))
     * @expect 10 when input array(0, 0, 0, 0, 0, ' ', '...'), array('usedFeature' => array('delimiter' => 9), 'level' => 0), array()
     * @expect 11 when input array(0, 0, 0, 0, 0, '#', '...'), array('hbhelpers' => array('abc' => ''), 'usedFeature' => array('hbhelper' => 10), 'level' => 0), array(array('abc'))
     * @expect true when input array(0, 0, 0, 0, 0, '>', '...'), array('basedir' => array('.'), 'fileext' => array('.tmpl'), 'usedFeature' => array('unless' => 7, 'partial' => 7), 'level' => 0, 'flags' => array('skippartial' => 0)), array('test')
     */
    protected static function validateOperations($token, &$context, $vars) {
        switch ($token[self::POS_OP]) {
            case '>':
                static::readPartial($vars[0][0], $context);
                return true;

            case ' ':
                return ++$context['usedFeature']['delimiter'];

            case '^':
                if (isset($vars[0][0])) {
                    $context['stack'][] = $token[self::POS_INNERTAG];
                    $context['level']++;
                    return ++$context['usedFeature']['isec'];
                }

                if (!$context['flags']['else']) {
                    $context['error'][] = 'Do not support {{^}}, you should do compile with LightnCandy::FLAG_ELSE flag';
                }
                return;

            case '/':
                array_pop($context['stack']);
                $context['level']--;
                return true;

            case '!':
                return ++$context['usedFeature']['comment'];

            case '#':
                $context['stack'][] = $token[self::POS_INNERTAG];
                $context['level']++;

                if (!isset($vars[0][0])) {
                    return;
                }

                if (is_string($vars[0][0])) {
                    // detect handlebars custom helpers.
                    if (isset($context['hbhelpers'][$vars[0][0]])) {
                        return ++$context['usedFeature']['hbhelper'];
                    }

                    // detect block custom helpers.
                    if (isset($context['blockhelpers'][$vars[0][0]])) {
                        return ++$context['usedFeature']['bhelper'];
                    }
                }

                switch ($vars[0][0]) {
                    case 'with':
                        if ($context['flags']['with']) {
                            if (count($vars) < 2) {
                                $context['error'][] = 'No argument after {{#with}} !';
                            }
                        } else {
                            if (isset($vars[1][0])) {
                                $context['error'][] = 'Do not support {{#with var}}, you should do compile with LightnCandy::FLAG_WITH flag';
                            }
                        }
                        // Continue to add usage...
                    case 'each':
                    case 'unless':
                    case 'if':
                        return ++$context['usedFeature'][$vars[0][0]];

                    default:
                        return ++$context['usedFeature']['sec'];
                }
        }
    }

    /**
     * Internal method used by compile(). Collect handlebars usage information, detect template error.
     *
     * @param string[] $token detected handlebars {{ }} token
     * @param array<string,array|string|integer> $context current compile context
     */
    protected static function scanFeatures($token, &$context) {
        list($raw, $vars) = static::parseTokenArgs($token, $context);

        if (static::validateStartEnd($token, $context)) {
            return;
        }

        if (static::validateOperations($token, $context, $vars)) {
            return;
        }

        if (($token[self::POS_OP] === '^') && ($context['flags']['else'])) {
            return $context['usedFeature']['else']++;
        }

        if (count($vars) == 0) {
            return $context['error'][] = 'Wrong variable naming in ' . static::tokenString($token);
        }

        if (!isset($vars[0])) {
            return static::noNamedArguments($token, $context, true, ', you should use it after a custom helper.');
        }

        if ($vars[0] !== 'else') {
            $context['usedFeature'][$raw ? 'raw' : 'enc']++;
        }

        foreach ($vars as $var) {
            if (!isset($var[0])) {
                if ($context['level'] == 0) {
                    $context['usedFeature']['rootthis']++;
                }
                $context['usedFeature']['this']++;
            }
        }

        if (!isset($vars[0][0])) {
            return;
        }

        if ($vars[0][0] === 'else') {
            if ($context['flags']['else']) {
                return $context['usedFeature']['else']++;
            }
        }

        // detect handlebars custom helpers.
        if (isset($context['hbhelpers'][$vars[0][0]])) {
            return $context['usedFeature']['hbhelper']++;
        }

        // detect custom helpers.
        if (isset($context['helpers'][$vars[0][0]])) {
            return $context['usedFeature']['helper']++;
        }
    }

    /**
     * Internal method used by compile(). Show error message when named arguments appear without custom helper.
     *
     * @param array<string> $token detected handlebars {{ }} token
     * @param array<string,array|string|integer> $context current compile context
     * @param boolean $named is named arguments
     * @param string $suggest extended hint for this no named argument error
     */
    public static function noNamedArguments($token, &$context, $named, $suggest = '!') {
        if ($named) {
            $context['error'][] = 'Do not support name=value in ' . static::tokenString($token) . $suggest;
        }
    }

    /**
     * Internal method used by compileToken(). Modify $token when spacing rules matched.
     *
     * @param array<string> $token detected handlebars {{ }} token
     * @param array<array|string|integer> $vars parsed arguments list
     * @param array<string,array|string|integer> $context current compile context
     *
     * @return string|null Return compiled code segment for the token
     */
    public static function handleMustacheSpacing(&$token, $vars, &$context) {
        if (!$context['flags']['mustsp'] && !$context['flags']['mustpi']) {
            return;
        }

        // left line change detection
        $lsp = preg_match('/^(.*)(\\r?\\n)([ \\t]*?)$/s', $token[self::POS_LSPACE], $lmatch);
        $ind = $lsp ? $lmatch[3] : $token[self::POS_LSPACE];

        // right line change detection
        $rsp = preg_match('/^([ \\t]*?)(\\r?\\n)(.*)$/s', $token[self::POS_RSPACE], $rmatch);
        $st = true;

        // setup ahead flag
        $ahead = $context['tokens']['ahead'];
        $context['tokens']['ahead'] = preg_match('/^[^\n]*{{/s', $token[self::POS_RSPACE] . $token[self::POS_ROTHER]);

        // reset partial indent
        $context['tokens']['partialind'] = '';

        // same tags in the same line , not standalone
        if (!$lsp && $ahead) {
            $st = false;
        }

        // Do not need standalone detection for these tags
        if (!$token[self::POS_OP] || ($token[self::POS_OP] === '&')) {
            if (!$context['flags']['else'] || (isset($vars[0][0]) && ($vars[0][0] !== 'else'))) {
                $st = false;
            }
        }

        // not standalone because other things in the same line ahead
        if ($token[self::POS_LOTHER] && !$token[self::POS_LSPACE]) {
            $st = false;
        }

        // not standalone because other things in the same line behind
        if ($token[self::POS_ROTHER] && !$token[self::POS_RSPACE]) {
            $st = false;
        }

        if ($st && (($lsp && $rsp) // both side cr
            || ($rsp && !$token[self::POS_LOTHER]) // first line without left
            || ($lsp && ($context['tokens']['current'] == $context['tokens']['count']) && !$token[self::POS_ROTHER]) // final line
           )) {
            // handle partial
            if ($context['flags']['mustpi'] && ($token[self::POS_OP] === '>')) {
                $context['tokens']['partialind'] = $ind;
            }
            if ($context['flags']['mustsp']) {
                $token[self::POS_LSPACE] = (isset($lmatch[2]) ? ($lmatch[1] . $lmatch[2]) : '');
                $token[self::POS_RSPACE] = isset($rmatch[3]) ? $rmatch[3] : '';
            }
        }
    }

    /**
     * Internal method used by compile(). Return compiled PHP code partial for a handlebars token.
     *
     * @param array<string> $token detected handlebars {{ }} token
     * @param array<string,array|string|integer> $context current compile context
     *
     * @return string Return compiled code segment for the token
     */
    public static function compileToken(&$token, &$context) {
        list($raw, $vars) = static::parseTokenArgs($token, $context);
        $named = count(array_diff_key($vars, array_keys(array_keys($vars)))) > 0;

        // Handle spacing (standalone tags, partial indent)
        static::handleMustacheSpacing($token, $vars, $context);

        // Handle space control.
        if ($token[self::POS_LSPACECTL]) {
            $token[self::POS_LSPACE] = '';
        }

        if ($token[self::POS_RSPACECTL]) {
            $token[self::POS_RSPACE] = '';
        }

        if ($ret = static::compileSection($token, $context, $vars, $named)) {
            return $ret;
        }

        if (isset($vars[0][0])) {
            if ($ret = static::compileCustomHelper($context, $vars, $raw)) {
                return $ret;
            }
            if ($ret = static::compileElse($context, $vars)) {
                return $ret;
            }
        }

        static::noNamedArguments($token, $context, $named, ', maybe you missing the custom helper?');

        return static::compileVariable($context, $vars, $raw);
    }

    /**
     * Internal method used by compile(). Return compiled PHP code partial for a handlebars section token.
     *
     * @param array<string> $token detected handlebars {{ }} token
     * @param array<string,array|string|integer> $context current compile context
     * @param array<array|string|integer> $vars parsed arguments list
     * @param boolean $named is named arguments or not
     *
     * @return string|null Return compiled code segment for the token when the token is section
     */
    protected static function compileSection(&$token, &$context, &$vars, $named) {
        switch ($token[self::POS_OP]) {
            case '>':
                // mustache spec: ignore missing partial
                if (($context['usedFeature']['dynpartial'] === 0) && !isset($context['usedPartial'][$vars[0][0]])) {
                    return $context['ops']['seperator'];
                }
                $p = array_shift($vars);
                if (!isset($vars[0])) {
                    $vars[0] = array();
                }
                $v = static::getVariableNames($vars, $context);
                $tag = ">$p[0] " .implode(' ', $v[1]);
                if ($context['flags']['runpart']) {
                    if (preg_match(static::IS_SUBEXP_SEARCH, $p[0])) {
                        list($p) = static::compileSubExpression($p[0], $context);
                    } else {
                        $p = "'$p[0]'";
                    }
                    $sp = $context['tokens']['partialind'] ? ", '{$context['tokens']['partialind']}'" : '';
                    return $context['ops']['seperator'] . static::getFuncName($context, 'p', $tag) . "\$cx, $p, $v[0]$sp){$context['ops']['seperator']}";
                }
                if ($named || $v[0] !== 'array(array($in),array())') {
                    $context['error'][] = "Do not support {{{$tag}}}, you should do compile with LightnCandy::FLAG_RUNTIMEPARTIAL flag";
                }
                return "{$context['ops']['seperator']}'" . static::compileTemplate($context, preg_replace('/^/m', $context['tokens']['partialind'], $context['usedPartial'][$p[0]]), $p[0]) . "'{$context['ops']['seperator']}";
            case '^':
                // {{^}} means {{else}}
                if (!isset($vars[0][0])) {
                    $vars[0][0] = 'else';
                    $token[self::POS_OP] = '';
                    return;
                }

                // Try to compile as custom helper {{^myHelper}}
                $r = static::compileBlockCustomHelper($context, $vars, true);
                if ($r !== null) {
                    return $r;
                }

                $v = static::getVariableName($vars[0], $context);
                $context['stack'][] = $v[1];
                $context['stack'][] = '^';
                static::noNamedArguments($token, $context, $named);
                // Compile to inverted section {{^myVar}}
                return "{$context['ops']['cnd_start']}(" . static::getFuncName($context, 'isec', '^' . $v[1]) . "\$cx, {$v[0]})){$context['ops']['cnd_then']}";
            case '/':
                return static::compileBlockEnd($token, $context, $vars);
            case '!':
            case ' ':
                return $context['ops']['seperator'];
            case '#':
                // Try to compile as custom helper {{#myHelper}}
                $r = static::compileBlockCustomHelper($context, $vars);
                if ($r !== null) {
                    return $r;
                }
                static::noNamedArguments($token, $context, $named, ', maybe you missing the block custom helper?');
                // Compile to section {{#myVar}}
                return static::compileBlockBegin($context, $vars);
        }
    }

    /**
     * Internal method used by compile(). Return compiled PHP code partial for a handlebars block custom helper begin token.
     *
     * @param array<string,array|string|integer> $context current compile context
     * @param array<array|string|integer> $vars parsed arguments list
     * @param boolean $inverted the logic will be inverted
     *
     * @return string|null Return compiled code segment for the token
     */
    protected static function compileBlockCustomHelper(&$context, $vars, $inverted = false) {
        if (!isset($vars[0][0])) {
            return;
        }
        $notHBCH = !isset($context['hbhelpers'][$vars[0][0]]);

        if (!isset($context['blockhelpers'][$vars[0][0]]) && $notHBCH) {
            return;
        }

        $v = static::getVariableName($vars[0], $context);
        $context['stack'][] = $v[1];
        $context['stack'][] = '#';
        $ch = array_shift($vars);
        $inverted = $inverted ? 'true' : 'false';

        static::addUsageCount($context, $notHBCH ? 'blockhelpers' : 'hbhelpers', $ch[0]);
        $v = static::getVariableNames($vars, $context);
        return $context['ops']['seperator'] . static::getFuncName($context, $notHBCH ? 'bch' : 'hbch', ($inverted ? '^' : '#') . implode(' ', $v[1])) . "\$cx, '$ch[0]', {$v[0]}, \$in, $inverted, function(\$cx, \$in) {{$context['ops']['f_start']}";
    }

    /**
     * Internal method used by compile(). Return compiled PHP code partial for a handlebars block end token.
     *
     * @param array<string> $token detected handlebars {{ }} token
     * @param array<string,array|string|integer> $context current compile context
     * @param array<array|string|integer> $vars parsed arguments list
     *
     * @return string Return compiled code segment for the token
     */
    protected static function compileBlockEnd(&$token, &$context, $vars) {
        $each = false;
        $pop = array_pop($context['stack']);
        switch ($token[self::POS_INNERTAG]) {
            case 'if':
            case 'unless':
                if ($pop == ':') {
                    array_pop($context['stack']);
                    return $context['usedFeature']['parent'] ? "{$context['ops']['f_end']}}){$context['ops']['seperator']}" : "{$context['ops']['cnd_end']}";
                }
                return $context['usedFeature']['parent'] ? "{$context['ops']['f_end']}}){$context['ops']['seperator']}" : "{$context['ops']['cnd_else']}''{$context['ops']['cnd_end']}";
            case 'with':
                if ($context['flags']['with']) {
                    if ($pop !== 'with') {
                        $context['error'][] = 'Unexpect token: {{/with}} !';
                        return;
                    }
                    return "{$context['ops']['f_end']}}){$context['ops']['seperator']}";
                }
                break;
            case 'each':
                $each = true;
        }

        switch($pop) {
            case '#':
            case '^':
                $pop2 = array_pop($context['stack']);
                $v = static::getVariableName($vars[0], $context);
                if (!$each && ($pop2 !== $v[1])) {
                    $context['error'][] = 'Unexpect token ' . static::tokenString($token) . " ! Previous token {{{$pop}$pop2}} is not closed";
                    return;
                }
                if ($pop == '^') {
                    return "{$context['ops']['cnd_else']}''{$context['ops']['cnd_end']}";
                }
                return "{$context['ops']['f_end']}}){$context['ops']['seperator']}";
            default:
                $context['error'][] = 'Unexpect token: ' . static::tokenString($token) . ' !';
                return;
        }
    }

    /**
     * Internal method used by compile(). Return compiled PHP code partial for a handlebars block begin token.
     *
     * @param array<string,array|string|integer> $context current compile context
     * @param array<array|string|integer> $vars parsed arguments list
     *
     * @return string Return compiled code segment for the token
     */
    protected static function compileBlockBegin(&$context, $vars) {
        $each = 'false';
        $v = isset($vars[1]) ? static::getVariableNameOrSubExpression($vars[1], $context) : array(null, array());
        switch (isset($vars[0][0]) ? $vars[0][0] : null) {
            case 'if':
                $context['stack'][] = 'if';
                return $context['usedFeature']['parent']
                    ? $context['ops']['seperator'] . static::getFuncName($context, 'ifv', 'if ' . $v[1]) . "\$cx, {$v[0]}, \$in, function(\$cx, \$in) {{$context['ops']['f_start']}"
                    : "{$context['ops']['cnd_start']}(" . static::getFuncName($context, 'ifvar', $v[1]) . "\$cx, {$v[0]})){$context['ops']['cnd_then']}";
            case 'unless':
                $context['stack'][] = 'unless';
                return $context['usedFeature']['parent']
                    ? $context['ops']['seperator'] . static::getFuncName($context, 'unl', 'unless ' . $v[1]) . "\$cx, {$v[0]}, \$in, function(\$cx, \$in) {{$context['ops']['f_start']}"
                    : "{$context['ops']['cnd_start']}(!" . static::getFuncName($context, 'ifvar', $v[1]) . "\$cx, {$v[0]})){$context['ops']['cnd_then']}";
            case 'each':
                $each = 'true';
                array_shift($vars);
                if (!isset($vars[0])) {
                    $vars[0] = array(null);
                }
                break;
            case 'with':
                if ($context['flags']['with']) {
                    $context['stack'][] = 'with';
                    return $context['ops']['seperator'] . static::getFuncName($context, 'wi', 'with ' . $v[1]) . "\$cx, {$v[0]}, \$in, function(\$cx, \$in) {{$context['ops']['f_start']}";
                }
        }

        $v = static::getVariableNameOrSubExpression($vars[0], $context);
        $context['stack'][] = $v[1];
        $context['stack'][] = '#';
        return $context['ops']['seperator'] . static::getFuncName($context, 'sec', (($each == 'true') ? 'each ' : '') . $v[1]) . "\$cx, {$v[0]}, \$in, $each, function(\$cx, \$in) {{$context['ops']['f_start']}";
    }

    /**
     * Internal method used by compile(). Return compiled PHP code partial for a handlebars custom helper token.
     *
     * @param array<string,array|string|integer> $context current compile context
     * @param array<array|string|integer> $vars parsed arguments list
     * @param boolean $raw is this {{{ token or not
     * @param boolean $err should cause error when missing helper or not
     *
     * @return string|null Return compiled code segment for the token when the token is custom helper
     */
    protected static function compileCustomHelper(&$context, $vars, $raw, $err = false) {
        $notHH = !isset($context['hbhelpers'][$vars[0][0]]);
        if (!isset($context['helpers'][$vars[0][0]]) && $notHH) {
            if ($err) {
                if (!$context['flags']['exhlp']) {
                    $context['error'][] = "Can not find custom helper function defination {$vars[0][0]}() !";
                }
            }
            return;
        }

        $fn = $raw ? 'raw' : $context['ops']['enc'];
        $ch = array_shift($vars);
        $v = static::getVariableNames($vars, $context);
        static::addUsageCount($context, $notHH ? 'helpers' : 'hbhelpers', $ch[0]);
        return $context['ops']['seperator'] . static::getFuncName($context, $notHH ? 'ch' : 'hbch', "$ch[0] " . implode(' ', $v[1])) . "\$cx, '$ch[0]', {$v[0]}, '$fn'" . ($notHH ? '' : ', $in') . "){$context['ops']['seperator']}";
    }

    /**
     * Internal method used by compile(). Return compiled PHP code partial for a handlebars else token.
     *
     * @param array<string,array|string|integer> $context current compile context
     * @param array<array|string|integer> $vars parsed arguments list
     *
     * @return string|null Return compiled code segment for the token when the token is else
     */
    protected static function compileElse(&$context, &$vars) {
        if ($vars[0][0] === 'else') {
            $c = count($context['stack']) - 1;
            if ($c >= 0) {
                switch ($context['stack'][count($context['stack']) - 1]) {
                    case 'if':
                    case 'unless':
                        $context['stack'][] = ':';
                        return $context['usedFeature']['parent'] ? "{$context['ops']['f_end']}}, function(\$cx, \$in) {{$context['ops']['f_start']}" : "{$context['ops']['cnd_else']}";
                    case 'with':
                    case 'each':
                    case '#':
                        return "{$context['ops']['f_end']}}, function(\$cx, \$in) {{$context['ops']['f_start']}";
                    default:
                }
            }
            $context['error'][] = '{{else}} only valid in if, unless, each, and #section context';
        }
    }

    /**
     * Internal method used by compile(). Return compiled PHP code partial for a handlebars variable token.
     *
     * @param array<string,array|string|integer> $context current compile context
     * @param array<array|string|integer> $vars parsed arguments list
     * @param boolean $raw is this {{{ token or not
     *
     * @return string Return compiled code segment for the token
     */
    protected static function compileVariable(&$context, &$vars, $raw) {
        $v = static::getVariableName($vars[0], $context);
        if ($context['flags']['jsobj'] || $context['flags']['jstrue'] || $context['flags']['debug']) {
            return $context['ops']['seperator'] . static::getFuncName($context, $raw ? 'raw' : $context['ops']['enc'], $v[1]) . "\$cx, {$v[0]}){$context['ops']['seperator']}";
        } else {
            return $raw ? "{$context['ops']['seperator']}$v[0]{$context['ops']['seperator']}" : "{$context['ops']['seperator']}htmlentities((string){$v[0]}, ENT_QUOTES, 'UTF-8'){$context['ops']['seperator']}";
        }
    }

    /**
     * Internal method used by compile(). Add usage count to context
     *
     * @param array<string,array|string|integer> $context current context
     * @param string $category ctegory name, can be one of: 'var', 'helpers', 'blockhelpers'
     * @param string $name used name
     * @param integer $count increment
     *
     * @expect 1 when input array('usedCount' => array('test' => array())), 'test', 'testname'
     * @expect 3 when input array('usedCount' => array('test' => array('testname' => 2))), 'test', 'testname'
     * @expect 5 when input array('usedCount' => array('test' => array('testname' => 2))), 'test', 'testname', 3
     */
    protected static function addUsageCount(&$context, $category, $name, $count = 1) {
        if (!isset($context['usedCount'][$category][$name])) {
            $context['usedCount'][$category][$name] = 0;
        }
        return ($context['usedCount'][$category][$name] += $count);
    }
}

/**
 * LightnCandy static class for compiled template runtime methods.
 */
class LCRun3 {
    const DEBUG_ERROR_LOG = 1;
    const DEBUG_ERROR_EXCEPTION = 2;
    const DEBUG_TAGS = 4;
    const DEBUG_TAGS_ANSI = 12;
    const DEBUG_TAGS_HTML = 20;

    /**
     * LightnCandy runtime method for output debug info.
     *
     * @param string $v expression
     * @param string $f runtime function name
     * @param array<string,array|string|integer> $cx render time context
     *
     * @expect '{{123}}' when input '123', 'miss', array('flags' => array('debug' => LCRun3::DEBUG_TAGS), 'lcrun' => 'LCRun3'), ''
     * @expect '<!--MISSED((-->{{#123}}<!--))--><!--SKIPPED--><!--MISSED((-->{{/123}}<!--))-->' when input '123', 'wi', array('flags' => array('debug' => LCRun3::DEBUG_TAGS_HTML), 'lcrun' => 'LCRun3'), false, false, function () {return 'A';}
     */
    public static function debug($v, $f, $cx) {
        $params = array_slice(func_get_args(), 2);
        $r = call_user_func_array((isset($cx['funcs'][$f]) ? $cx['funcs'][$f] : "{$cx['lcrun']}::$f"), $params);

        if ($cx['flags']['debug'] & self::DEBUG_TAGS) {
            $ansi = $cx['flags']['debug'] & (self::DEBUG_TAGS_ANSI - self::DEBUG_TAGS);
            $html = $cx['flags']['debug'] & (self::DEBUG_TAGS_HTML - self::DEBUG_TAGS);
            $cs = ($html ? (($r !== '') ? '<!!--OK((-->' : '<!--MISSED((-->') : '')
                  . ($ansi ? (($r !== '') ? "\033[0;32m" : "\033[0;31m") : '');
            $ce = ($html ? '<!--))-->' : '')
                  . ($ansi ? "\033[0m" : '');
            switch ($f) {
                case 'sec':
                case 'ifv':
                case 'unl':
                case 'wi':
                    if ($r == '') {
                        if ($ansi) {
                            $r = "\033[0;33mSKIPPED\033[0m";
                        }
                        if ($html) {
                            $r = '<!--SKIPPED-->';
                        }
                    }
                    return "$cs{{#{$v}}}$ce{$r}$cs{{/{$v}}}$ce";
                default:
                    return "$cs{{{$v}}}$ce";
            }
        } else {
            return $r;
        }
    }

    /**
     * LightnCandy runtime method for missing data error.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param string $v expression
     */
    public static function miss($cx, $v) {
        $e = "LCRun3: $v is not exist";
        if ($cx['flags']['debug'] & self::DEBUG_ERROR_LOG) {
            error_log($e);
            return;
        }
        if ($cx['flags']['debug'] & self::DEBUG_ERROR_EXCEPTION) {
            throw new Exception($e);
        }
    }

    /**
     * LightnCandy runtime method for variable lookup. It is slower and only be used for instance property or method detection.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer> $base current variable context
     * @param array<string|integer> $path array of names for path
     *
     * @return null|string Return the value or null when not found
     *
     * @expect null when input array('scopes' => array(), 'flags' => array('prop' => 0, 'method' => 0, 'mustlok' => 0)), 0, array('a', 'b')
     * @expect 3 when input array('scopes' => array(), 'flags' => array('prop' => 0, 'method' => 0), 'mustlok' => 0), array('a' => array('b' => 3)), array('a', 'b')
     * @expect null when input array('scopes' => array(), 'flags' => array('prop' => 0, 'method' => 0, 'mustlok' => 0)), (Object) array('a' => array('b' => 3)), array('a', 'b')
     * @expect 3 when input array('scopes' => array(), 'flags' => array('prop' => 1, 'method' => 0, 'mustlok' => 0)), (Object) array('a' => array('b' => 3)), array('a', 'b')
     */
    public static function v($cx, $base, $path) {
        $count = count($cx['scopes']);
        while ($base) {
            $v = $base;
            foreach ($path as $name) {
                if (is_array($v) && isset($v[$name])) {
                    $v = $v[$name];
                    continue;
                }
                if (is_object($v)) {
                    if ($cx['flags']['prop'] && isset($v->$name)) {
                        $v = $v->$name;
                        continue;
                    }
                    if ($cx['flags']['method'] && is_callable(array($v, $name))) {
                        $v = $v->$name();
                        continue;
                    }
                }
                if ($cx['flags']['mustlok']) {
                    unset($v);
                    break;
                }
                return null;
            }
            if (isset($v)) {
                return $v;
            }
            $count--;
            switch ($count) {
                case -1:
                    $base = $cx['sp_vars']['root'];
                    break;
                case -2:
                    return null;
                default:
                    $base = $cx['scopes'][$count];
            }
        }
    }

    /**
     * LightnCandy runtime method for {{#if var}}.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $v value to be tested
     *
     * @return boolean Return true when the value is not null nor false.
     *
     * @expect false when input array(), null
     * @expect false when input array(), 0
     * @expect false when input array(), false
     * @expect true when input array(), true
     * @expect true when input array(), 1
     * @expect false when input array(), ''
     * @expect false when input array(), array()
     * @expect true when input array(), array('')
     * @expect true when input array(), array(0)
     */
    public static function ifvar($cx, $v) {
        return !is_null($v) && ($v !== false) && ($v !== 0) && ($v !== 0.0) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
    }

    /**
     * LightnCandy runtime method for {{#if var}} when {{../var}} used.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $v value to be tested
     * @param array<array|string|integer> $in input data with current scope
     * @param Closure|null $truecb callback function when test result is true
     * @param Closure|null $falsecb callback function when test result is false
     *
     * @return string The rendered string of the section
     *
     * @expect '' when input array('scopes' => array()), null, array(), null
     * @expect '' when input array('scopes' => array()), null, array(), function () {return 'Y';}
     * @expect 'Y' when input array('scopes' => array()), 1, array(), function () {return 'Y';}
     * @expect 'N' when input array('scopes' => array()), null, array(), function () {return 'Y';}, function () {return 'N';}
     */
    public static function ifv($cx, $v, $in, $truecb, $falsecb = null) {
        $ret = '';
        if (self::ifvar($cx, $v)) {
            if ($truecb) {
                $cx['scopes'][] = $in;
                $ret = $truecb($cx, $in);
                array_pop($cx['scopes']);
            }
        } else {
            if ($falsecb) {
                $cx['scopes'][] = $in;
                $ret = $falsecb($cx, $in);
                array_pop($cx['scopes']);
            }
        }
        return $ret;
    }

    /**
     * LightnCandy runtime method for {{#unless var}} when {{../var}} used.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $var value be tested
     * @param array<array|string|integer>|string|integer|null $in input data with current scope
     * @param Closure $truecb callback function when test result is true
     * @param Closure|null $falsecb callback function when test result is false
     *
     * @return string Return rendered string when the value is not null nor false.
     *
     * @expect '' when input array('scopes' => array()), null, array(), null
     * @expect 'Y' when input array('scopes' => array()), null, array(), function () {return 'Y';}
     * @expect '' when input array('scopes' => array()), 1, array(), function () {return 'Y';}
     * @expect 'Y' when input array('scopes' => array()), null, array(), function () {return 'Y';}, function () {return 'N';}
     * @expect 'N' when input array('scopes' => array()), true, array(), function () {return 'Y';}, function () {return 'N';}
     */
    public static function unl($cx, $var, $in, $truecb, $falsecb = null) {
        return self::ifv($cx, $var, $in, $falsecb, $truecb);
    }

    /**
     * LightnCandy runtime method for {{^var}} inverted section.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $v value to be tested
     *
     * @return boolean Return true when the value is not null nor false.
     *
     * @expect true when input array(), null
     * @expect false when input array(), 0
     * @expect true when input array(), false
     * @expect false when input array(), 'false'
     * @expect true when input array(), array()
     * @expect false when input array(), array('1')
     */
    public static function isec($cx, $v) {
        return is_null($v) || ($v === false) || (is_array($v) && (count($v) === 0));
    }

    /**
     * LightnCandy runtime method for {{{var}}} .
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $v value to be output
     *
     * @return string The raw value of the specified variable
     *
     * @expect true when input array('flags' => array('jstrue' => 0)), true
     * @expect 'true' when input array('flags' => array('jstrue' => 1)), true
     * @expect '' when input array('flags' => array('jstrue' => 0)), false
     * @expect 'false' when input array('flags' => array('jstrue' => 1)), false
     * @expect 'false' when input array('flags' => array('jstrue' => 1)), false, true
     * @expect 'Array' when input array('flags' => array('jstrue' => 1, 'jsobj' => 0)), array('a', 'b')
     * @expect 'a,b' when input array('flags' => array('jstrue' => 1, 'jsobj' => 1)), array('a', 'b')
     * @expect '[object Object]' when input array('flags' => array('jstrue' => 1, 'jsobj' => 1)), array('a', 'c' => 'b')
     * @expect '[object Object]' when input array('flags' => array('jstrue' => 1, 'jsobj' => 1)), array('c' => 'b')
     * @expect 'a,true' when input array('flags' => array('jstrue' => 1, 'jsobj' => 1)), array('a', true)
     * @expect 'a,1' when input array('flags' => array('jstrue' => 0, 'jsobj' => 1)), array('a',true)
     * @expect 'a,' when input array('flags' => array('jstrue' => 0, 'jsobj' => 1)), array('a',false)
     * @expect 'a,false' when input array('flags' => array('jstrue' => 1, 'jsobj' => 1)), array('a',false)
     */
    public static function raw($cx, $v) {
        if ($v === true) {
            if ($cx['flags']['jstrue']) {
                return 'true';
            }
        }

        if (($v === false)) {
            if ($cx['flags']['jstrue']) {
                return 'false';
            }
        }

        if (is_array($v)) {
            if ($cx['flags']['jsobj']) {
                if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                    return '[object Object]';
                } else {
                    $ret = array();
                    foreach ($v as $k => $vv) {
                        $ret[] = self::raw($cx, $vv);
                    }
                    return join(',', $ret);
                }
            } else {
                return 'Array';
            }
        }

        return "$v";
    }

    /**
     * LightnCandy runtime method for {{var}} .
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $var value to be htmlencoded
     *
     * @return string The htmlencoded value of the specified variable
     *
     * @expect 'a' when input array(), 'a'
     * @expect 'a&amp;b' when input array(), 'a&b'
     * @expect 'a&#039;b' when input array(), 'a\'b'
     */
    public static function enc($cx, $var) {
        return htmlentities(self::raw($cx, $var), ENT_QUOTES, 'UTF-8');
    }

    /**
     * LightnCandy runtime method for {{var}} , and deal with single quote to same as handlebars.js .
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $var value to be htmlencoded
     *
     * @return string The htmlencoded value of the specified variable
     *
     * @expect 'a' when input array(), 'a'
     * @expect 'a&amp;b' when input array(), 'a&b'
     * @expect 'a&#x27;b' when input array(), 'a\'b'
     * @expect '&#x60;a&#x27;b' when input array(), '`a\'b'
     */
    public static function encq($cx, $var) {
        return preg_replace('/`/', '&#x60;', preg_replace('/&#039;/', '&#x27;', htmlentities(self::raw($cx, $var), ENT_QUOTES, 'UTF-8')));
    }

    /**
     * LightnCandy runtime method for {{#var}} section.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $v value for the section
     * @param array<array|string|integer>|string|integer|null $in input data with current scope
     * @param boolean $each true when rendering #each
     * @param Closure $cb callback function to render child context
     * @param Closure|null $else callback function to render child context when {{else}}
     *
     * @return string The rendered string of the section
     *
     * @expect '' when input array('flags' => array('spvar' => 0)), false, false, false, function () {return 'A';}
     * @expect '' when input array('flags' => array('spvar' => 0)), null, null, false, function () {return 'A';}
     * @expect 'A' when input array('flags' => array('spvar' => 0)), true, true, false, function () {return 'A';}
     * @expect 'A' when input array('flags' => array('spvar' => 0)), 0, 0, false, function () {return 'A';}
     * @expect '-a=' when input array('flags' => array('spvar' => 0)), array('a'), array('a'), false, function ($c, $i) {return "-$i=";}
     * @expect '-a=-b=' when input array('flags' => array('spvar' => 0)), array('a','b'), array('a','b'), false, function ($c, $i) {return "-$i=";}
     * @expect '' when input array('flags' => array('spvar' => 0)), 'abc', 'abc', true, function ($c, $i) {return "-$i=";}
     * @expect '-b=' when input array('flags' => array('spvar' => 0)), array('a' => 'b'), array('a' => 'b'), true, function ($c, $i) {return "-$i=";}
     * @expect '1' when input array('flags' => array('spvar' => 0)), 'b', 'b', false, function ($c, $i) {return count($i);}
     * @expect '1' when input array('flags' => array('spvar' => 0)), 1, 1, false, function ($c, $i) {return print_r($i, true);}
     * @expect '0' when input array('flags' => array('spvar' => 0)), 0, 0, false, function ($c, $i) {return print_r($i, true);}
     * @expect '{"b":"c"}' when input array('flags' => array('spvar' => 0)), array('b' => 'c'), array('b' => 'c'), false, function ($c, $i) {return json_encode($i);}
     * @expect 'inv' when input array('flags' => array('spvar' => 0)), array(), 0, true, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'inv' when input array('flags' => array('spvar' => 0)), array(), 0, false, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'inv' when input array('flags' => array('spvar' => 0)), false, 0, true, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'inv' when input array('flags' => array('spvar' => 0)), false, 0, false, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'inv' when input array('flags' => array('spvar' => 0)), '', 0, true, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'cb' when input array('flags' => array('spvar' => 0)), '', 0, false, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'inv' when input array('flags' => array('spvar' => 0)), 0, 0, true, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'cb' when input array('flags' => array('spvar' => 0)), 0, 0, false, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'inv' when input array('flags' => array('spvar' => 0)), new stdClass, 0, true, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect 'cb' when input array('flags' => array('spvar' => 0)), new stdClass, 0, false, function ($c, $i) {return 'cb';}, function ($c, $i) {return 'inv';}
     * @expect '268' when input array('flags' => array('spvar' => 1), 'sp_vars'=>array('root' => 0)), array(1,3,4), 0, false, function ($c, $i) {return $i * 2;}
     * @expect '038' when input array('flags' => array('spvar' => 1), 'sp_vars'=>array('root' => 0)), array(1,3,'a'=>4), 0, true, function ($c, $i) {return $i * $c['sp_vars']['index'];}
     */
    public static function sec($cx, $v, $in, $each, $cb, $else = null) {
        $isAry = is_array($v);
        $isTrav = $v instanceof Traversable;
        $loop = $each;
        $keys = null;
        $last = null;
        $isObj = false;

        if ($isAry && $else !== null && count($v) === 0) {
            $cx['scopes'][] = $in;
            $ret = $else($cx, $in);
            array_pop($cx['scopes']);
            return $ret;
        }

        // #var, detect input type is object or not
        if (!$loop && $isAry) {
            $keys = array_keys($v);
            $loop = (count(array_diff_key($v, array_keys($keys))) == 0);
            $isObj = !$loop;
        }

        if (($loop && $isAry) || $isTrav) {
            if ($each && !$isTrav) {
                // Detect input type is object or not when never done once
                if ($keys == null) {
                    $keys = array_keys($v);
                    $isObj = (count(array_diff_key($v, array_keys($keys))) > 0);
                }
            }
            $ret = array();
            $cx['scopes'][] = $in;
            $i = 0;
            if ($cx['flags']['spvar']) {
                $old_spvar = $cx['sp_vars'];
                $cx['sp_vars'] = array(
                    '_parent' => $old_spvar,
                    'root' => $old_spvar['root'],
                );
                if (!$isTrav) {
                    $last = count($keys) - 1;
                }
            }
            foreach ($v as $index => $raw) {
                if ($cx['flags']['spvar']) {
                    $cx['sp_vars']['first'] = ($i === 0);
                    $cx['sp_vars']['last'] = ($i == $last);
                    $cx['sp_vars']['key'] = $index;
                    $cx['sp_vars']['index'] = $i;
                    $i++;
                }
                $ret[] = $cb($cx, $raw);
            }
            if ($cx['flags']['spvar']) {
                if ($isObj) {
                    unset($cx['sp_vars']['key']);
                } else {
                    unset($cx['sp_vars']['last']);
                }
                unset($cx['sp_vars']['index']);
                unset($cx['sp_vars']['first']);
                $cx['sp_vars'] = $old_spvar;
            }
            array_pop($cx['scopes']);
            return join('', $ret);
        }
        if ($each) {
            if ($else !== null) {
                $cx['scopes'][] = $in;
                $ret = $else($cx, $v);
                array_pop($cx['scopes']);
                return $ret;
            }
            return '';
        }
        if ($isAry) {
            $cx['scopes'][] = $in;
            $ret = $cb($cx, $v);
            array_pop($cx['scopes']);
            return $ret;
        }

        if ($v === true) {
            return $cb($cx, $in);
        }

        if (!is_null($v) && ($v !== false)) {
            return $cb($cx, $v);
        }

        if ($else !== null) {
            $cx['scopes'][] = $in;
            $ret = $else($cx, $in);
            array_pop($cx['scopes']);
            return $ret;
        }

        return '';
    }

    /**
     * LightnCandy runtime method for {{#with var}} .
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param array<array|string|integer>|string|integer|null $v value to be the new context
     * @param array<array|string|integer>|string|integer|null $in input data with current scope
     * @param Closure $cb callback function to render child context
     * @param Closure|null $else callback function to render child context when {{else}}
     *
     * @return string The rendered string of the token
     *
     * @expect '' when input array(), false, false, function () {return 'A';}
     * @expect '' when input array(), null, null, function () {return 'A';}
     * @expect '{"a":"b"}' when input array(), array('a'=>'b'), array('a'=>'c'), function ($c, $i) {return json_encode($i);}
     * @expect '-b=' when input array(), 'b', array('a'=>'b'), function ($c, $i) {return "-$i=";}
     */
    public static function wi($cx, $v, $in, $cb, $else = null) {
        if (($v === false) || ($v === null)) {
            return $else ? $else($cx, $in) : '';
        }
        $cx['scopes'][] = $in;
        $ret = $cb($cx, $v);
        array_pop($cx['scopes']);
        return $ret;
    }

    /**
     * LightnCandy runtime method for {{> partial}} .
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param string $p partial name
     * @param array<array|string|integer>|string|integer|null $v value to be the new context
     *
     * @return string The rendered string of the partial
     *
     */
    public static function p($cx, $p, $v, $sp = '') {
        $param = $v[0][0];

        if (is_array($v[1])) {
            if (is_array($v[0][0])) {
                $param = array_merge($v[0][0], $v[1]);
            } else if (($cx['flags']['method'] || $cx['flags']['prop']) && is_object($v[0][0])) {
                foreach ($v[1] as $i => $v) {
                    $param->$i = $v;
                }
            }
        }

        return call_user_func($cx['partials'][$p], $cx, $param, $sp);
    }

    /**
     * LightnCandy runtime method for custom helpers.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param string $ch the name of custom helper to be executed
     * @param array<array> $vars variables for the helper
     * @param string $op the name of variable resolver. should be one of: 'raw', 'enc', or 'encq'.
     *
     * @return string The rendered string of the token
     *
     * @expect '=-=' when input array('helpers' => array('a' => function ($i) {return "=$i[0]=";})), 'a', array(array('-'),array()), 'raw'
     * @expect '=&amp;=' when input array('helpers' => array('a' => function ($i) {return "=$i[0]=";})), 'a', array(array('&'),array()), 'enc'
     * @expect '=&#x27;=' when input array('helpers' => array('a' => function ($i) {return "=$i[0]=";})), 'a', array(array('\''),array()), 'encq'
     * @expect '=b=' when input array('helpers' => array('a' => function ($i,$j) {return "={$j['a']}=";})), 'a', array(array(),array('a' => 'b')), 'raw'
     */
    public static function ch($cx, $ch, $vars, $op) {
        return self::chret(call_user_func_array($cx['helpers'][$ch], $vars), $op);
    }

    /**
     * LightnCandy runtime method to handle response of custom helpers.
     *
     * @param string|array<string,array|string|integer> $ret return value from custom helper
     * @param string $op the name of variable resolver. should be one of: 'raw', 'enc', or 'encq'.
     *
     * @return string The rendered string of the token
     *
     * @expect '=&=' when input '=&=', 'raw'
     * @expect '=&amp;&#039;=' when input '=&\'=', 'enc'
     * @expect '=&amp;&#x27;=' when input '=&\'=', 'encq'
     * @expect '=&amp;&#039;=' when input array('=&\'='), 'enc'
     * @expect '=&amp;&#x27;=' when input array('=&\'='), 'encq'
     * @expect '=&amp;=' when input array('=&=', false), 'enc'
     * @expect '=&=' when input array('=&=', false), 'raw'
     * @expect '=&=' when input array('=&=', 'raw'), 'enc'
     * @expect '=&amp;&#x27;=' when input array('=&\'=', 'encq'), 'raw'
     */
    public static function chret($ret, $op) {
        if (is_array($ret)) {
            if (isset($ret[1]) && $ret[1]) {
                $op = $ret[1];
            }
            $ret = $ret[0];
        }

        switch ($op) {
            case 'enc':
                return htmlentities($ret, ENT_QUOTES, 'UTF-8');
            case 'encq':
                return preg_replace('/&#039;/', '&#x27;', htmlentities($ret, ENT_QUOTES, 'UTF-8'));
        }
        return $ret;
    }

    /**
     * LightnCandy runtime method for Handlebars.js style custom helpers.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param string $ch the name of custom helper to be executed
     * @param array<array|string|integer>|string|integer|null $vars variables for the helper
     * @param string $op the name of variable resolver. should be one of: 'raw', 'enc', or 'encq'.
     * @param boolean $inverted the logic will be inverted
     * @param Closure|null $cb callback function to render child context
     * @param Closure|null $else callback function to render child context when {{else}}
     *
     * @return string The rendered string of the token
     */
    public static function hbch($cx, $ch, $vars, $op, $inverted, $cb = null, $else = null) {
        $isBlock = (is_object($cb) && ($cb instanceof Closure));
        $args = $vars[0];
        $options = array(
            'name' => $ch,
            'hash' => $vars[1],
            '_this' => $isBlock ? $op : $inverted,
        );

        // $invert the logic
        if ($inverted) {
            $tmp = $else;
            $else = $cb;
            $cb = $tmp;
        }

        if ($isBlock) {
            $options['fn'] = function ($context = '_NO_INPUT_HERE_') use ($cx, $op, $cb) {
                if ($cx['flags']['echo']) {
                    ob_start();
                }
                if ($context === '_NO_INPUT_HERE_') {
                    $cx['scopes'][] = $op;
                    $ret = $cb($cx, $op);
                } else {
                    $cx['scopes'][] = $op;
                    $ret = $cb($cx, $context);
                }
                array_pop($cx['scopes']);
                return $cx['flags']['echo'] ? ob_get_clean() : $ret;
            };
        }

        if ($else) {
            $options['inverse'] = function ($context = '_NO_INPUT_HERE_') use ($cx, $op, $else) {
                if ($cx['flags']['echo']) {
                    ob_start();
                }
                if ($context === '_NO_INPUT_HERE_') {
                    $ret = $else($cx, $op);
                } else {
                    $cx['scopes'][] = $op;
                    $ret = $else($cx, $context);
                    array_pop($cx['scopes']);
                }
                return $cx['flags']['echo'] ? ob_get_clean() : $ret;
            };
        }

        // prepare $options['data']
        if ($cx['flags']['spvar']) {
            $options['data'] = $cx['sp_vars'];
        }

        $args[] = $options;
        $e = null;
        $r = true;

        try {
            $r = call_user_func_array($cx['hbhelpers'][$ch], $args);
        } catch (Exception $E) {
            $e = "LCRun3: call custom helper '$ch' error: " . $E->getMessage();
        }

        if($e !== null) {
            if ($cx['flags']['debug'] & self::DEBUG_ERROR_LOG) {
                error_log($e);
            }
            if ($cx['flags']['debug'] & self::DEBUG_ERROR_EXCEPTION) {
                throw new Exception($e);
            }
        }

        return self::chret($r, $isBlock ? 'raw' : $op);
    }

    /**
     * LightnCandy runtime method for block custom helpers.
     *
     * @param array<string,array|string|integer> $cx render time context
     * @param string $ch the name of custom helper to be executed
     * @param array<array|string|integer>|string|integer|null $vars variables for the helper
     * @param array<array|string|integer>|string|integer|null $in input data with current scope
     * @param boolean $inverted the logic will be inverted
     * @param Closure $cb callback function to render child context
     * @param Closure|null $else callback function to render child context when {{else}}
     *
     * @return string The rendered string of the token
     *
     * @expect '4.2.3' when input array('blockhelpers' => array('a' => function ($cx) {return array($cx,2,3);})), 'a', array(0, 0), 4, false, function($cx, $i) {return implode('.', $i);}
     * @expect '2.6.5' when input array('blockhelpers' => array('a' => function ($cx,$in) {return array($cx,$in[0],5);})), 'a', array('6', 0), 2, false, function($cx, $i) {return implode('.', $i);}
     * @expect '' when input array('blockhelpers' => array('a' => function ($cx,$in) {})), 'a', array('6', 0), 2, false, function($cx, $i) {return implode('.', $i);}
     */
    public static function bch($cx, $ch, $vars, $in, $inverted, $cb, $else = null) {
        $r = call_user_func($cx['blockhelpers'][$ch], $in, $vars[0], $vars[1]);

        // $invert the logic
        if ($inverted) {
            $tmp = $else;
            $else = $cb;
            $cb = $tmp;
        }

        $ret = '';
        if (is_null($r)) {
            if ($else) {
                $cx['scopes'][] = $in;
                $ret = $else($cx, $r);
                array_pop($cx['scopes']);
            }
        } else {
            if ($cb) {
                $cx['scopes'][] = $in;
                $ret = $cb($cx, $r);
                array_pop($cx['scopes']);
            }
        }

        return $ret;
    }
}
