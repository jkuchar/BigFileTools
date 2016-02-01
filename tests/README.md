# Running tests #

To run tests follow receipt stored in root of this repository. For Linux in `.travis.yml` or `.gitlab-ci.yml` and for Windows in `appveyor.yml`.

This project uses Nette\Tester. Please follow how to run tests from there.

Tests requires to have fixture files. Create them by calling `setup.sh` or `setup.cmd`.

## Requirements ##

These tests requires approximately 6GB for free space on volume. If you are running tests in VM using dynamic allocated disk you do not need to worry about space. Tests preallocates files  - this means that there will be reserved space on disk for these files, however there will be physically nothing written to disk (except file system headers).

## Drivers testing methodic ##

There are two boundaries that causes problems - 2^31 bytes size and 2^32 bytes in size. 

This means to cover all cases we need three files:
- size < 2^31
- 2^31 < size  < 2^32
- size > 2^32

For more information please refer to:
- [setup.sh](setup.sh) and [setup.cmd](setup.cmd) - creating testing fixtures (three big files)
- [BaseDriverTest](BigFileTools/Driver/BaseDriverTest.php) - for test asserts 