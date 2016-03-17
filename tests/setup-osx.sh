#!/bin/bash 

# http://stackoverflow.com/a/8706714/631369

# 4096M = 2^32 bits

bash tests/cleanup.sh

touch tests/temp/emptyfile.tmp
gtruncate -s 1M tests/temp/smallfile.tmp
gtruncate -s 2050M tests/temp/mediumfile.tmp
gtruncate -s 4100M tests/temp/bigfile.tmp
