<?php
/**
 * Test for Array Value Filter class file
 *
 * @package IssueTwentyOne
 *
 */

namespace IssueTwentyOne\Tests;

use IssueTwentyOne;

/**
 * Test class for ArrayValueFinder
 *
 * @package IssueTwentyOne\Tests
 * @author Dan Holmes <dholmes@nerdery.com>
 * @version $id$
 */
class ArrayValueFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * two line, easy json string
     *
     * @var string
     */
    protected $jsonExampleSimple = <<<EOS
[{"name": "Dan Holmes", "label": "dailyprogrammer"},
{"name": "Kosh Naranek", "job": "dailyprogrammer"}]
EOS;

    /**
     * multi-line json string, still structured
     *
     * @var string
     */
    protected $jsonExampleA = <<<EOS
{"name": "William Shakespeare", "wife": {"birthYear": 1555, "deathYear":
"Fun fact, she's a vampire", "name": "Anne Hathaway", "dead": false},
"favoriteWebsites": ["dailysonneter", "dailyprogrammer",
"vine (he's way into 6-second cat videos)"], "dead": true, "birthYear": 1564,
"facebookProfile": null, "selectedWorks": [{"written": 1606, "name":
"The Tragedy of Macbeth", "isItAwesome": true}, {"written": 1608, "name":
"Coriolanus", "isItAwesome": "It's alright, but kinda fascist-y"}], "deathYear":
 1616}
EOS;

    /**
     * multi-line json string, kind of crazy
     *
     * @var string
     */
    protected $jsonExampleB = <<<EOS
{"dlpgcack": false, "indwqahe": null, "caki": {"vvczskh": null, "tczqyzn":
false, "qymizftua": "jfx", "cyd": {"qembsejm": [null, "dailyprogrammer", null],
"qtcgujuki": 79, "ptlwe": "lrvogzcpw", "jivdwnqi": null, "nzjlfax": "xaiuf",
"cqajfbn": true}, "kbttv": "dapsvkdnxm", "gcfv": 43.25503357696589}, "cfqnknrm":
null, "dtqx": "psuyc", "zkhreog": [null, {"txrhgu": false, "qkhe": false,
"oqlzgmtmx": "xndcy", "khuwjmktox": 48, "yoe": true, "xode": "hzxfgvw",
"cgsciipn": 20.075297532268902}, "hducqtvon", false, [null, 76.8463226047357,
"qctvnvo", null], [null, {"nlp": false, "xebvtnvwbb": null, "uhfikxc": null,
"eekejwjbe": false, "jmrkaqky": null, "oeyystp": false}, [null, 10, "nyzfhaps",
71, null], 40, null, 13.737832677566875], [true, 80, 20, {"weynlgnfro":
40.25989193717965, "ggsirrt": 17, "ztvbcpsba": 12, "mljfh": false, "lihndukg":
"bzebyljg", "pllpche": null}, null, [true, false, 52.532666161803895, "mkmqrhg",
 "kgdqstfn", null, "szse"], null, {"qkhfufrgac": "vpmiicarn", "hguztz":
 "ocbmzpzon", "wprnlua": null}], {"drnj": [null, false], "jkjzvjuiw": false,
 "oupsmgjd": false, "kcwjy": null}]}
EOS;

    /**
     * Test the haystack as array method
     *
     * @param array $input array of incoming data to search
     * @param mixed $search value to search for
     * @param array $expected result
     * @return null
     *
     * @dataProvider haystackDataProvider
     */
    public function testHaystack($input, $search, $expected)
    {
        $finder = $this->createFinderFromJson($input);
        $results = $finder->find($search);

        $this->assertEquals($expected, $results);
    }
    /**
     * Test the Haystack as string method
     *
     * @param array $input array of incoming data to search
     * @param mixed $search value to search for
     * @param array $expected result
     * @return null
     *
     * @dataProvider haystackPrettyDataProvider
     */
    public function testHaystackPretty($input, $search, $expected)
    {
        $finder = $this->createFinderFromJson($input);
        $results = $finder->findPrettyPath($search);

        $this->assertEquals($expected, $results);
    }

    /**
     * Test the haystack handling multiple hits
     *
     * @return null
     */
    public function testHandlesMultiples()
    {
        $finder = $this->createFinderFromJson($this->jsonExampleB);
        $results = $finder->findPrettyPath(null);
        $this->assertEquals(23, count($results), "There are 23 nulls in ExampleB");
    }

    /**
     * Helper function to get an array value finder with decoded version of json
     *
     * @param array $input array of incoming data to search
     * @return IssueTwentyOne\ArrayValueFinder
     */
    protected function createFinderFromJson($input)
    {
        return new IssueTwentyOne\ArrayValueFinder(
            (array) json_decode($input, true)
        );
    }

    /**
     * Unit Testing Data Provider for "as Array" testing
     *
     * @return array of arrays of Input, Search and Expected
     */
    public function haystackDataProvider()
    {
        return array(
            array(
                $this->jsonExampleSimple,
                'dailyprogrammer',
                array(
                    array(0, 'label'),
                    array(1, 'job'),
                ),
            ),
            array(
                $this->jsonExampleA,
                'dailyprogrammer',
                array(array('favoriteWebsites', 1)),
            ),
            array(
                $this->jsonExampleB,
                'dailyprogrammer',
                array(array('caki', 'cyd', 'qembsejm', 1)),
            ),
        );
    }

    /**
     * Unit Testing Data Provider for "as Strings" testing
     *
     * @return array of arrays of Input, Search and Expected
     */
    public function haystackPrettyDataProvider()
    {
        return array(
            array(
                $this->jsonExampleSimple,
                'dailyprogrammer',
                array(
                    '0 -> label',
                    '1 -> job',
                ),
            ),
            array(
                $this->jsonExampleA,
                'dailyprogrammer',
                array('favoriteWebsites -> 1'),
            ),
            array(
                $this->jsonExampleB,
                'dailyprogrammer',
                array('caki -> cyd -> qembsejm -> 1'),
            ),
        );
    }
}