call tests\cleanup.cmd

rem UTF-8 support: http://stackoverflow.com/a/18088002/631369
chcp 65001

rem http://stackoverflow.com/a/986041/631369
fsutil file createnew tests\temp\emptyfile.tmp 0
fsutil file createnew "tests\temp\empty - file.tmp" 0
fsutil file createnew "tests\temp\emptyfileěšů指事字.tmp" 0
fsutil file createnew tests\temp\smallfile.tmp 1048576
fsutil file createnew tests\temp\mediumfile.tmp 2149580800
fsutil file createnew tests\temp\bigfile.tmp 4299161600
