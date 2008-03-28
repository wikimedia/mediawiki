<?php
# See the end of this file for documentation

# The latest release of this test framework can always be found on CPAN:
# http://search.cpan.org/search?query=Test.php

register_shutdown_function('_test_ends');

$__Test = array(
    # How many tests are planned
    'planned'   => null,

    # How many tests we've run, if 'planned' is still null by the time we're
    # done we report the total count at the end
    'run' => 0,

    # Are are we currently within todo_start()/todo_end() ?
    'todo' => array(),
);

function plan($plan, $why = '')
{
    global $__Test;

    $__Test['planned'] = true;

    switch ($plan)
    {
      case 'no_plan':
        $__Test['planned'] = false;
        break;
      case 'skip_all';
        printf("1..0%s\n", $why ? " # Skip $why" : '');
        exit;
      default:
        printf("1..%d\n", $plan);
        break;
    }
}

function pass($desc = '')
{
    return _proclaim(true, $desc);
}

function fail($desc = '')
{
    return _proclaim(false, $desc);
}

function ok($cond, $desc = '') {
    return _proclaim($cond, $desc);
}

function is($got, $expected, $desc = '') {
    $pass = $got == $expected;
    return _proclaim($pass, $desc, /* todo */ false, $got, $expected);
}

function isnt($got, $expected, $desc = '') {
    $pass = $got != $expected;
    return _proclaim($pass, $desc, /* todo */ false, $got, $expected, /* negated */ true);
}

function like($got, $expected, $desc = '') {
    $pass = preg_match($expected, $got);
    return _proclaim($pass, $desc, /* todo */ false, $got, $expected);
}

function unlike($got, $expected, $desc = '') {
    $pass = !preg_match($expected, $got);
    return _proclaim($pass, $desc, /* todo */ false, $got, $expected, /* negated */ true);
}

function cmp_ok($got, $op, $expected, $desc = '')
{
    $pass = null;

    # See http://www.php.net/manual/en/language.operators.comparison.php
    switch ($op)
    {
      case '==':
        $pass = $got == $expected;
        break;
      case '===':
        $pass = $got === $expected;
        break;
      case '!=':
      case '<>':
        $pass = $got != $expected;
        break;
      case '!==':
        $pass = $got !== $expected;
        break;
      case '<':
        $pass = $got < $expected;
        break;
      case '>':
        $pass = $got > $expected;
        break;
      case '<=':
        $pass = $got <= $expected;
        break;
      case '>=':
        $pass = $got >= $expected;
        break;
    default:
        if (function_exists($op)) {
            $pass = $op($got, $expected);
        } else {
            die("No such operator or function $op\n");
        }
    }

    return _proclaim($pass, $desc, /* todo */ false, $got, "$got $op $expected");
}

function diag($message)
{
    if (is_array($message))
    {
        $message = implode("\n", $message);
    }

    foreach (explode("\n", $message) as $line)
    {
        echo "# $line\n";
    }
}

function include_ok($file, $desc = '')
{
    $pass = include $file;
    return _proclaim($pass, $desc == '' ? "include $file" : $desc);
}

function require_ok($file, $desc = '')
{
    $pass = require $file;
    return _proclaim($pass, $desc == '' ? "require $file" : $desc);
} 

function is_deeply($got, $expected, $desc = '')
{
    $diff = _cmp_deeply($got, $expected);
    $pass = is_null($diff);

    if (!$pass) {
        $got      = strlen($diff['gpath']) ? ($diff['gpath'] . ' = ' . $diff['got']) 
                                           : _repl($got);
        $expected = strlen($diff['epath']) ? ($diff['epath'] . ' = ' . $diff['expected']) 
                                           : _repl($expected);
    }

    _proclaim($pass, $desc, /* todo */ false, $got, $expected);
}

function isa_ok($obj, $expected, $desc = '')
{
    $pass = is_a($obj, $expected);
    _proclaim($pass, $desc, /* todo */ false, $name, $expected);
}

function todo_start($why = '')
{
    global $__Test;

    $__Test['todo'][] = $why;
}

function todo_end()
{
    global $__Test;

    if (count($__Test['todo']) == 0) {
        die("todo_end() called without a matching todo_start() call");
    } else {
        array_pop($__Test['todo']);
    }
}

