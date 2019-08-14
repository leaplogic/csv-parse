<?php
/**
 * CSV Parse plugin for Craft CMS 3.x
 *
 * Parse CSV file in template provide via paginated results.
 *
 * @link      https://leaplogic.net
 * @copyright Copyright (c) 2019 Leap Logic
 */

namespace leaplogic\csvparse\twigextensions;

use leaplogic\csvparse\CsvParse;

use Craft;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Leap Logic
 * @package   CsvParse
 * @since     1.0.0
 */
class CsvParseTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'CsvParse';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'headers' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('headers', [$this, 'headers']),
        ];
    }


    /**
     * Return the headers of the CSV file
     *
     * @param null $assset
     *
     * @return Array
     */

    public function headers($asset)
    {
        return CsvParse::$plugin->parse->headers($asset);
    }
}
