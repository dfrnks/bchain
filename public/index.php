<?php

$GLOBALS["node"] = "127.0.0.1:8000";
$GLOBALS["node_principal"] = "127.0.0.1:8000";

require "../bootstrap.php";

//$app->run();

$wallet = new \Bchain\Wallet\Wallet('C9767FAADC56D62967689C35A95D02245B4155918475310C93A529E587BA7614', '03D524011FDAA3378F3A2FC84AE4FCD4ED2987144BCE038EAD1C25685ADF5B1BAA');
$wallet->createKeys();
//$sign = $wallet->sign("Lalalalala");
//
//echo $wallet->verify("Lalalalala", $sign);