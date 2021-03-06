<?php

namespace IUcto;


require_once __DIR__ . '/Connector.php';
require_once __DIR__ . '/Parser.php';
require_once __DIR__ . '/ErrorHandler.php';

require_once __DIR__ . '/Dto/DocumentOverview.php';
require_once __DIR__ . '/Dto/DocumentItem.php';
require_once __DIR__ . '/Dto/DocumentDetail.php';
require_once __DIR__ . '/Dto/Department.php';
require_once __DIR__ . '/Dto/CustomerOverview.php';
require_once __DIR__ . '/Dto/Customer.php';
require_once __DIR__ . '/Dto/Contract.php';
require_once __DIR__ . '/Dto/BankAccount.php';
require_once __DIR__ . '/Dto/BankAccountOverview.php';
require_once __DIR__ . '/Dto/BankAccountList.php';
require_once __DIR__ . '/Dto/Address.php';

require_once __DIR__ . '/Command/SaveCustomer.php';
require_once __DIR__ . '/Command/SaveDocument.php';

require_once __DIR__ . '/Utils.php';


use IUcto\Command\SaveCustomer;
use IUcto\Command\SaveDocument;

use IUcto\Dto\BankAccountList;
use IUcto\Dto\Contract;
use IUcto\Dto\Customer;
use IUcto\Dto\CustomerOverview;
use IUcto\Dto\Department;
use IUcto\Dto\DocumentDetail;
use IUcto\Dto\DocumentOverview;


/**
 * @author IUcto
 */
class IUcto {

    private $connector;
    private $parser;
    private $errorHandler;

    public function __construct(Connector $connector, Parser $parser, ErrorHandler $errorHandler) {
        $this->connector = $connector;
        $this->parser = $parser;
        $this->errorHandler = $errorHandler;
    }

    private function handleRequest($address, $method, array $data = array()) {
        $response = $this->connector->request($address, $method, $data);
        $data = $this->parser->parse($response);
        if (isset($data['errors']) && is_array($data['errors'])) {
            $this->errorHandler->handleErrors($data['errors']);
        }
        return $data;
    }

    /**
     * Zjednodušený výpis všech dostupných dokladů.
     * 
     * @return DocumentOverview[] - 2-úrovňové pole. První úroveň tvoří klíč typ dokladu. 
     * @throws IUcto\ConnectionException
     */
    public function getAllDocuments() {
        $allData = $this->handleRequest('invoice_issued', Connector::GET);
        $allDocuments = array();
        foreach ($allData as $type => $typeData) {
            foreach ($typeData as $data) {
                $allDocuments[$type][] = new DocumentOverview($data);
            }
        }
        return $allDocuments;
    }

    /**
     * Vytvoří nový doklad, odpověd obsahuje detail dokladu.
     * 
     * @param SaveDocument $saveDocument
     * @return DocumentDetail
     * @throws IUcto\ConnectionException
     * @throws IUcto\ValidationException
     */
    public function createDocument(SaveDocument $saveDocument) {
        $allData = $this->handleRequest('invoice_issued', Connector::POST, $saveDocument->toArray());
        return new DocumentDetail($allData);
    }

    /**
     * Vrátí kompletní kolekci dat vybraného dokladu.
     * 
     * @param int $id
     * @return DocumentDetail
     * @throws IUcto\ConnectionException
     */
    public function getDocumentDetail($id) {
        $allData = $this->handleRequest('invoice_issued/' . $id, Connector::GET);
        return new DocumentDetail($allData);
    }

    /**
     * Aktulizuje předané parametry vybraného dokladu. Poviné ple jsou stejná jako při vkládání nového záznamu.
     * 
     * @param int $id
     * @param SaveDocument $saveDocument
     * @return mixed []
     * @throws IUcto\ConnectionException
     * @throws IUcto\ValidationException
     */
    public function updateDocument($id, SaveDocument $saveDocument) {
        $allData = $this->handleRequest('invoice_issued/' . $id, Connector::PUT, $saveDocument->toArray());
        return new DocumentDetail($allData);
    }

    /**
     * Pokusí se smazat vybraný dokladu. Pokud je doklad vázán na jiný záznam, vrátí chybu a doklad se nasmaže.
     * 
     * @param int $id
     * @return void
     * @throws IUcto\ConnectionException
     */
    public function deleteDocument($id) {
        $this->handleRequest('invoice_issued/' . $id, Connector::DELETE);
    }

    /**
     * Zjednodušený výpis všech dostupných zákazníků.
     * 
     * @return CustomerOverview []
     * @throws IUcto\ConnectionException
     */
    public function getCustomers() {
        $allData = $this->handleRequest('customer', Connector::GET);
        $allCustomers = array();
        foreach ($allData['customer'] as $customer) {
            $allCustomers[] = new CustomerOverview($customer);
        }
        return $allCustomers;
    }

    /**
     * Vytvoří nový doklad, odpověd obsahuje detail vytvořeného zákazníka.
     * 
     * @param SaveCustomer $saveCustomer
     * @return Customer
     * @throws IUcto\ConnectionException
     * @throws IUcto\ValidationException
     */
    public function createCustomer(SaveCustomer $saveCustomer) {
        $allData = $this->handleRequest('customer', Connector::POST, $saveCustomer->toArray());
        if (!$allData) {
            throw new \InvalidArgumentException("Can't parse the recieved data");
        }
        return new Customer($allData);
    }

