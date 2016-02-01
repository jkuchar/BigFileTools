# Running tests #

To run tests follow receipt stored in root of this repository. For Linux in `.travis.yml` or `.gitlab-ci.yml` and for Windows in `appveyor.yml`.

## Requirements ##

These tests requires approximately 6GB for free space on volume. If you are running tests in VM using dynamic allocated disk you do not need to worry about space. Tests preallocates files  - this means that there will be reserved space on disk for these files, however there will be physically nothing written to disk (except file system headers).