<?php

declare ( ticks = 1 ) {
	echo "foo";
}

for ( $i = 0; $i < 5; $i++ ) {
	echo $i;
}

foreach ( array( 1, 2, 3 ) as $i ) {
	echo $i;
}

$x = 1;

if ( $x < 2 ) {
	echo $x;
}

switch ( $x ) {
	case 2:
		echo $x;
		break;
}

while ( $x > 2 ) {
	echo $i;
}
