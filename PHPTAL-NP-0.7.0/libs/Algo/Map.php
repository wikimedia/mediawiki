<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//  
//  Copyright (c) 2003 Laurent Bedubourg
//  
//  This library is free software; you can redistribute it and/or
//  modify it under the terms of the GNU Lesser General Public
//  License as published by the Free Software Foundation; either
//  version 2.1 of the License, or (at your option) any later version.
//  
//  This library is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
//  Lesser General Public License for more details.
//  
//  You should have received a copy of the GNU Lesser General Public
//  License along with this library; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//  
//  Authors: Laurent Bedubourg <laurent.bedubourg@free.fr>
//  
// $id$

define('ALGO_MAP_VERSION', '0.1.1');
       
/**
 * Applies a callback(or code) to the elements of given arrays.
 * 
 * This function acts much like the perl 'map' keyword, it pass through all 
 * array parameters and apply a callback function to each elements of 
 * these arrays.
 *
 * See examples below to an overview of Algo_map() usage.
 *
 * *IMPORTANT*
 *
 * Please note that the callback function needs to return something, when you
 * don't want your function to return an element or an hashtable, just return
 * an empty array().
 *
 * 
 * example 1 : Simple use, this example compose an hashtable with an array.
 * 
 * <?php
 * function myfunc($a)
 * {
 *      return array($a=>1);
 * }
 *
 * $a_pam = array("apple", "pie", "cherry");
 *
 * $result = Algo_map('myfunc', $a_pam);
 *
 * // $result ==> array("apple"=>1, "pie"=>1, "cherry"=>1);
 * ?>
 *
 * You can pass more than one array as parameters. The number of elements of
 * each array should be at least equal to the number of elements of the first
 * array.
 *
 * If count($a1) > count($a2), elements of $a2 that doesn't exists
 * will be replaced by a false value.
 *
 * 
 * example 2 : Using more than one array with Algo_map()
 * 
 * <?php
 * function myfunc($a, $b)
 * {
 *      return array($a * $b);
 * }
 *
 * $a_nbr = array(1,2,3,4);
 * $b_nbr = array(1,2,4,8,16);
 *
 * $result = Algo_map('myfunc', $a_nbr, $b_nbr);
 *
 * // $result ==> array(1, 4, 12, 32);
 * ?>
 * 
 *
 * example 3 : Problem of array size
 * 
 * <?php
 * function myfunc($a, $b)
 * {
 *      return array($a * $b);
 * }
 * 
 * $a_nbr = array(1,2,3,4,16);
 * $b_nbr = array(1,2,4,8);
 *
 * $result = Algo_map('myfunc', $a_nbr, $b_nbr);
 *
 * // $result ==> array(1, 4, 12, 32, 0);
 * // the fifth element is equal to 0 because the fifth element of $b_nbr
 * // doesn't exists and is replaced by false(ie:0) in the callback
 * // function.
 * ?>
 * 
 * 
 * example 4 : Defining the function code inside the Algo_map() call
 * 
 * <?php
 * $a_t = array("a","b","c","d");
 * $b_t = array("z","b","e","d");
 * 
 * $result = Algo_map(
 *      // first element match the function arguments two vars $a and $b 
 *      // which will be filled with $a_t[x] and $b_t[x] for x=0 to len(a)
 *      '$a,$b',
 *      // the second parameter is the callback function code
 *      // map always return an array (or an hashtable)
 *      'return($a==$b ? $a : array());',
 *      $a_t,
 *      $b_t
 *     );
 * 
 * // $result ==> array("b","d");
 * ?>
 * 
 *
 * example 5 : Object method callback
 * 
 * <?php
 * class Foo {
 *     function myCallback($a){
 *         return "-$a-";
 *     }
 * };
 *
 * $foo = new Foo();
 * $array = array('a','b','c');
 * $result = Algo_map($foo, 'myCallback', $array);
 *
 * // $result = array('-a-', '-b-', '-c-');
 * ?>
 * 
 * 
 * @param  mixed ... See examples
 * @return array
 * @author Laurent Bedubourg <codebringer@free.fr>
 * @date   2001-09-20
 */
function Algo_map()
{
    // we retrieve the list of map arguments
    $argv = func_get_args();
    // the first argument is the function name
    $func = array_shift($argv);

    if (is_object($func)) {
        // if first argument is an object, this map
        // has a callback to some method
        $obj  = $func;
        $func = array_shift($argv);     
    } else if (!function_exists($func)) {
        // if the function isn't defined,
        // we'll try to create the function
        // the first argument was the string
        // of function arguments(ie:'$a,$b,$c')

        // The code is the second argument
        $code = array_shift($argv);

        // We create the new function
        // it will be a lambda style function
        $func = create_function($func, $code);
    }

    // prepare the result array
    $result = array();

    // we'll apply the new function to
    // each element of arrays passed as
    // parameters
    while (count($argv[0]) > 0) {
        // prepare the array of arguments
        $fargs = array();
        
        for($i = 0; $i < count($argv); $i++) {
            // for each array to map
            // we add the next element of the
            // array to the argument array
            $fargs[] = array_shift($argv[$i]);
        }

        if (isset($obj)) {
            $result = array_merge($result,
                                  call_user_func_array(array($obj,$func),
                                                       $fargs));
        } else {
            // we merge the result of the function
            // with allready found results
            $result = array_merge($result,
                                  // call the function with the array
                                  // of arguments
                                  call_user_func_array($func, $fargs));
        }
    }

    // let's return the result array
    return $result;
}

?>
