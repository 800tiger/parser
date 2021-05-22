# CSV Object Parser

create a small PHP powered tool which can take a CSV file as an input, parse the
columns and rows into an object, sort the objects, ensure the Transaction Code is valid and then
return the objects in a table format.

## Features

- Seperate input .csv file's first line as column Object and each line as record object.
- Can create customize columns.
- Ensure trasanction code valid by creating reusable traits (src/Traits/ValidateTraits.php)
- Sort data by timestamp column.
- Export csv data as objects array or json format.
- No need to save anything to database.
- Ability to figure out debit or credit as record object's attribute.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install this parser.


```bash
composer require 800tiger/parser
```

If you don't use a framework such as Drupal, Laravel, Symfony, Yii etc., you may have to manually include Composer's autoloader file in your PHP script:

```bash
require_once __DIR__ . '/vendor/autoload.php';
```

## Usage

```php
use Parser\Parser\ParserHandleCsv;

$parser = new ParserHandleCsv($csv_file_path,'r+',true);
$headers = $parser->getHeader();
$rows = $parser->getRowsWithoutHeader();
$html_objects = $parser->exportAsTable('html','DESC');
$json_objects = $parser->exportAsTable('json','ASC');

```

## Example

Amazon EC2 display link [http://3.128.179.96/parser/](http://3.128.179.96/parser/).Use BankTransactions.csv supplied.

## Requirements

NumberFormatter::CURRENCY_ACCOUNTING (int)
Currency format for accounting, e.g., ($3.00) for negative currency amount instead of -$3.00. Available as of PHP 7.4.1 and ICU 53.

Uncomment line 68 in ParserEntity.php to use this feature if using PHP8.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)