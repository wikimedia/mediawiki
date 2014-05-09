#!/bin/sh

has_binary () {
    if [ -z `which $1` ]; then
        return 1
    fi
    return 0
}

if [ `id -u` -ne 0 ]; then
    echo '*** ERROR: Must be root to run'
    exit 1
fi

if ( has_binary phpunit ); then
    echo PHPUnit already installed
else if ( has_binary pear ); then
    echo Installing phpunit with pear
    pear channel-discover pear.phpunit.de
    pear channel-discover components.ez.no
    pear channel-discover pear.symfony.com
    pear update-channels
    #Temporary fix for 64597
    pear install --alldeps phpunit/PHPUnit-3.7.35
else if ( has_binary apt-get ); then
    echo Installing phpunit with apt-get
    apt-get install phpunit
else if ( has_binary yum ); then
    echo Installing phpunit with yum
    yum install phpunit
else if ( has_binary port ); then
    echo Installing phpunit with macports
    port install php5-unit
fi
fi
fi
fi
fi
