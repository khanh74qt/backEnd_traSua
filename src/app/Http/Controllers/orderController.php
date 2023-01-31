<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\orderModel;
use App\model\historyOrderModel;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;

class orderController extends Controller
{
    //method này dùng để truy xuất ra order của khách hàng gần nhất để tiến hành gửi về client để pha chế
    public function orderInfo()
    {
        if(Auth::guard('api')->check()){
            $historyOrder = new historyOrderModel;
            $order = new orderModel;
            $data = $order::all();
            if(count($data)===0){
                //khong co data
                return response()->json(['message'=>'No_Order'], 200);
            }else{
                //co data
                $data = $data[0]->content;
                $historyOrder->content = $data;
                $historyOrder->save();
                orderModel::whereNotNull('id')->delete();
                $data_json = json_decode($data, true);
                return response()->json($data_json, 200);
            }
            
            
            


        }else{
            return response()->json(['message'=>'authentication'],401);
        }

    }
    //method này dùng để nhận thông tin order của khách hàng lưu vào database
    public function orderSend(Request $request)
    {
        $order = new orderModel;
        $order->content = $request->content;
        $order->save();
        return response()->json(['message' => 'success'], 200);
    }
}
