<?php

namespace Parser\Parser;

use SplFileObject;
use NumberFormatter;
use Parser\Parser\ParserEntity;
use Parser\Parser\ParserHeader;

class ParserHandleCsv {

    /**
     * @var boolean $header
     * Boolean check csv file has headers or not default header is true
     */
    protected $header = true;

    /**
     * @var string $delimiter
     * String csv file delimiter, default value ','
     */
    protected $delimiter = ',';
    
    /**
     * @var string $enclosure
     * String csv file enclosure, default value '"'
     */
    protected $enclosure = '"';

    /**
     * @var array $valide_string
     * Array csv file validate strings
     */
    protected $valide_string = array( ',', ';', "\t", '|', ':','"');

    /**
     * @var array firstlineheader
     * Array firstline header
    */
    protected $firstlineheader = [];

    /**
     * @var array transactions
     * Array transactions seperate header
    */
    protected $transactions = [];

    /**
     * @var int rowline
     * Int rowline which is header line number default 0
    */
    protected $rowline = 0;

    /**
     * @var int totallines
     * Int totallines file total lines
    */
    protected $totallines = 0;

    /**
     * @var splFileObject output
     * Object output
    */
    protected $output;

    /**
     * @var int total columns
     * Int total columns in file
    */
    protected $totalcolumns;
    /**
     * @var string new_column
     * Object output default is Valid Transaction;
    */
    protected $new_column = 'Valid Transaction';

    /**
     * @var string new_column
     * Object output default is Valid Transaction;
    */
    protected $sort_by_time = 'DESC';

    /**
     * Parser Constructor.
     *
     * @param string $path
     * @param string $mode
     * @return sqlFileObject
     */
    public function __construct(string $path, string $mode ='r+', bool $header)
    {

        if($this->checkFileAvaliable($path)){
            $this->path = $path;
            $this->mode = $mode;
            $this->header = $header;
            $this->output = $this->handelCsvFile();
        }
    }

    public function setDelimiter(string $delimiter):void
    {
        if (in_array($delimiter, $this->valide_string) !== true) {
            throw new \Exception('Delimiter is not valid.');
        }
        $this->delimiter = $delimiter;
    } 
    
    public function setEnclosure(string $enclosure):void
    {
        if (in_array($enclosure, $this->valide_string) !== true) {
            throw new \Exception('Enclosure is not valid.');
        }
        $this->enclosure = $enclosure;
    }

    //check file available; 
    public function checkFileAvaliable($path){

        try{
            if (file_exists($path) !== true) {
                throw new \Exception("File {$path} does not exist.");
            }
            if (pathinfo($path)['extension'] != 'csv') {
                throw new \Exception("File is not a valid csv file.");
            }
        }catch(\Exception $e){
            return false;
        }
        return true;
    }

    //return array using SPLFILE Object for reading CSV file
    public function handelCsvFile() : SplFileObject
    {
        $output = new SplFileObject($this->path);
        $output->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD); 
        $this->totallines = iterator_count($output);
        
        return $output;
    }

    //create header object and add new column 'Validate Transaction'
    public function getHeader(){

        if($this->header === true){
           
            $this->output->rewind();

            $this->firstlineheader = str_replace("\xEF\xBB\xBF", '', $this->output->current());

            $this->totalcolumns = count($this->firstlineheader);

            $header_object = new ParserHeader($this->firstlineheader,$this->new_column);
        }
        return $header_object;
    }
    

    //create transaction object without header line by reading CSV file line by line retrun ArrayObject
    //validate transaction object attribute on ParserEntity class.
    public function getRowsWithoutHeader() 
    {   
        if($this->rowline == 0 && !$this->output->eof()){
            $this->output->next();
            $this->rowline++;
            return $this->getRowsWithoutHeader();
        }
              
        if(isset($this->transactions) && $this->rowline < $this->totallines-1){

            $line = $this->output->current();
            $line = array_slice(array_pad($line, $this->totalcolumns, null), 0, $this->totalcolumns);
            $line = array_combine($this->firstlineheader, $line);
            $transaction_object = new ParserEntity($line);
            array_push($this->transactions,$transaction_object);
            $this->output->next();
            $this->rowline++;
            return $this->getRowsWithoutHeader();
        } 
        
        return $this->transactions;
    }
    
    //sort by time function, need to use at view level.
    public function sortBytime($desc) : array
    {  
        $this->sort_by_time = $desc;
        usort($this->transactions, function($a, $b) {
            $ad = \DateTime::createFromFormat('d/m/Y g:i A', $a->date)->format('Y-m-d g:i A');
            $bd = \DateTime::createFromFormat('d/m/Y g:i A', $b->date)->format('Y-m-d g:i A');
            if ($ad == $bd) {
              return 0;
            }
            if($this->sort_by_time == 'DESC'){
                return $ad > $bd ? -1 : 1;
            }else{
                return $ad < $bd ? -1 : 1;
            }
          });
        return $this->transactions;
    }

    // export csv as array for html, json for Typescript or api.
    public function exportAsTable($exporttype,$orderBytime){

        $this->getRowsWithoutHeader();
        $this->sortBytime($orderBytime);

        if($exporttype == 'html'){
            return $this->transactions;
        }

        if($exporttype == 'json'){
            return json_encode($this->transactions);
        }
        
    }

    /**
     * Parser Destructor.
     */
    public function __destruct()
    {
        unset($this->output);
    }
}

