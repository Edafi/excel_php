<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\WXls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {
function readCell($columnAddress, $rows, $worksheetName = '') {
    if (($columnAddress == 'A' || $columnAddress=='N')  ) {
        return true;
        }
    return false;
    }
}

function read(){
$spreadsheet = new Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();
$spreadsheet->getDefaultStyle()
->getFont()
->setName('Arial')
->setSize(14);


$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
$reader->setReadFilter( new MyReadFilter());
$spreadsheet = $reader->load('simple.xls');
$sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
$data = $sheet->toArray();  
for($i=0;$i<count($data);$i++)
    {
        $data[$i] = (string)$data[$i];
    }
print_r($data);

// die(print_r($data, true)); 
// $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
// // $reader->setReadDataOnly(true);
// $reader->setReadFilter( new MyReadFilter() );
// $spreadsheet = $reader->load('simple.xls');
$worksheet->fromArray([$data],NULL,'A1');

///$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
//$writer->save('result.xls');
}
?>