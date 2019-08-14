# CSV Parse plugin for Craft CMS 3.x

Parse CSV file in template provide paginated results.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require leaplogic/csv-parse

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for CSV Parse.

## CSV Parse Overview

The plugin is mean to parse a CSV file provided via an Asset then provide results as an array. You have the ability to filter or search the CSV file. 

## Using CSV Parse
Make sure to provide an Asset input to add a CSV file to your CMS. The plugin will use this in the template to parse and provide an array of results.

### Grab CSV Header Row
```TWIG
{% set headers = craft.csvparse.headers(entry.csvInput.first()) %}
```
### Grab CSV Entries
In this example we are pulling a url param to grab which page of entries to return. 
```TWIG
{% set amount = 20 %}
{% set offset = craft.request.getParam('pg')|number_format * 20  %}
{% set entries = craft.csvparse.entries(entry.csv.first(), offset, amount) %}
```
### Returned Object 
```PHP
{
    'data' => Array,
    'meta' => [
        'total' => Number
    ]
}
```

### Example Output
```TWIG
{% macro table(items,headers) %}
    <table>
        <tr>
            {% for item in headers %}
                <th>{{ item }}</th>
            {% endfor %}
        </tr>
        {% for item in items %}
            <tr>
                {% for col in item %}
                    <td>{{ col }}</td>
                {% endfor %}
            </tr>
        {% endfor %}
    </table>
{% endmacro %}

{% import _self as self %}

{{ self.table(entries.data, headers) }}
```

### Example Pagination
```TWIG
{% set total = entries.meta.total %}
{% set prevPg = craft.request.getParam('pg')|number_format - 1 == 0 ? 1 : craft.request.getParam('pg')|number_format - 1 %}
{% set nextPg = craft.request.getParam('pg')|number_format + 1 >= (total / amount)|round ? (total / amount)|round : craft.request.getParam('pg')|number_format + 1  %}
{% set filterType = craft.request.getParam('q') ? '&q=' ~ craft.request.getParam('q')|url_encode : craft.request.getParam('f') ? '&f=' ~ craft.request.getParam('f')|url_encode : ''  %}
{% if total > amount %}
    <div class="pagination">
        <a href="?pg={{ prevPg }}{{ filterType }}">PREV</a>
        <span>{{ craft.request.getParam('pg') ? craft.request.getParam('pg') : 1  }} / {{ (total / amount)|round }}</span>
        <a href="?pg={{ craft.request.getParam('pg') ? nextPg : 2 }}{{ filterType }}">NEXT</a>
    </div>
{% endif %}
```
## CSV Parse Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [Leap Logic](https://leaplogic.net)
