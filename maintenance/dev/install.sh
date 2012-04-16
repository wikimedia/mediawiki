#!/bin/bash

if [ "x$BASH_SOURCE" == "x" ]; then echo '$BASH_SOURCE not set'; exit 1; fi
DEV=$(cd -P "$(dirname "${BASH_SOURCE[0]}" )" && pwd)

"$DEV/installphp.sh"
"$DEV/installmw.sh"
"$DEV/start.sh"
