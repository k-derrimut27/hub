<?php
$appId = '/7a2QyK0qXLFj60pwYgWnLck1aSMMnBRz4Hazu3x4sX2YrtEp/dOjnRiOArVgn2V';
$secret = '29e14ab9-7f2d-4885-8131-c2a92e1e5c8a';
$refreshToken = 'd98e8ce4-0dfc-4b61-870f-c3430522b87e';
$klaviyoPrivateAPIKey = 'pk_54cf0b5b8ba9113f8842f1ed5188fed0aa';
$klaviyoWorkflowID = 'YOUR_KLAVIYO_WORKFLOW_ID';
// Step 1: Obtain the access token from Clubware
$accessTokenURL = 'https://api-derrimut247.clubware.com.au/Authorisation/GetAccessToken';
$data = array(
    'appid' => $appId,
    'secret' => $secret,
    'refresh_token' => $refreshToken
);
$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);
$context = stream_context_create($options);
$response = file_get_contents($accessTokenURL, false, $context);
$access_token = json_decode($response, true)['access_token'];
// Step 2: Retrieve customer list from Clubware
$clubwareCustomerListURL = 'https://api-derrimut247.clubware.com.au/Customers/GetCustomers';
$clubwareOptions = array(
    'http' => array(
        'header' => "Authorization: Bearer $access_token\r\n",
        'method' => 'GET'
    )
);
$clubwareContext = stream_context_create($clubwareOptions);
$clubwareResponse = file_get_contents($clubwareCustomerListURL, false, $clubwareContext);
$customers = json_decode($clubwareResponse, true);
// Step 3: Connect to Klaviyo's API
// Step 4: Prepare data for Klaviyo
$klaviyoData = array();
foreach ($customers as $customer) {
    $klaviyoData[] = array(
        'email' => $customer['email'],
        'properties' => array(
            // Additional properties or custom fields for Klaviyo
            // Example: 'first_name' => $customer['first_name']
        )
    );
}
// Step 5: Trigger email workflow in Klaviyo
$klaviyoURL = "https://a.klaviyo.com/api/v2/workflows/$klaviyoWorkflowID/experiences/manual/flow";
$klaviyoOptions = array(
    'http' => array(
        'header' => "Content-type: application/json\r\n",
        'method' => 'POST',
        'header' => "Authorization: Bearer $klaviyoPrivateAPIKey\r\n",
        'content' => json_encode($klaviyoData)
    )
);
$klaviyoContext = stream_context_create($klaviyoOptions);
$klaviyoResponse = file_get_contents($klaviyoURL, false, $klaviyoContext);
// Handle Klaviyo response
if ($klaviyoResponse === false) {
    // Handle error
} else {
    // Handle success
}
?>