#
# The code below consists of private utility functions for the above functions
#

function _proclaim(
    $cond, # bool
    $desc = '',
    $todo = false,
    $got = null,
    $expected = null,
    $negate = false) {

    global $__Test;

    $__Test['run'] += 1;

    # We're in a TODO block via todo_start()/todo_end(). TODO via specific
    # functions is currently unimplemented and will probably stay that way
    if (count($__Test['todo'])) {
        $todo = true;
    }

    # Everything after the first # is special, so escape user-supplied messages
    $desc = str_replace('#', '\\#', $desc);
    $desc = str_replace("\n", '\\n', $desc);

    $ok = $cond ? "ok" : "not ok";
    $directive = '';

    if ($todo) {
        $todo_idx = count($__Test['todo']) - 1;
        $directive .= ' # TODO ' . $__Test['todo'][$todo_idx];
    }

    printf("%s %d %s%s\n", $ok, $__Test['run'], $desc, $directive);

    # report a failure
    if (!$cond) {
        # Every public function in this file calls _proclaim so our culprit is
        # the second item in the stack
        $caller = debug_backtrace();
        $call = $caller['1'];
    
        diag(
            sprintf(" Failed%stest '%s'\n in %s at line %d\n       got: %s\n  expected: %s",
                $todo ? ' TODO ' : ' ',
                $desc,
                $call['file'],
                $call['line'],
                $got,
                $expected
            )
        );
    }

    return $cond;
}

function _test_ends()
{
    global $__Test;

    if (count($__Test['todo']) != 0) {
        $todos = join("', '", $__Test['todo']);
        die("Missing todo_end() for '$todos'");
    }

    if (!$__Test['planned']) {
        printf("1..%d\n", $__Test['run']);
    }
}

#
# All of the below is for is_deeply()
#

function _repl($obj, $deep = true) {
    if (is_string($obj)) {
        return "'" . $obj . "'";
    } else if (is_numeric($obj)) {
        return $obj;
    } else if (is_null($obj)) {
        return 'null';
    } else if (is_bool($obj)) {
        return $obj ? 'true' : 'false';
    } else if (is_array($obj)) {
        return _repl_array($obj, $deep);
    }else {
        return gettype($obj);
    }
}

function _diff($gpath, $got, $epath, $expected) {
    return array(
        'gpath'     => $gpath,
        'got'       => $got,
        'epath'     => $epath,
        'expected'  => $expected
    );
}

function _idx($obj, $path = '') {
    return $path . '[' . _repl($obj) . ']';
}

function _cmp_deeply($got, $exp, $path = '') {
    if (is_array($exp)) {
        
        if (!is_array($got)) {
            return _diff($path, _repl($got), $path, _repl($exp));
        }
        
        $gk = array_keys($got);
        $ek = array_keys($exp);
        $mc = max(count($gk), count($ek));

        for ($el = 0; $el < $mc; $el++) {
            # One array shorter than the other?
            if ($el >= count($ek)) {
                return _diff(_idx($gk[$el], $path), _repl($got[$gk[$el]]), 
                             'missing', 'nothing');
            } else if ($el >= count($gk)) {
                return _diff('missing', 'nothing', 
                             _idx($ek[$el], $path), _repl($exp[$ek[$el]]));
            }
            
            # Keys differ?
            if ($gk[$el] != $ek[$el]) {
                return _diff(_idx($gk[$el], $path), _repl($got[$gk[$el]]), 
                             _idx($ek[$el], $path), _repl($exp[$ek[$el]]));
            }

            # Recurse
            $rc = _cmp_deeply($got[$gk[$el]], $exp[$ek[$el]], _idx($gk[$el], $path));
            if (!is_null($rc)) {
                return $rc;
            }
        }
    }
    else {
        # Default to serialize hack
        if (serialize($got) != serialize($exp)) {
            return _diff($path, _repl($got), $path, _repl($exp));
        }
    }
    
    return null;
}

function _plural($n, $singular, $plural = null) {
    if (is_null($plural)) {
        $plural = $singular . 's';
    }
    return $n == 1 ? "$n $singular" : "$n $plural";
}

