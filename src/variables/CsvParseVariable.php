<?php
/**
 * CSV Parse plugin for Craft CMS 3.x
 *
 * Parse CSV file in template provide via paginated results.
 *
 * @link      https://leaplogic.net
 * @copyright Copyright (c) 2019 Leap Logic
 */

namespace leaplogic\csvparse\variables;

use leaplogic\csvparse\CsvParse;

use Craft;

/**
 * CSV Parse Variable
 *
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Leap Logic
 * @package   CsvParse
 * @since     1.0.0
 */
class CsvParseVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Provide CSV file results
     * 
     * @return Array
     */
    public function entries($asset, $offset = 0, $amount = 10)
    {
        return CsvParse::$plugin->parse->entries($asset, $offset, $amount);
    }

    /**
     * Provide the Header row of the CSV file
     * 
     * @return Array
     */
    public function headers($asset)
    {
        return CsvParse::$plugin->parse->headers($asset);
    }

    /**
     * Search CSV results by query with pagination
     * 
     * @return Array
     */
    public function search($asset, $query='', $offset = 0, $amount = 10)
    {
        return CsvParse::$plugin->parse->search($asset, urldecode ($query), $offset, $amount);
    }

    /**
     * Filter CSV results by query with pagination
     * 
     * @return Array
     */
    public function filter($asset, $query='', $offset = 0, $amount = 10)
    {
        return CsvParse::$plugin->parse->filter($asset, $query, $offset, $amount);
    }
}
