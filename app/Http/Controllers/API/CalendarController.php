<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CalendarController extends Controller
{
    public function calendar (){
        $month = request('month');
        
        $response = Cache::remember('holiday'.$month, now()->addHour(1), function () use ($month) {

            $daysInMonthAgo = Carbon::now()->setMonth($month-1)->daysInMonth;
            $daysInMonthafter = Carbon::now()->setMonth($month+1)->daysInMonth;
            $daysInMonthNow = Carbon::now()->setMonth($month)->daysInMonth;

            $yearNow = Carbon::now()->format('Y');
            $dateNow = Carbon::now()->format('d');
            $monthNow = Carbon::now()->format('m');

            $dayOfWeekNow = Carbon::now()->dayOfWeek;
            $firstDayOfMonth = Carbon::createFromDate(2024, $month, 1)->dayOfWeek;
            
            $response =   HTTP::withHeaders([
                'Accept' => 'application/json'
            ])->get('https://api-harilibur.vercel.app/api?month='.$month);

            $collection =  collect(json_decode($response));

            $calendar = [];

            for($i = 0; $i < 43; $i++){
    
                if($i <= $firstDayOfMonth-2){
                    $days = $daysInMonthAgo-($firstDayOfMonth-2-$i);
    
                    $calendar[$i]['day'] = $days;
                    $calendar[$i]['holiday'] = false;
                    $calendar[$i]['isMonth'] = false;
                    $calendar[$i]['isMissed'] = true;
    
                } else if($i >= $firstDayOfMonth && $i < $daysInMonthNow+$firstDayOfMonth) {
                    $day = $i-$firstDayOfMonth+1;
    
                    $calendar[$i]['day'] = $day;
                    $calendar[$i]['isMonth'] = true;
                    $calendar[$i]['isMissed'] = false;
    
                    $date = $yearNow.'-'.$month.'-'.$day;
    
                    if($month<10) {
                        $date = $yearNow.'-0'.$month.'-'.$day;
                    }
    
                    if($dateNow > $day && $month == $monthNow) {
                        $calendar[$i]['isMissed'] = true;
                    }
    
                    $checkHoliday = $collection->where('holiday_date', $date)->all();
    
                    $checkSunday = $i % 7;
    
    
                    if($checkHoliday) {
                        $calendar[$i]['holiday'] = true;
                        foreach ($checkHoliday as $data) {
                            $data->day = $day;
                        }
                    }else if(!$checkHoliday && $checkSunday == 0) {
                        $calendar[$i]['holiday'] = true;
                    }else if(!$checkHoliday && $checkSunday != 0) {
                        $calendar[$i]['holiday'] = false;
                    }
    
                } else if($i > $daysInMonthNow){
                    $calendar[$i]['day'] = $i-($daysInMonthNow+$firstDayOfMonth-1);
                    $calendar[$i]['holiday'] = false;
                    $calendar[$i]['isMonth'] = false;
                    $calendar[$i]['isMissed'] = true;
                }
            }

            return [
                'calendar' => $calendar,
                'holiday' => $collection
            ];
        });
        
      

        return $response;
    }
}
