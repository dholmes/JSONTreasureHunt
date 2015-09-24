<?php
/**
 * Array Value Filter class file
 *
 * @package IssueTwentyOne
 *
 */

namespace IssueTwentyOne;

use \RecursiveIteratorIterator;
use \RecursiveArrayIterator;

/**
 * Finds one or more key paths in an array of arrays
 *
 * @package IssueTwentyOne
 * @author Dan Holmes <daniel.holmes@gmail.com>
 * @version $id$
 */
class ArrayValueFinder
{
    /**
     * haystack of data to search added at construct time
     *
     * @var array
     */
    protected $haystack = array();

    /**
     * Constructor
     *
     * @param array $haystack of data to search
     */
    public function __construct(array $haystack)
    {
        $this->haystack = $haystack;
    }

    /**
     * Locate a list of lists of matching key names
     *
     * @param mixed $needle value to search for
     * @return array list of arrays containing keynames to values contained in needle
     */
    public function find($needle)
    {
        $iterator = $this->createIteratorForHaystack();
        $results = array();
        foreach ($iterator as $key => $value) {
            if ($value === $needle) {
                $keyList = array($key);
                for ($i = $iterator->getDepth() - 1; $i >= 0; $i--) {
                    array_unshift($keyList, $iterator->getSubIterator($i)->key());
                }
                $results[] = $keyList;
            }
        }
        return $results;
    }

    /**
     * Locate a list of strings representing the path to the matching values
     *
     * @param mixed $needle value to search for
     * @param string $seperator string to put between the matches
     * @return array
     */
    public function findPrettyPath($needle, $seperator = ' -> ')
    {
        $results = $this->find($needle);
        $results = array_map(function ($keyList) use ($seperator) {
            return join($seperator, $keyList);
        }, $results);
        return $results;
    }

    /**
     * Internal method to abstract the iterator-iterator/array iterator for overloading
     *
     * @return RecursiveIteratorIterator
     */
    protected function createIteratorForHaystack()
    {
        return new RecursiveIteratorIterator(
            new RecursiveArrayIterator($this->haystack),
            RecursiveIteratorIterator::CHILD_FIRST
        );
    }
}