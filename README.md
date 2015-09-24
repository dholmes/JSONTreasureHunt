# Dan's "ISSUE 21" challenge

# TLDR;
Given a JSON document, output they keypath to locate a search value in the form of

foo -> bar -> baz

## Purpose
It's my first week here so for me it wasn't as much about showing off, but
practicing my new team's coding standards & writing all that phpdoc.
I also wanted to make sure my phpcs, phpunit, composer, etc was all installed
and ready to go.  Best of all, it gave me an excuse to play with 
something new from thephpleague (CLImate).

## Does it Scale?
Does it read everything into a variable? You betcha.  Will this scale to 200G? Nope.
But it covers the challenge requirements and gave me a chance to have a well tested core,
with a number of flexible interfaces.

## Setup
```bash
composer install
```

## running tests
```bash
phpunit tests/ArrayValueFinderTest.php
```

## running script
```bash
chmod u+x ./find_in_json.php
cat examples/testb.json | ./find_in_json.php -s 'dailyprogrammer'

// or

./find_in_json.php -s 'dailyprogrammer' -f examples/testb.json

// get help
./find_in_json.php
./find_in_json.php -h
```

## running phpcs
```bash
 phpcs --standard=nerdery --ignore=vendor ./
```
