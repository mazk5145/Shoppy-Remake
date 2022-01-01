<?php
error_reporting(0);

function validateaddress($ip) {
  return $ip && substr($ip, 0, 4) != '127.' && substr($ip, 0, 4) != '127.' && substr($ip, 0, 3) != '10.' && substr($ip, 0, 2) != '0.' ? $ip : false;
}

function pivlipget() {
  return
  @$_SERVER['HTTP_X_FORWARDED_FOR'] ? explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'], 2)[0] :
  @$_SERVER['HTTP_CLIENT_IP'] ? explode(',', $_SERVER['HTTP_CLIENT_IP'], 2)[0] :
  validateaddress(@$_SERVER['REMOTE_ADDR']) ?:
  'UNKNOWN';
}

function get_browser_name($user_agent)
{
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
   
    return 'Other';
}

$n=34;
function genorderid($n) {
    $characters = '0123456789abcde-fgh-ij-klmnopqrstuvwxyzABC-DEF-GHIJ-KLMNOPQRST-UV-WX-YZ';
    $randomString = '';
  
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
}

$ip = pivlipget();
$browser = get_browser_name($_SERVER['HTTP_USER_AGENT']);


if (!$_GET["username"])
{
    $username = "demo";
}

if (!$_GET["createdat"])
{
    $createdon = date('Y.m.d h:i:s');
}


if (!$_GET["amount"])
{
    $amount = "1";
}

if (!$_GET["email"])
{
    $email = "test@shoppy.gg";
}

if (!$_GET["product"])
{
    $product = "Test Product";
}

if (!$_GET["paymentmth"])
{
    $paymentmethod = "BTC";
}

if (!$_GET["paymentvalue"])
{
    $valueofproduct = "9.999";
}

$countrycode = "Country Code lolxd";

$orderid = genorderid($n);

$webhookurl = "https://discord.com/api/webhooks/926877347620220958/mAkPwsuPz-eQwRNnNB2i6-2RMXUL4zYHrcBe4MHoHYy5tWoMbyDnfA3LiaOlvJl4N-BH";

$timestamp = date("c", strtotime("now"));

$json_data = json_encode([
    "username" => "Shoppy",

    "embeds" => [
        [
            "title" => "Order Completed",

            "type" => "rich",

            "description" => $orderid,

            "url" => "https://shoppy.gg/orders/",

            "timestamp" => $timestamp,

            // Footer
            "footer" => [
                "text" => "Shoppy Webhook Recreate by mazk#9154",
                "icon_url" => "https://cdn.discordapp.com/avatars/697678869167341639/02995d4ab822ef04a06cbfa999b1e8ac.webp?size=128"
            ],

            // Additional Fields array
            "fields" => [
                // Field 1
                [
                    "name" => "Product",
                    "value" => $product,
                    "inline" => true
                ],
                // Field 2
                [
                    "name" => "Gateway",
                    "value" => $paymentmethod,
                    "inline" => true
                ],
                [
                    "name" => "Value",
                    "value" => $valueofproduct,
                    "inline" => true
                ],
                [
                    "name" => "Quantity",
                    "value" => $amount,
                    "inline" => true
                ],
                [
                    "name" => "Email",
                    "value" => $email,
                    "inline" => true
                ],
                [
                    "name" => "IP Address",
                    "value" => $ip,
                    "inline" => true
                ],
                [
                    "name" => "Country Code",
                    "value" => $countrycode,
                    "inline" => false
                ],
                [
                    "name" => "Created At",
                    "value" => "`" . $createdon . "`",
                    "inline" => false
                ],
                [
                    "name" => "Custom Field: username",
                    "value" => $username,
                    "inline" => false
                ],
                [
                    "name" => "Custom Field: Device",
                    "value" => $browser,
                    "inline" => false
                ]
                // Etc..
            ]
        ]
    ]

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


$ch = curl_init( $webhookurl );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
curl_close( $ch );
