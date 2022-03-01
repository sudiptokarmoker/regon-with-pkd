<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Paynow\Service\Payment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        Log::info('INDEX');
        /***
         * for notification purposes */
        $payload = trim(file_get_contents('php://input'));
        $headers = getallheaders();
        $notificationData = json_decode($payload, true);
        if ($notificationData) {
            dd("hello");
            try {
                Log::info('HELLO-SUCCESS');
                new Notification('7cccad3a-a4c6-4210-bf3c-69e30b8ab183', $payload, $headers);

                // process notification with $notificationData
            } catch (Exception $exception) {
                header('HTTP/1.1 400 Bad Request', true, 400);
            }
            header('HTTP/1.1 202 Accepted', true, 202);
            // end of notification
        }
        return view('home');
    }
}
