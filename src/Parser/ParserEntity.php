<?php
namespace Parser\Parser;

use Parser\Traits\ValidateTraits;

class ParserEntity {

        public $date;

        public $transactioncode;

        public $customernumber;

        public $reference;

        public $amount;

        public $validate;

        public $debit;

        public function __construct(array $transactions){
            
            $this->setDate($transactions['Date']);
            $this->setTransactioncode($transactions['TransactionNumber']);
            $this->setCustomerNumber($transactions['CustomerNumber']);
            $this->setReference($transactions['Reference']);
            $this->setAmount($transactions['Amount']);
        }

        public function setValidate($validate)
        {
            $this->validate = $validate;
        }

        //set date format as "23/09/2017 9:34 AM"
        public function setDate($date)
        {
            $date_format = \DateTime::createFromFormat('Y-m-d g:i A', $date);
            $this->date = $date_format->format('d/m/Y g:i A');
        }

        //use traits to validate Code check 
        public function setTransactioncode($transactioncode)
        {
            $action = (ValidateTraits::verifyKey($transactioncode)) ? 'yes' : 'no';
            
            $this->setValidate($action);

            $this->transactioncode = $transactioncode;
        }

        public function setCustomerNumber($customernumber)
        {
            $this->customernumber = $customernumber;
        }

        public function setReference($reference)
        {
            $this->reference = $reference;
        }

        //convert amount as -$5.84 or $5.84
        //in PHP 7.4 + can use  NumberFormatter::CURRENCY_ACCOUNTING (int) to instead
        //set debit or credit flag in object
        public function setAmount($amount)
        {
            //$fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY_ACCOUNTING);
            $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
            $this->amount = $fmt->formatCurrency(number_format($amount/100, 2 ,'.',''),'USD');
            $check_debit = ($this->amount > 0) ? 'credit' : 'debit';
            $this->setDebit($check_debit);
        }

        public function setDebit($debit){
            $this->debit = $debit;
        }

        public function getDate()
        {
            return $this->date;
        }

        public function getTransactioncode()
        {
            return $this->transactioncode;
        }

        public function getCustomerNumber()
        {
            return $this->customernumber;
        }

        public function getReference()
        {
            return $this->reference;
        }

        public function getAmount()
        {
            return $this->amount;
        }
}
