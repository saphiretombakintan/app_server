<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\T003;
use App\T101;
use App\Veritrans\Veritrans;
use Illuminate\Http\Request;
use App\Http\Requests;

class VTController extends Controller
{
    public function __construct()
    {
        Veritrans::$serverKey = 'SB-Mid-server-iykXrCTadcrXdN-4RhB9TS6n';
        Veritrans::$isProduction = false;

    }

    public function finishVT() {
        return view('sukses-midtrans');
    }

    public function failVT() {
        return view('gagal-midtrans');
    }

    public function notif()
    {
        $vt = new Veritrans;
        echo 'test notification handler';
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if ($result) {
            $notif = $vt->status($result->order_id);
        }

        error_log(print_r($result, true));

        $transaction  = $notif->transaction_status;
        $type         = $notif->payment_type;
        $order_id     = $notif->order_id;
        $gross_amount = $notif->gross_amount;
        $fraud        = $notif->fraud_status;
        $va_number    = $notif->va_numbers[0]->va_number;

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    echo "Transaction order_id: " . $order_id . " is challenged by FDS";
                } else {
                    // TODO set payment status in merchant's database to 'Success'
                    echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
                }
            }
        } else if ($transaction == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'

            $t101_id = T101::where('order_id', $order_id)->get();
            $t002_id = User::where('code', $t101_id[0]->code_customer)->get();

            $userkey = "1xsbad";
            $passkey = "abc123";
            $notelp  = $t002_id[0]->phone;
            $msg     = "Terima Kasih." . "\n" .
                    "Nomor Virtual Account " . $va_number . " Sukses di-transfer";


            $url = "https://alpha.zenziva.net/apps/smsapi.php";
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $notelp . '&pesan=' . urlencode($msg));
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            $results = curl_exec($curlHandle);
            curl_close($curlHandle);

            T101::where('order_id', $order_id)->update([
                'status_fp' => 'SETTLEMENT FROM VT',
            ]);

            T003::where('code_unit', $t101_id[0]->code_unit)->update([
                'status_unit' => 'close',
            ]);

        } else if ($transaction == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;

            $t101_id = T101::where('order_id', $order_id)->get();
            $t002_id = User::where('code', $t101_id[0]->code_customer)->get();

            T101::where('order_id', $order_id)->update([
                'status_fp' => 'PENDING FROM VT',
            ]);

            T003::where('code_unit', $t101_id[0]->code_unit)->update([
                'status_unit' => 'order',
            ]);

            $userkey = "1xsbad";
            $passkey = "abc123";
            $notelp  = $t002_id[0]->phone;
            $msg     = "Terima Kasih." . "\n" .
                    "Nomor Virtual Account " . $va_number . "\n" .
                    "Silakan selesaikan Pembayaran Anda.";

            $url = "https://alpha.zenziva.net/apps/smsapi.php";
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $notelp . '&pesan=' . urlencode($msg));
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            $results = curl_exec($curlHandle);
            curl_close($curlHandle);

        } else if ($transaction == 'deny') {
            // TODO set payment status in merchant's database to 'Denied'
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";

            $t101_id = T101::where('order_id', $order_id)->get();
            $t002_id = User::where('code', $t101_id[0]->code_customer)->get();

            T101::where('order_id', $order_id)->update([
                'status_fp' => 'DENY FROM VT',
            ]);

            T003::where('code_unit', $t101_id[0]->code_unit)->update([
                'status_unit' => 'available',
            ]);

            $userkey = "1xsbad";
            $passkey = "abc123";
            $notelp  = $t002_id[0]->phone;
            $msg     = "Mohon maaf." . "\n" .
                    "Nomor Virtual Account " . $va_number . "\n" .
                    "Pembayaran Anda tertolak.";

            $url = "https://alpha.zenziva.net/apps/smsapi.php";
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $notelp . '&pesan=' . urlencode($msg));
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            $results = curl_exec($curlHandle);
            curl_close($curlHandle);

        } else if ($transaction == 'expire') {
            // TODO set payment status in merchant's database to 'Denied'
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";

            $t101_id = T101::where('order_id', $order_id)->get();
            $t002_id = User::where('code', $t101_id[0]->code_customer)->get();

            T101::where('order_id', $order_id)->update([
                'status_saldo' => 'EXPIRE FROM VT',
            ]);

            T003::where('code_unit', $t101_id[0]->code_unit)->update([
                'status_unit' => 'available',
            ]);

            $userkey = "1xsbad";
            $passkey = "abc123";
            $notelp  = $t002_id[0]->phone;
            $msg     = "Mohon maaf." . "\n" .
                    "Nomor Virtual Account " . $va_number . "\n" .
                    "Telah melewati masa Pembayaran.";

            $url = "https://alpha.zenziva.net/apps/smsapi.php";
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey=' . $userkey . '&passkey=' . $passkey . '&nohp=' . $notelp . '&pesan=' . urlencode($msg));
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            $results = curl_exec($curlHandle);
            curl_close($curlHandle);

        }

    }
}
