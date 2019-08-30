<?php
/**
 * CSV Parse plugin for Craft CMS 3.x
 *
 * Parse CSV file in template provide via paginated results.
 *
 * @link      https://leaplogic.net
 * @copyright Copyright (c) 2019 Leap Logic
 */

namespace leaplogic\csvparse\services;

use leaplogic\csvparse\CsvParse;

use Yii;
use Craft;
use craft\base\Component;

/**
 * Parse Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Leap Logic
 * @package   CsvParse
 * @since     1.0.0
 */
class Parse extends Component
{
    // Public Methods
    // =========================================================================

    /**
    * Return csv file rows by offset and amount
    */
    public function entries($asset, $offset, $amount) {
        // first remove csv header row
        $contents = array_slice($this->_parse($asset), 1);
        $total = count($contents);
        //\CVarDumper::dump($contents, 5, true);exit;
        return is_null($amount) ? [ 'data' => array_slice($contents, intval($offset)), 'meta' => [ 'total' => $total ] ] : [ 'data' => array_slice($contents, intval($offset), $amount), 'meta' => [ 'total' => $total ] ];
    }

    /**
     * Get the headers of the csv file
     */
    public function headers($asset)
    {
        return $this->_parse($asset)[0];
    }

    /**
     * Return array of csv file rows that have query
     * 
     */
    public function search($asset, $query, $offset, $amount)
    {
        // first remove csv header row
        $contents = array_slice($this->_parse($asset), 1);
        $contents = array_filter($contents, function( $row ) use ( &$query ) {
            $pos = strpos(strtolower(implode(' ', $row)), strtolower($query));
            if(!($pos === false)) {
                return true;
            }
        });
        $total = count($contents);
        return is_null($amount) ? [ 'data' => array_slice($contents, intval($offset)), 'meta' => [ 'total' => $total ] ] : [ 'data' => array_slice($contents, intval($offset), $amount), 'meta' => [ 'total' => $total ] ];
    }

    public function filter($asset, $query, $offset, $amount)
    {
        $contents = array_slice($this->_parse($asset), 1);
        //\CVarDumper::dump($query, 5, true);exit;
        $regex = "/^\A[$query]/i";
        $contents = array_filter($contents, function($item) use ($regex) {
            preg_match($regex, $item[0], $matches);
            return count($matches) > 0;
        });
        $total = count($contents);
        return is_null($amount) ? [ 'data' => array_slice($contents, intval($offset)), 'meta' => [ 'total' => $total ] ] : [ 'data' => array_slice($contents, intval($offset), $amount), 'meta' => [ 'total' => $total ] ];
    }


    private function _parse($asset)
    {
        $volumePath = $asset->getVolume()->settings['path'];
        $folderPath = $asset->getFolder()->path;
        $folder = $asset->getFolder(); 
        $file = Yii::getAlias($volumePath) . $folderPath .'/'. $asset->filename;
        // https://craftcms.stackexchange.com/a/10817/331

        if (($handle = fopen($file, "r")) !== FALSE) {
            # Set the parent multidimensional array key to 0.
            $nn = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                # Count the total keys in the row.
                $c = count($data);
                # Populate the multidimensional array.
                for ($x=0;$x<$c;$x++)
                {
                    $csvarray[$nn][$x] = $data[$x];
                }
                $nn++;
            }
            # Close the File.
            fclose($handle);
        }

        return $csvarray;
    }
}
