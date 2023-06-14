<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LengthException;
use Tymon\JWTAuth\Facades\JWTAuth;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        $order = Orders::where('catering_id', '=', $cateringId)->get();

        $unpaid = $order->where('status', '=', 'UNPAID')->count();
        $void = $order->where('status', '=', 'VOID')->count();
        $paid = $order->where('status', '=', 'PAID')->count();
        $pending = $order->where('status', '=', 'PENDING')->count();
        $notApproved = $order->where('status', '=', 'NOT_APPROVED')->count();
        $proccesed = $order->where('status', '=', 'PROCESSED')->count();
        $ongoing = $order->where('status', '=', 'ONGOING')->count();
        $sending = $order->where('status', '=', 'SEND')->count();
        $accepted = $order->where('status', '=', 'ACCEPTED')->count();
        $complain = $order->where('status', '=', 'COMPLAINT')->count();
        
        $month_label = ['','Januari', 'Februari', 'Maret', 'April', 
            'Mei', 'Juni', 'Juli', 'Agustus', 'September', 
            'Oktober', 'November', 'Desember'];

        if(request()->year == null){
            // $year = date('Y');
            $year = Carbon::now()->isoFormat('Y');
        }else{
            $year = request()->year;
        }

        $yearOption = DB::table('orders')
            ->addSelect(DB::raw('YEAR(end_date) as years'))
            // ->whereYear('end_date', '=', $year)
            ->where('catering_id', '=', $cateringId)
            ->groupBy('years')
            ->orderByRaw('years ASC')
            ->get();

        if(count($yearOption) != 0){
            foreach($yearOption as $result){
                $yearSelect[] = $result->years;

            }

        }else{
            $yearSelect = null;
        }

        $status = ['UNPAID', 'VOID', 'PAID', 'PENDING', 'NOT_APPROVED', 
            'PROCESSED', 'ONGOING', 'SEND', 'ACCEPTED', 'COMPLAINT'];

        $i = 0;
        $value = array(array());
        foreach($status as $result){
            $transactionStatus = DB::table('orders')
                ->addSelect(DB::raw('COUNT(id) as total'))
                ->addSelect(DB::raw('MONTH(created_at) as month'))
                ->where('catering_id', '=', $cateringId)
                ->whereYear('created_at', '=', $year)
                ->where('status', '=', $result)
                ->groupBy('month')
                ->orderByRaw('month ASC')
                ->get();
                $counter = [];
                if($transactionStatus){
                    $start_value = 1;
                    foreach($transactionStatus as $result2){
                        for($j=$start_value; $j <= $result2->month; $j++){
                            if($j == $result2->month){
                                $value[$i][] = $result2->total;
                                $start_value = $result2->month+1;
                                $counter[] = $i;
                            }else{
                                $value[$i][] = 0;
                                $counter[] = $i;
                            }
                        };

                    }
                    $a = count($counter);
                    if(count($counter) <=11){
                        $start_value2 = count($counter)+1;
                        for($jj=$start_value2; $jj<=11; $jj++){
                            $value[$i][] = 0;
                            $counter[] = $i;
                        };
                    }
                }
                $i++;
        }

        $transaction = DB::table('orders')
            ->addSelect(DB::raw('SUM(total_price) as total_price'))
            ->addSelect(DB::raw('MONTH(end_date) as month'))
            ->addSelect(DB::raw('MONTHNAME(end_date) as month_name'))
            ->addSelect(DB::raw('YEAR(end_date) as years'))
            ->addSelect(DB::raw('COUNT(id) as total_transaction'))
            ->where('catering_id', '=', $cateringId)
            ->whereYear('end_date', '=', $year)
            ->where('status', '=', 'ACCEPTED')
            ->groupBy('month')
            ->orderByRaw('month ASC')
            ->get();

            if($transaction){
                $month_name =[];
                $total_transaction =[];
                $start_value = 1;
                foreach($transaction as $result){
                    for($i=$start_value; $i <= $result->month; $i++){
                        if($i == $result->month){
                            $month_name[] = $result->month_name;
                            $total_price[] = $result->total_price;
                            $total_transaction[] = $result->total_transaction;
                            $start_value = $result->month+1;
                        }else{
                            $month_name[] = $month_label[$i];
                            $total_price[] = 0;
                            $total_transaction[] = 0;
                        }
                    };

                }
                $a = count($month_name);
                if(count($month_name) <=12){
                    $start_value2 = count($month_name)+1;
                    for($j=$start_value2; $j<=12; $j++){
                        $month_name[] = $month_label[$j];
                        $total_price[] = 0;
                        $total_transaction[] = 0;
                    };
                }
            }

        //response
        return response()->json([
            'success' => true,
            'message' => 'Statistik Data',
            'data' => [
                'count' => [
                    'unpaid' => $unpaid,
                    'void' => $void,
                    'paid' => $paid,
                    'pending' => $pending,
                    'notApproved' => $notApproved,
                    'proccesed' => $proccesed,
                    'ongoing' => $ongoing,
                    'sending' => $sending,
                    'accepted' => $accepted,
                    'complain' => $complain,
                ],
                'chart_income' => [
                    'month_name' => $month_name,
                    'total_price' => $total_price,
                    'year' => $year,
                ],
                'chart_transaction' => [
                    'month_name' => $month_name,
                    'transactionUnpaid' => $value[0],
                    'transactionVoid' => $value[1],
                    'transactionPaid' => $value[2],
                    'transactionPending' => $value[3],
                    'transactionNotApproved' => $value[4],
                    'transactionProccesed' => $value[5],
                    'transactionOngoing' => $value[6],
                    'transactionSending' => $value[7],
                    'transactionAccepted' => $value[8],
                    'transactionComplaint' => $value[9],
                    'total_transcation' => $total_transaction,
                    'year' => $year,
                ],
                'year_option' => $yearSelect,
                'message' => request()->year . 'msg1',
                'message2' => $year . 'msg2'
            ]
        ], 200);
    }
}
