# Running tests #

To run tests follow receipt stored in root of this repository. For Linux in `.travis.yml` or `.gitlab-ci.yml` and for Windows in `appveyor.yml`.

## Requirements ##

These tests requires approximately 6GB for free space on volume. If you are running tests in VM using dynamic allocated disk you do not need to worry about space. Tests preallocates files. This means that there is reserved space on disk these files, however they are not physically written tho disk.