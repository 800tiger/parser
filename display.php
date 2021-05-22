<?php

use Parser\Parser\ParserHandleCsv;
require_once realpath("vendor/autoload.php");

if ( isset($_POST["submit"]) && isset($_FILES["csv_task"])){
    if ($_FILES["csv_task"]["error"] > 0) {
        echo "Return Code: " . $_FILES["csv_task"]["error"] . "<br />";
    }
    else {
        if (file_exists("./src/upload/" . $_FILES["csv_task"]["name"])) {
            $storagename = "./src/upload/".$_FILES["csv_task"]["name"];
        }
        else {
            $storagename = $_FILES["csv_task"]["name"];
            move_uploaded_file($_FILES["csv_task"]["tmp_name"], "./src/upload/" . $storagename);
            $storagename = "./src/upload/".$storagename;
        }
    }
 }

    $reader = new ParserHandleCsv($storagename,'r+',true);
    $headers = $reader->getHeader();
    $rows = $reader->getRowsWithoutHeader();
    $transactions = $reader->exportAsTable('html','DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV TABLE</title>
    <style>
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }
        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }
        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }
        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }
    </style>
</head>
<body>
    <div class="table">
        <table class="styled-table">
        <tr>
        <?php foreach($headers->headers as $head):?>
            <th><?php echo $head?></th>
        <?php endforeach; ?>
            <th><?php echo $headers->validate?></th>
        </tr>
        <?php foreach($transactions as $ts):?>
        <tr>
            <td><?php echo $ts->date;?></td>
            <td><?php echo $ts->transactioncode;?></td>
            <td><?php echo $ts->customernumber;?></td>
            <td><?php echo $ts->reference;?></td>
            <td><?php echo $ts->amount;?></td>
            <td><?php echo $ts->validate;?></td>
        </tr>
        <?php endforeach; ?>
        </table>
    </div>
</body>
</html>

    



