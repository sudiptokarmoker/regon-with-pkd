<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Paynow\Client;
use Paynow\Environment;
use Paynow\Exception\PaynowException;
use Paynow\Notification;
use Paynow\Service\Payment;

class DevController extends Controller
{
    public function index(Request $request)
    {
        $client = new Client('0e723f5e-6be2-4b14-ae88-f53866b8dd06', '7cccad3a-a4c6-4210-bf3c-69e30b8ab183', Environment::SANDBOX);
        $orderReference = "success_1234570";
        $idempotencyKey = uniqid($orderReference . '_');

        $paymentData = [
            'amount' => '100',
            'currency' => 'PLN',
            'externalId' => $orderReference,
            'description' => 'Payment description',
            'buyer' => [
                'email' => 'sudiptocsi@gmail.com',
            ],
        ];
        try {
            $payment = new Payment($client);
            $result = $payment->authorize($paymentData, $idempotencyKey);
            /**
             * converting this data to array
             */
            $data = (array) $result;
            // getting the result data from converted data
            $getResultResonseData = [];
            foreach ($data as $item) {
                $getResultResonseData[] = $item;
            }
            if (count($getResultResonseData) > 0 && $getResultResonseData[1] == 'NEW') {
                //dd("valid request found here");
                return redirect()->away($getResultResonseData[2]);
            } else {
                dd("not valid request this is");
            }
        } catch (PaynowException $exception) {
            // catch errors
            dd($exception->getMessge());
        }
    }
    /**
     * payment confrimation web hook here
     */
    public function notify_paynow_payment(Request $request)
    {
        Log::info('Payment triggerd here now');
        /***
         * for notification purposes */
        $payload = trim(file_get_contents('php://input'));
        if($payload){
            $headers = getallheaders();
            $notificationData = json_decode($payload, true);
            try {
                new Notification('7cccad3a-a4c6-4210-bf3c-69e30b8ab183', $payload, $headers);
                // process notification with $notificationData
            } catch (Exception $exception) {
                header('HTTP/1.1 400 Bad Request', true, 400);
            }
            header('HTTP/1.1 202 Accepted', true, 202);
            // end of notification
        }
    }
}
