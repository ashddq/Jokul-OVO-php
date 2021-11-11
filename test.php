<?php
$clientId = $_POST['clientId'];
$secretKey = $_POST['secretKey'];
$ovoid = $_POST['ovoid'];
$amount = $_POST['amount'];
date_default_timezone_set('UTC');
$env = $_POST['env'];

if ($env == 'Sandbox') {
    $getUrl = 'https://api-sandbox.doku.com'; //sandbox
  } else if ($env == 'Production'){
    $getUrl = 'https://api.doku.com'; //production
  } else {
    $getUrl = 'https://api.doku.com'; //production
  }
  
$path = '/ovo-emoney/v1/payment';
$url = $getUrl.$path;
$timestamp      = date('Y-m-d\TH:i:s\Z');
$waktu = date('Y-m-d-H-i-s');
$invoice = $_POST['invoice'];
$abc = $amount.$clientId.$invoice.$ovoid.$secretKey;
$checksum = hash('sha256', $abc);
$Body = array (
'client' =>
    array (
        'id' => $clientId,
    ),
'order' =>
    array (
        'invoice_number' => $invoice,
        'amount' => $amount,
    ),
'ovo_info' =>
    array (
        'ovo_id' => $ovoid,
    ),
'security' =>
    array (
        'check_sum' => $checksum,
    ),
);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($Body));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);
$hasil = json_decode($response, true);
$waktutransaksi7 = date('l, d F Y H:i:s', strtotime($timestamp.'+7 hours'));
$waktumundur = date('l, d F Y H:i:s', strtotime($timestamp.'+7 hours +70 seconds'));
$simulator = 'https://sandbox.doku.com/integration/simulator/ovo/inquiry';      
$sts = $hasil['ovo_payment']['status'];
$sisasaldo = $hasil['ovo_payment']['cash_balance'];
$namaovo = $hasil['ovo_info']['ovo_account_name'];
$failed = $hasil['error']['message'];

?>

<!DOCTYPE html>
<html>
  <head>
    <title>JOKUL OVO - @ashddq</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <style>
      html, body {
      display: flex;
      justify-content: center;
      font-family: Roboto, Arial, sans-serif;
      font-size: 15px;
      }
      form {
      border: 5px solid #f1f1f1;
      }
      input[type=text], input[type=password] {
      width: 100%;
      padding: 16px 8px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      box-sizing: border-box;
      }
      button {
      background-color: #9900ff;
      color: white;
      padding: 14px 0;
      margin: 10px 0;
      border: none;
      cursor: grabbing;
      width: 100%;
      }
      h1 {
      text-align:center;
      font-size:18;
      }
      button:hover {
      opacity: 0.8;
      }
      .formcontainer {
      text-align: left;
      margin: 24px 50px 12px;
      }
      .container {
      padding: 16px 0;
      text-align:left;
      }
      span.psw {
      float: right;
      padding-top: 0;
      padding-right: 15px;
      }
      @media screen and (max-width: 300px) {
      span.psw {
      display: block;
      float: none;
      }
    </style>
  </head>
  <body>
  <section class="result" id="result">
      <div class="container">
      <?php if ($sts == "SUCCESS"){ ?>
        <div class="row mb-2">
          <div class="col text-center">
          <h1>JOKUL OVO</h1>
            <h3>Invoice Number : <?= $invoice ?></h3>
            <h3>Ovo ID : <?= $ovoid ?></h3>
            <h3>Nama : <?= $namaovo ?></h3>
            <h3>Amount : <?= $amount ?></h3>
            <h3>Status : <?= $sts ?></h3>
            <h3>Sisa Saldo : <?= $sisasaldo ?></h3>
          </div>
        </div>
        </div>
        <?php }else if($sts == "FAILED"){?>
          <div class="row mb-2">
          <div class="col text-center">
          <h1>JOKUL OVO</h1>
            <h3>Waktu pembayaran anda habis, silahkan klik tombol coba lagi!</h3>
            <h3>Status : <?= $sts ?></h3>
          </div>
        </div>
        </div>
        <?php }else{ ?>
        <div class="row mb-2">
          <div class="col text-center">
          <h1>JOKUL OVO</h1>
            <h3>Status : <?= $failed ?></h3>
            <h3>Please enter the correct Client-ID or Secret-Key</h3>
          </div>
        </div>
        </div>
        <?php } ?>
      </div>
      <a href="index.html"><button type="submit">Coba Lagi</button></a>
      <div class="container" style="background-color: #eee">
        <label> <center><a href="https://www.instagram.com/ashddq">@ashddq</a></center>
    </section>
  </body>
</html>
