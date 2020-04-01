#!/bin/bash

patch_dir="_bluespice/patches"

GREEN='\033[0;32m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
NC='\033[0m'

while read -r patch_file
do
    original_file=$(echo $patch_file | sed "s/\.diff//g")
    original_file=$(echo $original_file | sed "s/_bluespice\/patches\///g")
    printf "\n${PURPLE}Patching: ${GREEN}$original_file${NC} ==> "
    cmdout=$(/usr/bin/env patch --ignore-whitespace --fuzz 3 --dry-run $original_file $patch_file)
    if [[ "$cmdout" == *"FAILED"* ]]; then
        printf "${RED}FAILED!${NC}\n"
    else
        cmdout=$(/usr/bin/env patch -s $original_file $patch_file)
        printf "${GREEN}DONE!${NC}\n"
    fi
done < <(find $patch_dir -type f -name "*\.diff")
