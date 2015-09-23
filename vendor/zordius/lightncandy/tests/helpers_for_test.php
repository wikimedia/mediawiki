<?php

// Class for customized LCRun
class MyLCRunClass extends LCRun3 {
    public static function raw($cx, $v) {
        return '[[DEBUG:raw()=>' . var_export($v, true) . ']]';
    }
}

// Classes for inputs or helpers
class myClass {
    function test() {
        return 'testMethod OK!';
    }

    function helper2($arg) {
        return is_array($arg) ? '=Array=' : "=$arg=";
    }

    function __call($method, $args) {
        return "-- $method:" . print_r($args, true);
    }
}

class foo {
    public $prop = 'Yes!';

    function bar() {
        return 'OK!';
    }
}

class twoDimensionIterator implements Iterator {
    private $position = 0;
    private $x = 0;
    private $y = 0;
    private $w = 0;
    private $h = 0;

    public function __construct($w, $h) {
        $this->w = $w;
        $this->h = $h;
        $this->rewind();
    }

    function rewind() {
        $this->position = 0;
        $this->x = 0;
        $this->y = 0;
    }

    function current() {
        return $this->x * $this->y;
    }

    function key() {
        return $this->x . 'x' . $this->y;
    }

    function next() {
        ++$this->position;
        $this->x = $this->position % $this->w;
        $this->y = floor($this->position / $this->w);
    }

    function valid() {
        return $this->position < $this->w * $this->h;
    }
}

// Custom helpers
function helper1($arg) {
    $arg = is_array($arg) ? 'Array' : $arg;
    return "-$arg-";
}                                                                                                                                          
function alink($u, $t) {
    $u = is_array($u) ? 'Array' : $u;
    $t = is_array($t) ? 'Array' : $t;
    return "<a href=\"$u\">$t</a>";
}

 function meetup_date_format() {
    return "OKOK~1";
}

function  meetup_date_format2() {
    return "OKOK~2";
}

function        meetup_date_format3 () {
    return "OKOK~3";
}

function	meetup_date_format4(){
    return "OKOK~4";};


function test_array ($input) {
   return is_array($input[0]) ? 'IS_ARRAY' : 'NOT_ARRAY';
}

function test_join ($input) {
   return join('.', $input[0]);
}

// Custom helpers for handlebars (should be used in hbhelpers)
function myif ($conditional, $options) {
    if ($conditional) {
        return $options['fn']();
    } else {
        return $options['inverse']();
    }
}

function mywith ($context, $options) {
    return $options['fn']($context);
}

function myeach ($context, $options) {
    $ret = '';
    foreach ($context as $cx) {
        $ret .= $options['fn']($cx);
    }
    return $ret;
}

function mylogic ($input, $yes, $no, $options) {
    if ($input === true) {
        return $options['fn']($yes);
    } else {
        return $options['inverse']($no);
    }
}

function mydash ($a, $b) {
    return "$a-$b";
}

function myjoin ($a, $b) {
    return "$a$b";
}

function getroot ($options) {
    return $options['data']['root'];
}

?>
