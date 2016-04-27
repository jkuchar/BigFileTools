#!/bin/bash 

# http://stackoverflow.com/a/8706714/631369
# 2048M = 2^31 bytes
# 4096M = 2^32 bytes

bash tests/cleanup.sh

touch tests/temp/emptyfile.tmp
touch "tests/temp/empty - file.tmp"
truncate -s 1M tests/temp/smallfile.tmp
truncate -s 2050M tests/temp/mediumfile.tmp
truncate -s 4100M tests/temp/bigfile.tmp
