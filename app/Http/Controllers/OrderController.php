<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function pay()
    {
        try {
            $order = DB::table('orders')->where('status', 'pending')->first();

            if (!$order) return redirect()->back();

            DB::transaction(function () use ($order) {
                DB::table('orders')->where('id', $order->id)->update([
                    'status'        => 'paid',
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);

                if ($order->referred_by) {
                    $course = DB::table('courses')->where('id', $order->course_id)->first();

                    $credit = floatval(($course->price * 10) / 100);

                    DB::table('point_transactions')->insert([
                        'created_at' => date('Y-m-d H:i:s'),
                        'credit'    => $credit,
                        'user_id'   => $order->referred_by
                    ]);

                    $credit = DB::table('point_transactions')->where('user_id', $order->referred_by)->sum('credit');
                    $debit  = DB::table('point_transactions')->where('user_id', $order->referred_by)->sum('debit');

                    $point = ($credit - $debit);

                    DB::table('user_details')->where('user_id', $order->referred_by)->update([
                        'point' => $point
                    ]);
                }
            });

            return redirect()->back();
        } catch (Exception $e) {
            dd($e);
        }
    }
}
