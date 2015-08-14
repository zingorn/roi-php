# roi-php
ROI Test Problem

# Install
* Download Composer

```
php -r "readfile('https://getcomposer.org/installer');" | php
```

* Make project 

```
php composer.phar install
```

#Run tests

```
vendor/bin/phpunit tests/MarsRoverTest.php
```

or

```
php vendor/phpunit/phpunit/phpunit tests/MarsRoverTest.php
```

#Add test

Create two files: &lt;test_name&gt;.in (input format) and &lt;test_name&gt;.out (output format) in folder /tests/data.