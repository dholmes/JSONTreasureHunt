<?php
/**
 * Basically exists to remove last warnings from phpcs on find_in_json script
 *
 * If this were real, could be a good idea as it could be pulled out
 * into a real interface with just the local paramaters, etc
 *
 * @package IssueTwentyOne
 */

namespace IssueTwentyOne;

use League\CLImate\CLImate;

/**
 * Helper class for the find_in_json script
 *
 * @package IssueTwentyOne
 * @author Dan Holmes <daniel.holmes@gmail.com>
 */
class FindInJsonScriptHelper
{
    /**
     * cli wrapper object that handles working with CLI boundry
     *
     * @var CLImate
     */
    protected $climate;

    /**
     * Just a config string for the CLI's app description.
     *
     * @var string
     */
    protected $description = "Scan a file of JSON for a search string";

    /**
     * Constructor for service class
     *
     * @param CLImate $climate The cli wrapper class
     */
    public function __construct(CLImate $climate)
    {
        $this->climate = $climate;
    }

    /**
     * Abstracts our defined commandline parameters
     *
     * @return array
     */
    public function getParameterDefinition()
    {
        return [
            'file' => [
                'prefix' => 'f',
                'longPrefix' => 'file',
                'defaultValue' => null,
                'description' => 'Name of file or URL containing JSON (otherwise, pass though stdin)',
            ],
            'search' => [
                'prefix' => 's',
                'longPrefix' => 'search',
                'description' => 'Value to Search For',
                'required'=>true,
            ],
            'help' => [
                'prefix' => 'h',
                'longPrefix' => 'help',
                'description' => "Print Usage",
                'noValue' => true,
            ],
        ];
    }

    /**
     * Defines and handles the bulk of command line argument processing
     *
     * @return null
     * @throws Exception on missing required parameters
     */
    public function processCommandlineArguments()
    {
        $this->climate->description($this->description);
        $this->climate->arguments->add($this->getParameterDefinition());
        $this->climate->arguments->parse();
    }

    /**
     * wrapper around getting the contents of the requested file
     *
     * Obviously, this won't scale to 1G file sizes or anything,
     * But the biggest file is 60M and does it in about a second
     * and a half, so great for a desktop, run-on-occasion tool.
     *
     * @param string|null $filename path to file (null pulls STDIN)
     * @return string content
     */
    public function getInputFileContents($filename)
    {
        if (is_null($filename)) {
            $input = stream_get_contents(STDIN);
        } else {
            // tried doing php://stdin but not working on my mac
            $input = file_get_contents($filename);
        }
        return $input;
    }
}
