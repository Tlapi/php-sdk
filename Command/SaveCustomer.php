<?php

/**
 * Description of SaveCustomer
 *
 * @author odehnal@iprogress.cz
 */
class SaveCustomer {

    /**
     * Jméno zákazníka
     *
     * @var string
     */
    private $name;

    /**
     * Zobrazované jméno
     *
     * @var string
     */
    private $nameDisplay;

    /**
     * IČ
     * 
     * @var string
     */
    private $comid;

    /**
     * DIČ
     *
     * @var string
     */
    private $vatid;

    /**
     * Plátce DPHP (ano/ne)
     *
     * @var string
     */
    private $vatPayer;

    /**
     * Email
     *
     * @var string
     */
    private $email;

    /**
     * Telefon
     *
     * @var string
     */
    private $phone;

    /**
     * Mobil
     *
     * @var string
     */
    private $cellphone;

    /**
     * WWW
     *
     * @var string
     */
    private $www;

    /**
     * Obvyklá splatnost [dny]
     *
     * @var string
     */
    private $usualMaturity;

    /**
     * Preferovaná metoda platby
     *
     * @var string
     */
    private $preferredPaymentMethod;

    /**
     * Jazyk faktury [cs, sk, en]
     *
     * @var string
     */
    private $invoiceLanguage;

    /**
     * Adresa
     *
     * @var Address
     */
    private $address;

    /**
     *  Poznámka
     *
     * @var string
     */
    private $note;

    /**
     * Číslo účtu 1
     *
     * @var string
     */
    private $accountNumber1;

    /**
     * Číslo účtu 2
     *
     * @var string
     */
    private $accountNumber2;

    /**
     * Číslo účtu 3
     *
     * @var string
     */
    private $accountNumber3;

    /**
     * Číslo účtu 4
     *
     * @var string
     */
    private $accountNumber4;

    function __construct(array $arrayData) {
        $this->name = $arrayData['name'];
        $this->nameDisplay = $arrayData['name_display'];
        $this->comid = $arrayData['comid'];
        $this->vatid = $arrayData['vatid'];
        $this->vatPayer = $arrayData['vat_payer'];
        $this->email = $arrayData['email'];
        $this->phone = $arrayData['phone'];
        $this->cellphone = $arrayData['cellphone'];
        $this->www = $arrayData['www'];
        $this->usualMaturity = $arrayData['usual_maturity'];
        $this->preferredPaymentMethod = $arrayData['preferred_payment_method'];
        $this->invoiceLanguage = $arrayData['invoice_language'];
        $this->address = $arrayData['address'];
        $this->note = $arrayData['note'];
        $this->accountNumber1 = $arrayData['account_number1'];
        $this->accountNumber2 = $arrayData['account_number2'];
        $this->accountNumber3 = $arrayData['account_number3'];
        $this->accountNumber4 = $arrayData['account_number4'];
    }

    public function getName() {
        return $this->name;
    }

    public function getNameDisplay() {
        return $this->nameDisplay;
    }

    public function getComid() {
        return $this->comid;
    }

    public function getVatid() {
        return $this->vatid;
    }

    public function getVatPayer() {
        return $this->vatPayer;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getCellphone() {
        return $this->cellphone;
    }

    public function getWww() {
        return $this->www;
    }

    public function getUsualMaturity() {
        return $this->usualMaturity;
    }

    public function getPreferredPaymentMethod() {
        return $this->preferredPaymentMethod;
    }

    public function getInvoiceLanguage() {
        return $this->invoiceLanguage;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getNote() {
        return $this->note;
    }

    public function getAccountNumber1() {
        return $this->accountNumber1;
    }

    public function getAccountNumber2() {
        return $this->accountNumber2;
    }

    public function getAccountNumber3() {
        return $this->accountNumber3;
    }

    public function getAccountNumber4() {
        return $this->accountNumber4;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setNameDisplay($nameDisplay) {
        $this->nameDisplay = $nameDisplay;
    }

    public function setComid($comid) {
        $this->comid = $comid;
    }

    public function setVatid($vatid) {
        $this->vatid = $vatid;
    }

    public function setVatPayer($vatPayer) {
        $this->vatPayer = $vatPayer;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setCellphone($cellphone) {
        $this->cellphone = $cellphone;
    }

    public function setWww($www) {
        $this->www = $www;
    }

    public function setUsualMaturity($usualMaturity) {
        $this->usualMaturity = $usualMaturity;
    }

    public function setPreferredPaymentMethod($preferredPaymentMethod) {
        $this->preferredPaymentMethod = $preferredPaymentMethod;
    }

    public function setInvoiceLanguage($invoiceLanguage) {
        $this->invoiceLanguage = $invoiceLanguage;
    }

    public function setAddress(Address $address) {
        $this->address = $address;
    }

    public function setNote($note) {
        $this->note = $note;
    }

    public function setAccountNumber1($accountNumber1) {
        $this->accountNumber1 = $accountNumber1;
    }

    public function setAccountNumber2($accountNumber2) {
        $this->accountNumber2 = $accountNumber2;
    }

    public function setAccountNumber3($accountNumber3) {
        $this->accountNumber3 = $accountNumber3;
    }

    public function setAccountNumber4($accountNumber4) {
        $this->accountNumber4 = $accountNumber4;
    }

    public function toArray() {
        return array('id' => $this->id,
            'name' => $this->name,
            'name_display' => $this->nameDisplay,
            'comid' => $this->comid,
            'vatid' => $this->vatid,
            'vat_payer' => $this->vatPayer,
            'email' => $this->email,
            'phone' => $this->phone,
            'cellphone' => $this->cellphone,
            'www' => $this->www,
            'usual_maturity' => $this->usualMaturity,
            'preferred_payment_method' => $this->preferredPaymentMethod,
            'invoice_language' => $this->invoiceLanguage,
            'address' => $this->address->toArray(),
            'note' => $this->note,
            'account_number1' => $this->accountNumber1,
            'account_number2' => $this->accountNumber2,
            'account_number3' => $this->accountNumber3,
            'account_number4' => $this->account_number4);
    }

}