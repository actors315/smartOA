<?php
namespace SmartPay;

require_once 'pingpp-php/init.php';

\Pingpp\Pingpp::setApiKey('sk_test_LaXTmDb9C4GOiPOirTWX18mP');

\Pingpp\Pingpp::setPrivateKeyPath(__DIR__ . '/rsa_private_key.pem');