function _repl_array($obj, $deep) {
    if ($deep) {
        $slice = array_slice($obj, 0, 3); # Increase from 3 to show more
        $repl  = array();
        $next  = 0;
        foreach ($slice as $idx => $el) {
            $elrep = _repl($el, false);
            if (is_numeric($idx) && $next == $idx) {
                // Numeric index
                $next++;
            } else {
                // Out of sequence or non-numeric
                $elrep = _repl($idx, false) . ' => ' . $elrep;
            }
            $repl[] = $elrep;
        }
        $more = count($obj) - count($slice);
        if ($more > 0) {
            $repl[] = '... ' . _plural($more, 'more element')  . ' ...';
        }
        return 'array(' . join(', ', $repl) . ')';
    }
    else {
        return 'array(' . count($obj) . ')';
    }
}

/*

=head1 NAME

Test.php - TAP test framework for PHP with a L<Test::More>-like interface

=head1 SYNOPSIS

    #!/usr/bin/env php
    <?php  
    require 'Test.php';
  
    plan($num); # plan $num tests
    # or
    plan('no_plan'); # We don't know how many
    # or
    plan('skip_all'); # Skip all tests
    # or
    plan('skip_all', $reason); # Skip all tests with a reason
  
    diag('message in test output') # Trailing \n not required
  
    # $test_name is always optional and should be a short description of
    # the test, e.g. "some_function() returns an integer"
  
    # Various ways to say "ok"
    ok($got == $expected, $test_name);
  
    # Compare with == and !=
    is($got, $expected, $test_name);
    isnt($got, $expected, $test_name);
  
    # Run a preg regex match on some data
    like($got, $regex, $test_name);
    unlike($got, $regex, $test_name);
  
    # Compare something with a given comparison operator
    cmp_ok($got, '==', $expected, $test_name);
    # Compare something with a comparison function (should return bool)
    cmp_ok($got, $func, $expected, $test_name);
  
    # Recursively check datastructures for equalness
    is_deeply($got, $expected, $test_name);
  
    # Always pass or fail a test under an optional name
    pass($test_name);
    fail($test_name);

    # TODO tests, these are expected to fail but won't fail the test run,
    # unexpected success will be reported
    todo_start("integer arithmetic still working");
    ok(1 + 2 == 3);
    {
        # TODOs can be nested
        todo_start("string comparison still working")
        is("foo", "bar");
        todo_end();
    }
    todo_end();
    ?>
  
=head1 DESCRIPTION

F<Test.php> is an implementation of Perl's L<Test::More> for PHP. Like
Test::More it produces language agnostic TAP output (see L<TAP>) which
can then be gathered, formatted and summarized by a program that
understands TAP such as prove(1).

=head1 HOWTO

First place the F<Test.php> in the project root or somewhere else in
the include path where C<require> and C<include> will find it.

Then make a place to put your tests in, it's customary to place TAP
tests in a directory named F<t> under the root but they can be
anywhere you like. Make a test in this directory or one of its subdirs
and try running it with php(1):

    $ php t/pass.t 
    1..1
    ok 1 This dummy test passed

The TAP output consists of very simple output, of course reading
larger output is going to be harder which is where prove(1) comes
in. prove is a harness program that reads test output and produces
reports based on it:
    
    $ prove t/pass.t 
    t/pass....ok
    All tests successful.
    Files=1, Tests=1,  0 wallclock secs ( 0.03 cusr +  0.02 csys =  0.05 CPU)

To run all the tests in the F<t> directory recursively use C<prove -r
t>. This can be put in a F<Makefile> under a I<test> target, for
example:

    test: Test.php
		prove -r t
    
For reference the example test file above looks like this, the shebang
on the first line is needed so that prove(1) and other test harness
programs know they're dealing with a PHP file.

    #!/usr/bin/env php
    <?php
    
    require 'Test.php';
    
    plan(1);
    pass('This dummy test passed');
    ?>
    
=head1 SEE ALSO

L<TAP> - The TAP protocol

=head1 AUTHOR

E<AElig>var ArnfjE<ouml>rE<eth> Bjarmason <avar@cpan.org> and Andy Armstrong <andy@hexten.net>

=head1 LICENSING

The author or authors of this code dedicate any and all copyright
interest in this code to the public domain. We make this dedication
for the benefit of the public at large and to the detriment of our
heirs and successors. We intend this dedication to be an overt act of
relinquishment in perpetuity of all present and future rights this
code under copyright law.

=cut

*/
