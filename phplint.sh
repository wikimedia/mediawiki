#! /bin/bash
# Author Addshore

find ./ -type f -name 'LocalSettings.php' -exec php -l {} \; | grep 
"Errors parsing ";

# Flip the exit code
if [ $? -ne 0 ]
then
	exit 0
else
	exit 1
fi#! /bin/bash
# Author Addshore

find ./ -type f -name 'LocalSettings.php' -exec php -l {} \; | grep 
"Errors parsing ";

# Flip the exit code
if [ $? -ne 0 ]
then
	exit 0
else
	exit 1
fi
