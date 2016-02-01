rem http://stackoverflow.com/a/986041/631369
call tests\cleanup.cmd
fsutil file createnew tests\temp\smallfile.tmp 1048576
fsutil file createnew tests\temp\mediumfile.tmp 2149580800
fsutil file createnew tests\temp\bigfile.tmp 4299161600
