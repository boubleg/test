# Ruslan Khaibullin test task

## Installation

You will need to configure db connection in the file app/config/db.json
* I did not implement any adapters other than MySQL but I made all the preparations to use any other

### If you have vagrant installed
Simply run *$ vagrant up* from the root directory of the project
### Otherwise
Since the project isn't dependent on any external library aside from basic mysqli extension you should be able
to simply use it using any environment with PHP and MySQL installed. The project is using PHP 7 syntax so if you
want to use it with PHP 5.4+ then you should use branch 'php5.4'

## Usage

### Dec 20th at 23:00

*$ php scripts/beforeFix.php*
* I added an option to populate db with some random data just for the sake of testing execution time on bigger
amount of data so if you want to use it for whatever reason you can alter the numbers in it an then run it as
*$ php scripts/beforeFix.php --populate*

### Dec 28th 01:00

*$ php scripts/afterFix.php*
