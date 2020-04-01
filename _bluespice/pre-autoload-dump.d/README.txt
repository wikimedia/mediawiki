pre-autoload-dump scripts

* 99-apply_patches.sh:
   Applies each patch files to the correct files. Patches should be put in bluespice/patches folder. Directory hierarchy must be same including file name.
   Patch files must have .diff suffix.
   Example: 
     Target file: extensions/Form/Form.php
     Diff file: _bluespice/patches/extensions/Form/Form.php.diff