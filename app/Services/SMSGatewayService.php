<?php
namespace App\Services;
use GuzzleHttp\Client;

class SMSGatewayService
{

    protected $authKey;
    protected $senderId;

    public function __construct()
    {
        $this->authKey = "203072AnlNG6LV628dbf96P1";
        $this->senderId = "JOTHYD";

    }

    public function sendSMS($phoneNumber, $message,$templateId)
    {
        $authKey = $this->authKey;
        $mobileNumber = $phoneNumber;
        $message = $message;
        $sender = $this->senderId;
        $route = 4; // Modify as required
        $countryCode = '91'; // Modify as required
        $DLT_TE_ID = $templateId;

        $client = new Client();

        $url = 'http://tx.kappian.com/api/sendhttp.php';

        $queryParams = http_build_query([
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $sender,
            'route' => $route,
            'country' => $countryCode,
            'DLT_TE_ID' => $DLT_TE_ID,
        ]);

        $requestUrl = $url . '?' . $queryParams;

        try {
            $response = $client->get($requestUrl);
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
            if ($statusCode === 200) {
                return "SMS sent successfully!";
            } else {
                return "Failed to send SMS!";
            }
        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle request exceptions
            return "Exception: " . $e->getMessage();
        }

    }

    /*************TEMPALTES******************************************************
     *
     *  TEMP ID  : 1207169036992189664
     *  USE CASE :  Sign up verification 1  (for mobile app)
     *  CONTENT  :  Your One Time Password (OTP) for login at JDC Care is {#var#}.
     *              Do not share your OTP with anyone. Thank You! JDC Team visit
     *              www.jothydev.net, www.sugarcart.in
     * ============================================================================
     *  TEMP ID  : 1207169088755251452
     *  USE CASE : PID no. Verification  (For Mobile app)
     *  CONTENT  : Your One Time Password (OTP) for login at JDC Care is {#var#}.
     *             Do not share your OTP with others. Thank You! JDC Team visit
     *             www.jothydev.net, www.sugarcart.in
     *=============================================================================
     *  TEMP ID  : 1207169088615541010
     *  USE CASE : Reset password  (for mobile app)
     *  CONTENT  : We received a request to reset the password on your JDC Care Account.
     *             {#var#} Enter this code to complete the reset. Thank you! JDC Team
     *             Visit www.jothydev.net, www.sugarcart.in
     *
     *
     ***********************************************************************************/

}
?>
