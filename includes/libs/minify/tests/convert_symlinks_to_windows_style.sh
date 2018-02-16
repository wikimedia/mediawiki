#!/usr/bin/env bash
# http://stackoverflow.com/questions/5917249/git-symlinks-in-windows

git config --global alias.rm-symlink '!__git_rm_symlink(){
    git checkout -- "$1"
    link=$(echo "$1")
    POS=$'\''/'\''
    DOS=$'\''\\\\'\''
    doslink=${link//$POS/$DOS}
    dest=$(dirname "$link")/$(cat "$link")
    dosdest=${dest//$POS/$DOS}
    if [ -f "$dest" ]; then
        rm -f "$link"
        cmd //C mklink //H "$doslink" "$dosdest"
    elif [ -d "$dest" ]; then
        rm -f "$link"
        cmd //C mklink //J "$doslink" "$dosdest"
    else
        echo "ERROR: Something went wrong when processing $1 . . ."
        echo "       $dest may not actually exist as a valid target."
    fi
}; __git_rm_symlink "$1"'

git config --global alias.rm-symlinks '!__git_rm_symlinks(){
    for symlink in $(git ls-files -s | egrep "^120000" | cut -f2); do
        git rm-symlink "$symlink"
        git update-index --assume-unchanged "$symlink"
    done
}; __git_rm_symlinks'

git rm-symlinks
