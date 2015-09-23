<?php

// Custom Helper Interface ... noname arguments
// Template: {{helper1 article.url article.text}}
function helper1 ($args, $named) {
    $u = (isset($args[0])) ? $args[0] : 'undefined';
    $t = (isset($args[1])) ? $args[1] : 'undefined';
    return "<a href=\"{$u}\">{$t}</a>";
}

// Custom Helper Interface ... named arguments
// Template: {{helper1 url=article.url text=article.text [ur"l]=article.extra}}
function helper2 ($args, $named) {
    $u = isset($named['url']) ? jsraw($named['url']) : 'undefined';
    $t = isset($named['text']) ? jsraw($named['text']) : 'undefined';
    $x = isset($named['ur"l']) ? $named['ur"l'] : 'undefined';
    return "<a href=\"{$u}\">{$t}</a>({$x})";
}

// Block Custom Helper Interface ... 
// Template: {{helper3 articles}}
function helper3 ($cx, $args, $named) {
    return Array('test1', 'test2', 'test3');
}

// Block Custom Helper Interface ... 
// Template: {{helper3 val=values odd=enable_odd}}
function helper4 ($cx, $args, $named) {
    if (isset($named['val']) && is_array($cx)) {
        $cx['helper4_value'] = $named['val'] % 2;
        return $cx;
    }
    if (isset($named['odd'])) {
        return Array(1,3,5,7,9);
    }
}

// Handlebars.js Custom Helper Interface ...
// Template: {{#myeach articles}}Article: ....{{/myeach}}
function myeach ($list, $options) {
    foreach ($list as $item) {
        $ret .= $options['fn']($item);
    }
    return $ret;
}

// Simulate Javascript toString() behaviors
function jsraw ($i) {
    if ($i === true) {
        return 'true';
    }
    if ($i === false) {
        return 'false';
    }
    return $i;
}

?>
