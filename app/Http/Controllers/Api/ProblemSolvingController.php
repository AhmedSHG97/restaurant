<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProblemSolvingController extends Controller
{
    private $apiResponse,$validator;
    public function __construct(ApiResponse $apiResponse, Validator $validator)
    {
        $this->apiResponse = $apiResponse;
        $this->validator = $validator;
    }

    public function getNumbersCount(Request $request){
        $rules = [
            "start_number" => "required|integer",
            "end_number" => "required|integer|gt:start_number",
        ];
        $validation = $this->validator::make($request->all(), $rules);
        if($validation->fails()){
            return $this->apiResponse->setError($validation->errors()->first())->setData()->getJsonResponse();
        }
        $start = $request->start_number;
        $end = $request->end_number;
        $count = 0;
        for($i = $start; $i <= $end; $i++){
            // the hard way  -> execution time is 130 ms
            $not_countable = false;
            $number = $i;
            while($number  > 0){
                $digit = $number % 10;
                if($digit == 5){
                    $not_countable = true;
                    $number = $number / 10;
                    break;
                }
                $number = $number / 10;
            }
            if($not_countable){
                continue;
            }
            $count++;

            //the easy way -> execution time is 21 ms
            /*
            if(str_contains($i,"5")){
                continue;
            }
            $count++;
            */
        }
        return $this->apiResponse->setSuccess("the count of numbers that does not contain digit 5 is")->setData($count)->getJsonResponse();

    }
    public function getStringIndex(Request $request){
        $rules = [
            "input_string" => "required|string|regex:/^[a-zA-Z]+$/|min:1",
        ];
        $validation = $this->validator::make($request->all(), $rules,["input_string" => "the string should contain characters only with no spaces"]);
        if($validation->fails()){
            return $this->apiResponse->setError($validation->errors()->first())->setData()->getJsonResponse();
        }
        $string = strtoupper($request->input_string);
        $string_index = 0;
        foreach(str_split($string) as $indecator => $char){
            $char_position = ord($char) - ord('A') + 1;
            $char_index = strlen($string) - ($indecator+1);
            $string_index += $char_position * pow(26 , $char_index);
        }
        return $this->apiResponse->setSuccess("the index of the string")->setData($string_index)->getJsonResponse();

    }
}
