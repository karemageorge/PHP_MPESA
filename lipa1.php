<?php
// generating ACCESS_TOKEN

  $consumerKey = 'Fu4rCbCus594MY0fDtp8sQrnSeavlbmv'; //Fill with your app Consumer Key
  $consumerSecret = 'zX7iS4uC1DHREdqd'; // Fill with your app Secret
  $headers = ['Content-Type:application/json; charset=utf8'];
  $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
  $curl = curl_init($access_token_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, FALSE);
  curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
  $result = curl_exec($curl);
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $result = json_decode($result);
  $access_token = $result->access_token;
  echo $access_token;

  curl_close($curl);


// initiating the transactions
// defining the variable

// stk push part 1 - initiate transactions on behalf of our clients
  $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

  $BusinessShortCode = '174379'; // 174379 gotten from safaricom dev test credentials replace with your own
  $Timestamp = date('YmdHis'); // 20190819092612 - yymmddhhmmss
  $PartyA = '254708003481'; // 254708003481
  $CallBackURL = 'http://vanguardtech.co.ke/MPESA_API/callback_url.php';
  $AccountReference = 'cart098'; // can be your invoice number or cart number
  $TransactionDesc = 'cart payment for online service';
  $Amount = '1';

  $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // gotten from safaricom dev test credentials
  $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp); // business shortcode then pass key then timestamp in that order. you get them from the above variables




  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $initiate_url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header


  $curl_post_data = array(
    //Fill in the request parameters with valid values
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $Amount, // THERE WAS AN ERROR HERE, PREVIOUSLY 'Amount"'
    'PartyA' => $PartyA,
    'PartyB' => $BusinessShortCode,// same as the business shortcode
    'PhoneNumber' => ' ',
    'CallBackURL' => $CallBackURL,
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
  );

  $data_string = json_encode($curl_post_data);

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

  $curl_response = curl_exec($curl);
  print_r($curl_response);

  echo $curl_response;
  ?>
