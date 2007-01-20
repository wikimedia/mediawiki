test: Test.php
	prove -r t

verbose:
	prove -v -r t | egrep -v '^ok'
