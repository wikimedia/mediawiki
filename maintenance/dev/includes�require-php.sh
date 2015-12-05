# Include-able script to require that we have a known php binary we can execute

. "$DEV/includes/php.sh"

if [ "x$PHP" == "x" -o ! -x "$PHP" ]; then
	echo "Local copy of PHP is not installed"
	exit 1
fi
