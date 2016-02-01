# Drivers testing methodic #

There are two boundaries that causes problems - 2^31 bytes size and 2^32 bytes in size. 

This means to cover all cases we need three files:
- size < 2^31
- 2^31 < size  < 2^32
- size > 2^32

For more information please refer to:
- [setup.sh](setup.sh) and [setup.cmd](setup.cmd) - creating testing fixtures (three big files)
- [BaseDriverTest](BigFileTools/Driver/BaseDriverTest.php) - for test asserts 