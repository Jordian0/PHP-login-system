<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
</head>
<body>
<center>
    <h2>MORPHO RD TEST_PAGE</h2>
</center>
<div id="FingerPrint" style="width: 50%; height: 100%; float: left;">
    <button type="button">Capture</button>
    <p><?php echo Capture() ?></p>
    <br/><br/>

</div>

<?php
function Capture() {
    $url = "http://127.0.0.1:11100/capture";
    $PIDOPTS = "<PidOptions ver=\"1.0\">'.'<Opts fCount=\"1\" fType=\"0\" iCount=\"\" iType=\"\" pCount=\"\" pType=\"\" format=\"0\" pidVer=\"2.0\" timeout=\"10000\" otp=\"\" wadh=\"\" posh=\"\"/>".'</PidOptions>';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'CAPTURE');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: text/xml',
        'Accept: text/xml'
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $PIDOPTS);
    $response = curl_exec($ch);
    curl_close($ch);
//    echo "<script>alert('$response');</script>";
    echo $response;
}

?>

</body>
</html>
