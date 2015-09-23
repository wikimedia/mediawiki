# Include-able script to determine the location of our php if any
# We search for a environment var called PHP, native php,
# a local copy, home directory location used by installphp.sh
# and previous home directory location
# The binary path is returned in $PHP if any

for binary in $PHP $(which php || true) "$DEV/php/bin/php" "$HOME/.mediawiki/php/bin/php" "$HOME/.mwphp/bin/php" ]; do
	if [ -x "$binary" ]; then
		if "$binary" -r 'exit((int)!version_compare(PHP_VERSION, "5.4", ">="));'; then
			PHP="$binary"
			break
		fi
	fi
done
