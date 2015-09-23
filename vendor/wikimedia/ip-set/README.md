IPSet
=====

IPSet is a PHP library for matching IP addresses against a set of CIDR
specifications.

Here is how you use it:

<pre lang="php">
// At startup, calculate the optimized data structure for the set:
$ipset = new IPSet( array(
    '208.80.154.0/26',
    '2620:0:861:1::/64',
    '10.64.0.0/22',
) );

// Runtime check against cached set (returns bool):
if ( $ipset->match( $ip ) ) {
    // ...
}
</pre>

In rough benchmarking, this takes about 80% more time than `in_array()` checks
on a short (a couple hundred at most) array of addresses.  It's fast either way
at those levels, though, and IPSet would scale better than in_array if the
array were much larger.

For mixed-family CIDR sets, however, this code gives well over 100x speedup vs
iterating `IP::isInRange()` over an array of CIDR specs.

The basic implementation is two separate binary trees (IPv4 and IPv6) as nested
php arrays with keys named 0 and 1.  The values false and true are terminal
match-fail and match-success, otherwise the value is a deeper node in the tree.

A simple depth-compression scheme is also implemented: whole-byte tree
compression at whole-byte boundaries only, where no branching occurs during
that whole byte of depth.  A compressed node has keys 'comp' (the byte to
compare) and 'next' (the next node to recurse into if 'comp' matched successfully).

For example, given these inputs:

<pre>
25.0.0.0/9
25.192.0.0/10
</pre>

The v4 tree would look like:

<pre lang="php">
root4 => array(
    'comp' => 25,
    'next' => array(
        0 => true,
        1 => array(
            0 => false,
            1 => true,
        ),
    ),
);
</pre>

(multi-byte compression nodes were attempted as well, but were
a net loss in my test scenarios due to additional match complexity)


License
-------
Copyright 2014, 2015 Brandon Black <blblack@gmail.com>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
<http://www.gnu.org/copyleft/gpl.html>