    /**
     * Vrátí veškerá data k zákazníkovi.
     * 
     * @param int $id
     * @return Customer
     * @throws IUcto\ConnectionException
     */
    public function getCustomerDetail($id) {
        $allData = $this->handleRequest('customer/' . $id, Connector::GET);
        return new Customer($allData);
    }

    /**
     * Aktulizuje předané parametry vybraného zákazníka. Poviné ple jsou stejná jako při vkládání nového zákazníka.
     * 
     * @param int $id
     * @param SaveCustomer $saveCustomer
     * @return Customer
     * @throws IUcto\ConnectionException
     * @throws IUcto\ValidationException
     */
    public function updateCustomer($id, SaveCustomer $saveCustomer) {
        $allData = $this->handleRequest('customer/' . $id, Connector::PUT, $saveCustomer->toArray());
        return new Customer($allData);
    }

    /**
     * Pokusí se smazat vybraného zákazníka. Pokud je ovšem vázán na jiný záznam (faktury, platba, apod.), vrátí chybu a zákazník se nasmaže.
     * 
     * @param int $id
     * @return void
     * @throws IUcto\ConnectionException
     */
    public function deleteCustomer($id) {
        $this->handleRequest('customer/' . $id, Connector::DELETE);
    }

    /**
     * Seznam dostupných bankovních účtů.
     * 
     * @return BankAccountList
     * @throws IUcto\ConnectionException
     */
    public function getBankAccounts() {
        $allData = $this->handleRequest('bank_account', Connector::GET);
        return new BankAccountList($allData['bank_account']);
    }

    /**
     * Seznam dostupných měn pro použití v dokladech.Dostupnost se může měnit v závislosti na aktivaci rozšíření Účtování v cizích měnách.
     * 
     * @return string[]
     * @throws IUcto\ConnectionException
     */
    public function getCurrencies() {
        return $this->handleRequest('currency', Connector::GET);
    }

    /**
     * Seznam metod pro zaokrouhlování částek v dokladech.
     * 
     * @return string[]  klíč označení, hodnota popis
     * @throws IUcto\ConnectionException
     */
    public function getRoundingTypes() {
        return $this->handleRequest('rounding_type', Connector::GET);
    }

    /**
     * Seznam dostupných metod pro provedení platby používaných v dokladech.
     * 
     * @return string[]  klíč označení, hodnota popis
     * @throws IUcto\ConnectionException
     */
    public function getPaymentTypes() {
        return $this->handleRequest('payment_type', Connector::GET);
    }

    /**
     * Seznam kurzů DPH k danému datu.
     * 
     * @param string $date formát (YYYY-mm-dd)
     * @return int[]
     * @throws IUcto\ConnectionException
     */
    public function getVATRatesOn($date) {
        return $this->handleRequest('vat_rates?date=' . $date, Connector::GET);
    }

    /**
     * Odpověd obsahuje pole dostupných účetních položek v závislosti na parametru doctype. Formát odpovědi: {"id": "popis"}
     * 
     * @param string $doctype
     * @return string[] klíč označení, hodnota popis
     * @throws IUcto\ConnectionException
     */
    public function getAccountingEntryTypes($doctype = "FV") {
        return $this->handleRequest('accountentry_type?doctype=' . $doctype, Connector::GET);
    }

    /**
     * Odpověd obsahuje pole dostupných typů DPH v závislosti na parametru doctype. Formát odpovědi: {"id": "popis"}
     * 
     * @param string $doctype
     * @return string[] klíč označení, hodnota popis
     * @throws IUcto\ConnectionException
     */
    public function getVATs($doctype = "FV") {
        return $this->handleRequest('accountentry_type?doctype=' . $doctype, Connector::GET);
    }

    /**
     * Odpověd obsahuje pole dostupných účtů úč. osnovy pro použití v dokladech.{"id": "popis"}
     * 
     * @return string[] klíč označení, hodnota popis
     * @throws IUcto\ConnectionException
     */
    public function getChartAccounts() {
        return $this->handleRequest('chart_account', Connector::GET);
    }

    /**
     * Odpověd obsahuje pole dostupných Účty DPH pro použití v dokladech.{"id": "popis"}
     * 
     * @return mixed[] klíč (int) označení, hodnota popis
     * @throws IUcto\ConnectionException
     */
    public function getChartAccountVATs() {
        return $this->handleRequest('vat_chart', Connector::GET);
    }

    /**
     * Výpis dostupných středisek.
     *  
     * @return Department[]
     * @throws IUcto\ConnectionException
     */
    public function getDepartments() {
        $allData = $this->handleRequest('department', Connector::GET);
        $departments = array();
        foreach ($allData['department'] as $data) {
            $departments[] = new Department($data);
        }
        return $departments;
    }

    /**
     * Výpis dostupných zakázek.
     * 
     * @return Contract[]
     * @throws IUcto\ConnectionException
     */
    public function getContracts() {
        $allData = $this->handleRequest('contract', Connector::GET);
        $contracts = array();
        foreach ($allData['contract'] as $data) {
            $contracts[] = new Contract($data);
        }
        return $contracts;
    }

    /**
     * Seznam dostupných metod.
     * 
     * @return string[] klíč označení, hodnota popis
     * @throws IUcto\ConnectionException
     */
    public function getPreferedPaymentMethods() {
        return $this->handleRequest('preferred_payment_method', Connector::GET);
    }

    /**
     * Seznam dostupných států.
     * 
     * @return string[] klíč označení, hodnota popis
     * @throws IUcto\ConnectionException
     */
    public function getCountries() {
        return $this->handleRequest('country', Connector::GET);
    }

}
