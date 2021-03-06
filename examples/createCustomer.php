<?php
date_default_timezone_set("Europe/Prague");

require_once __DIR__ . '/../src/IUctoFactory.php';

$iUcto = IUcto\IUctoFactory::create('62b905ecb3e0ec6e760f20aacc59f15c'); // přidejte druhý parametr "http://gsmobile-novydesign.dev2.datesoft.cz/api" pro volání testovací verze

$data = array(
    "name" => "Jan Novák",
    "name_display" => null,
    "comid" => "1234568010",
    "vatid" => null,
    "vat_payer" => false,
    "email" =>
    "novak.jan@iucto.cz",
    "phone" => null,
    "cellphone" => null,
    "www" => null,
    "usual_maturity" => 30,
    "preferred_payment_method" => "transfer",
    "invoice_language" => "cs",
    "address" => array(
        "street" => "Stodolní 123",
        "city" => "Ostrava",
        "postalcode" => "385 02",
        "country" => "CZ"),
    "note" => null,
    "account_number1" => "1230123/0100",
    "account_number2" => null,
    "account_number3" => null,
    "account_number4" => null
);

try {
    $customer = $iUcto->createCustomer(new IUcto\Command\SaveCustomer($data));
    echo '<pre>';
    print_r($customer);
    echo '</pre>';
} catch (IUcto\ValidationException $e) {
    echo '<p>Validation errors:</p>';
    var_dump($e->getErrors());
} catch (IUcto\ConnectionException $e) {
    // network layer problem
    // HTTP response code
    echo $e->getCode();
    // Message from the server
    echo $e->getMessage();
}