<?php

declare ( ticks = 1 ):
	echo "foo";
enddeclare;

for ( $i = 0; $i < 5; $i++ ):
	echo $i;
endfor;

foreach ( array( 1, 2, 3 ) as $i ):
	echo $i;
endforeach;

$x = 1;

if ( $x < 2 ):
	echo $x;
endif;

switch ( $x ):
	case 2:
		echo $x;
		break;
endswitch;

while ( $x > 2 ):
	echo $i;
endwhile;
