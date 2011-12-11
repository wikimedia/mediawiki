# Include-able script to determine the location of our php if any

if [ -d "$DEV/php" -a -x "$DEV/php/bin/php" ]; then
	# Quick local copy
	PHP="$DEV/php/bin/php"
elif [ -d "$HOME/.mediawiki/php" -a -x "$HOME/.mediawiki/php/bin/php" ]; then
	# Previous home directory location to install php in
	PHP="$HOME/.mediawiki/php/bin/php"
elif [ -d "$HOME/.mwphp" -a -x "$HOME/.mwphp/bin/php" ]; then
	# Previous home directory location to install php in
	PHP="$HOME/.mwphp/bin/php"
fi
