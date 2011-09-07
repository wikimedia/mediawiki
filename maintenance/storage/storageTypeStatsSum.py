#!/usr/bin/python


"""

    For more detail, see http://wikitech.wikimedia.org/view/Text_storage_data


    reads in a file which should contain the output of
    ben@hume:~$ /home/w/bin/foreachwiki maintenance/storage/storageTypeStats.php > /tmp/storageTypeStats.log
    Parses it and sums up the values for all wikis.
    prints this sum to stdout.

    Example content:

ben@fenari:~/storageStats$ cat sample_output.txt
-----------------------------------------------------------------
aawiki
-----------------------------------------------------------------
aawiki:  Using bin size of 100
aawiki:  0^M1000^M2000^M3000^M4000^M5000^M6000^M7000^M8000^M9000^M10000^M
aawiki:  
aawiki:  Flags                         Class                                   Count               old_id range                 
aawiki:  ------------------------------------------------------------------------------------------------------------------------
aawiki:  gzip                          [none]                                  4568                0             - 4700         
aawiki:  [none]                        [none]                                  1615                4600          - 6300         
aawiki:  utf-8,gzip                    [none]                                  1883                5300          - 8300         
aawiki:  external,utf-8                CGZ pointer                             626                 6200          - 10300        
aawiki:  external,utf-8                DHB pointer                             368                 9100          - 10300        
aawiki:  utf-8,gzip,external           simple pointer                          975                 8200          - 10400        
aawiki:  external,utf8                 DHB pointer                             211                 9400          - 10200        
-----------------------------------------------------------------
aawikibooks
-----------------------------------------------------------------
aawikibooks:  Using bin size of 100
aawikibooks:  0^M1000^M2000^M3000^M
aawikibooks:  
aawikibooks:  Flags                         Class                                   Count               old_id range                 
aawikibooks:  ------------------------------------------------------------------------------------------------------------------------
aawikibooks:  [none]                        [none]                                  881                 0             - 1000         
aawikibooks:  external,utf-8                CGZ pointer                             187                 0             - 3400         
aawikibooks:  external,utf-8                DHB pointer                             34                  3200          - 3400         
aawikibooks:  object                        historyblobcurstub                      898                 900           - 1900         
aawikibooks:  utf-8,gzip                    [none]                                  900                 1800          - 2900         
aawikibooks:  utf-8,gzip,external           simple pointer                          431                 2800          - 3400         
aawikibooks:  external,utf8                 DHB pointer                             25                  3300          - 3400         

"""


import re
import optparse

##
##  set up argument parsing.  Require --input (or -i) and a filename.
usage = "usage: %prog <input>"
desc = """Sum the storage types across all wikis.  The input file should
contain the output of:
   foreachwiki maintenance/storage/storageTypeStats.php
"""

parser = optparse.OptionParser(usage=usage, description=desc)
(opts, args) = parser.parse_args()
if len(args) != 1:
    print "I can't do anything without a file to parse. Sorry!"
    parser.print_help()
    exit(1)

input = args[0]

try:
    file=open(input, 'r')

    # create a bunch of regexes to match various sections of the file
    # a section starts with nothing on the line but the name of the wiki db
    #aawikibooks
    start_section = re.compile("^(?P<dbname>[a-z0-9_]+)$")
    #aawikibooks:  external,utf-8     DHB pointer      34    3200    - 3400
    counter = re.compile("^[a-z0-9_]*: *(?P<flags>[^ ]+)  +(?P<class>[^ ]+ [^ ]*)  +(?P<count>\d+)  +.*")

    # create a bunch of counters
    wiki_count=0
    content_counters = dict()

    # ok, parse the file and collect stats!
    for line in file:
        match = start_section.match(line)
        if match:
            # this isn't actually used yet, but is in here for when we 
            # want more interesting stats and collect per-db
            wiki_count += 1
            db_name=match.group('dbname')
        match = counter.match(line)
        if match:
            # sum all unique class,flags combinations
            key = "%s/%s" % (match.group('flags'), match.group('class'))
            try:
                content_counters[key] += int(match.group('count'))
            except KeyError:
                content_counters[key] = int(match.group('count'))


except IOError, e:
    print "omg io error %s!" % e
    raise e

print "Results:"
print "       Count    Type"
print "------------------------------------------"
for key in sorted(content_counters.keys()):
    print "%12d    %s" % (content_counters[key], key)
print "all done!"

