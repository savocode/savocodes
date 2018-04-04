<?php

/**
 * Function to split name into first_name & last_name
 * @param  string $name
 * @return array
 */
function str_split_name($name)
{
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
    return array($first_name, $last_name);
}

function calculatePercentage($totalAmount, $percentage, $returnDecimal=2)
{
    $value = ($totalAmount * $percentage / 100);

    return (string) ($returnDecimal ? number_format($value, $returnDecimal, '.', '') : $value);
}

function valid_email($value)
{
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

function format_currency($value, $returnDecimal=2)
{
    return number_format($value, $returnDecimal);
}

function prefixCurrency($value, $returnDecimal=2)
{
    return '$' . format_currency($value, $returnDecimal);
    // return App\Models\Setting::extract('app.config.credit_currency') . ' ' . format_currency($value, $returnDecimal);
}

function distanceText($distance)
{
    $miles           = $distance / 1609.34;
    $metersOverMiles = $distance % 1609.34;

    if ($miles > 0) {
        $totalDistance = sprintf("%d miles %d meters", $miles, $metersOverMiles);
    } else {
        $totalDistance = sprintf("%d meters", $metersOverMiles);
    }

    return $totalDistance;
}

function fcmNotification($token, $title=null, $body=null, $payload=array())
{
    if ( null === env('FCM_SERVER_KEY') ) {
        throw new Exception('FCM_SERVER_KEY is not set in environment file.');
    }

    $fields = array_merge($payload, [
        'to' => $token,
        'notification' => ($title || $body) ? [
            'title' => $title,
            'body' => $body,
        ] : null
    ]);

    $headers = [
        'Authorization: key=' . env('FCM_SERVER_KEY'),
        'Content-Type: application/json'
    ];

//    echo "<pre>";
//    print_r($fields);
//    echo "<pre>";exit;

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec( $ch );
    curl_close( $ch );

    return $result;
}

/*
 * Project related helpers
 */
function generateGroupName($idOne, $idTwo, $prefix='u')
{
    $idOne = trim($idOne, $prefix);
    $idTwo = trim($idTwo, $prefix);

    $userIds = [$idOne, $idTwo];
    sort($userIds);

    return $prefix . implode('_' . $prefix, $userIds);
}

function generateRideShareId($tripId, $userId, $userType)
{
    $uuid5 = Ramsey\Uuid\Uuid::uuid5(Ramsey\Uuid\Uuid::NAMESPACE_DNS, "{$tripId}-{$userId}-{$userType}");

    return $uuid5->toString();
}

function removeQuotes($value)
{
    return str_replace(["'", '"'], [], $value);
}

function transformGenderStringToInteger($string)
{
    switch (strtolower($string)) {
        case 'male':
            return 1;
            break;
        case 'female':
            return 2;
            break;
        default:
            return intval($string);
            break;
    }
}

// Determine whether value exist in combination or not?
function hasBitValue($total, $validate)
{
    return (($total & $validate) !== 0);
}
