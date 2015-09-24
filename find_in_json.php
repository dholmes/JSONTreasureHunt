#!/usr/bin/env php
<?php
/**
 * Command-line script to locate strings within a json object
 *
 * Usage: ./find_in_json.php [-f file, --file file] [-h, --help] -s search
 *   or: cat example.json | ./find_in_json.php -s search
 *
 *  Required Arguments:
 *  -s search, --search search
 *  Value to Search For
 *
 *  Optional Arguments:
 *  -f file, --file file
 *  Name of file or URL containing JSON (otherwise, pass though stdin)
 *
 *  -h, --help
 *  Print Usage
 *
 * @package IssueTwentyOne
 * @author Dan Holmes <daniel.holmes@gmail.com>
 * @version $id$
 */

require 'vendor/autoload.php';

use IssueTwentyOne\FindInJsonScriptHelper;

$climate = new League\CLImate\CLImate;
$helper = new FindInJsonScriptHelper($climate);

try {
    $helper->processCommandlineArguments();
} catch (Exception $e) {
    $dieWith = 0;
    if (!$climate->arguments->get('help')) {
        $climate->to('error')->red($e->getMessage());
        $dieWith = 1;
    }
    $climate->usage();
    die($dieWith);
}

$search = $climate->arguments->get('search');
$input = $helper->getInputFileContents($climate->arguments->get('file'));

if ($search) {
    if (empty($input)) {
        $climate->to('error')->red("To use without -f, be sure to pipe json into this script");
        die(1);
    }

    $inputAsArray = (array) json_decode($input, true);
    $finder = new IssueTwentyOne\ArrayValueFinder($inputAsArray);

    $results = $finder->findPrettyPath($search);
    foreach ($results as $line) {
        $climate->out($line);
    }
}