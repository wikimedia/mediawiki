#!/usr/bin/python


"""

    For more detail, see http://wikitech.wikimedia.org/view/Text_storage_data

    reads in two files which should contain the output of storageTypeStatsSum.py
    Parses them both and calculates the difference for each storage type
    prints this to stdout.

    For best results, give the old and new files their dates for names, eg:
    ben@fenari:~/storageStats$ ./storageTypeStatsDiff.py 2010-02-18 2011-08-31

    Example content:

ben@fenari:~/storageStats$ cat 2010-02-18
Results:
       Count    Type
------------------------------------------
           9    0,external/simple pointer
         435    0/[none]
     1482941    [none]/[none]
      968957    gzip/[none]
      178234    object,external/simple pointer
        1800    object,utf-8/[none]
    17076928    utf-8,gzip/[none]
        1269    utf-8/[none]
all done!

ben@fenari:~/storageStats$ cat 2011-08-31
Results:
       Count    Type
------------------------------------------
           9    0,external/simple pointer
        1435    0/[none]
     1002341    [none]/[none]
     1234212    object,external/simple pointer
         213    object,external/blob
          20    object,utf-8/[none]
      123428    utf-8,gzip/[none]
         123    utf-8/[none]
all done!

"""


import re
import optparse

##
##  set up argument parsing.
usage = "usage: %prog <old-stats-file> <new-stats-file>"
desc = "Calculate the difference between two files containing storageTypeStatsSum.py output"
parser = optparse.OptionParser(usage=usage, description=desc)
(opts, args) = parser.parse_args()
# Require exactly two arguments
if len(args) != 2:
    print "Two files needed."
    parser.print_help()
    exit()

try:
    oldfile=open(args[0], 'r')
    newfile=open(args[1], 'r')
except IOError, e:
    print "IOError trying to open %s or %s: %s\n" % (args[0], args[1], e)
    exit(1)

# match only the actual value / key lines; ignore everything else
valueline = re.compile("^ *(?P<val>\d+) *(?P<desc>.*)$")

files={}
# ok, parse the files and collect stats!
for file in (oldfile, newfile):
    stats = {}
    for line in file:
        match = valueline.match(line)
        if match:
            stats[match.group('desc')] = int(match.group('val'))
    #stats collected for one file, save it to the files dict
    files[file.name] = stats

# calculate the difference
diff = {} # contains numbers keyed on storage types
allkeys = []
# collect keys from both sets in case they don't match
for stats in files.keys():
    # get the union of allkeys and this file's stats keys
    allkeys = list( set(allkeys) | set(files[stats].keys()) )
for key in allkeys:
    try:
        diff[key] = files[newfile.name][key] - files[oldfile.name][key]
    except KeyError:
        # this happens when a key only exists in one set
        diff[key] = 'n/a'

# print out results
print "%12s %12s %12s   %s" % (oldfile.name, newfile.name, 'Diff', 'Type')
print "---------------------------------------------------------------------"
for key in sorted(allkeys):
    try:
        oldval = files[oldfile.name][key]
    except KeyError:
        oldval = 'n/a'
    try:
        newval = files[newfile.name][key]
    except KeyError:
        newval = 'n/a'
    diffnum = diff[key]
    name = key
    print "%12s %12s %12s   %s" % (oldval, newval, diffnum, name)

