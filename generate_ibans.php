<?php
function generateIBAN($countryCode, $bankCode, $accountNumber) {
    $countryCodeNumeric = convertToNumeric($countryCode);
    $checksum = '00';
    $tempIBAN = $bankCode . $accountNumber . $countryCodeNumeric . $checksum;
    $checksum = 98 - bcmod($tempIBAN, 97);
    return $countryCode . str_pad($checksum, 2, '0', STR_PAD_LEFT) . $bankCode . $accountNumber;
}

function convertToNumeric($string) {
    $numericString = '';
    foreach (str_split($string) as $char) {
        if (ctype_alpha($char)) {
            $numericString .= (ord($char) - 55);
        } else {
            $numericString .= $char;
        }
    }
    return $numericString;
}

function generateRandomIBANs($countryCode, $bankCodeLength, $accountNumberLength, $quantity) {
    $ibans = [];
    for ($i = 0; $i < $quantity; $i++) {
        $bankCode = str_pad(random_int(0, pow(10, $bankCodeLength) - 1), $bankCodeLength, '0', STR_PAD_LEFT);
        $accountNumber = str_pad(random_int(0, pow(10, $accountNumberLength) - 1), $accountNumberLength, '0', STR_PAD_LEFT);
        $ibans[] = generateIBAN($countryCode, $bankCode, $accountNumber);
    }
    return $ibans;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $countryCode = $_POST['countryCode'];
    $bankCodeLength = intval($_POST['bankCodeLength']);
    $accountNumberLength = intval($_POST['accountNumberLength']);
    $quantity = intval($_POST['quantity']);

    $ibans = generateRandomIBANs($countryCode, $bankCodeLength, $accountNumberLength, $quantity);
    echo json_encode($ibans);
}
?>
