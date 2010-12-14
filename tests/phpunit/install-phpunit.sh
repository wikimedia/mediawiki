#!/bin/sh

if [ `id -u` -ne 0 ]; then
    echo '*** ERROR' Must be root to run
    exit 1
fi

if ( has_binary phpunit ); then
    echo PHPUnit already installed
else if ( has_binary pear ); then
    echo Installing phpunit with pear
    pear channel-discover pear.phpunit.de
    pear install phpunit/PHPUnit
else if ( has_binary apt-get ); then
    echo Installing phpunit with apt-get
    apt-get install phpunit
else if ( has_binary yum ); then
    echo Installing phpunit with yum
    yum install phpunit
fi
fi
fi
fi
