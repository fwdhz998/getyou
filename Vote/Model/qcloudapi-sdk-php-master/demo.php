<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once './src/QcloudApi/QcloudApi.php';

$config = array('SecretId'       => 'AKIDGK8gy5cZv1rSouwLo34q6D5h3A1diHH1',
                'SecretKey'      => 'g8LJ4dx2sTq44qNfiW6RXO1QaVBcppa5',
                'RequestMethod'  => 'POST',
                'DefaultRegion'  => 'gz');

$wenzhi = QcloudApi::load(QcloudApi::MODULE_WENZHI, $config);
$package = array("content"=>"天气真好");


$a = $wenzhi->TextClassify($package);
// $a = $cvm->generateUrl('DescribeInstances', $package);

if ($a === false) {
    $error = $wenzhi->getError();
    echo "Error code:" . $error->getCode() . ".\n";
    echo "message:" . $error->getMessage() . ".\n";
    echo "ext:" . var_export($error->getExt(), true) . ".\n";
} 
//else {
    //var_dump($a);
//}

//echo "\nRequest :" . $wenzhi->getLastRequest();
echo "\nResponse :" . $wenzhi->getLastResponse();
//echo "\n";
