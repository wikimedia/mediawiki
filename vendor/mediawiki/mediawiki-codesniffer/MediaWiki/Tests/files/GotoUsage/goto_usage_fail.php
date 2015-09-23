<?php

for ( $i=0; $i < 20; $i++ ) {
	if ( $i == 15 ) {
		goto endloop;
	}
}
endloop:
echo "Done";
