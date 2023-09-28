<?php

namespace App\Http\Controllers;


class ArrayController extends Controller
{
    public function index()
    {
        $array = [
            [
                "name" => "Phan Thanh Luc",
                "age" => 20,
                "province" => "Quang Tri",
                "class" => "PNV 24A"
            ],
            [
                "name" => "A Quang",
                "age" => 20,
                "province" => "Kon Tum",
                "class" => "PNV 24B"
            ],
            [
                "name" => "A Tien",
                "age" => 20,
                "province" => "Kon Tum",
                "class" => "PNV 24A"
            ],
            [
                "name" => "Ho Thi Bich",
                "age" => 20,
                "province" => "Quang Tri",
                "class" => "PNV 24A"
            ]

        ];
       

        $person=[];
        foreach ($array as $value) {
            $person[]=[
                "name"=>$value["name"],
                "age"=>$value["age"],
                "province"=>$value["province"],
                "class"=>$value["class"]
            ];
        }

        return response()->json($person);
    }

    public function sort(){

        $list_number=[0,6,3,8,12,6,0,3,7,46];

        for ($i=0; $i <count($list_number); $i++) { 
            for ($j=$i+1; $j > count($list_number) ; $j++) { 
                if ($list_number[$i]<$list_number[$j]) {
                    $number=$list_number[$i];
                    $list_number[$i]=$list_number[$j];
                    $list_number[$j]=$number;
                }
            }
        }

        return response()->json($list_number);
    }

    public function show(){
        $string = "HELLO world HOW Are YOU";

        preg_match_all('/\b[A-Z]+\b/', $string, $matches);
        
        $uppercaseWords = $matches[0];
        
        return response()->json($uppercaseWords);
    }

    public function getContenttext(){
        $array="EDUCATION i learned at passerelles numeriques 
        HOBBIES listening to music";
        
        $line=explode("\n",$array);
        $result=[];
        $uppercaseWords="";

        foreach ($line as $value) {
            if (preg_match("/^[A-Z]+/",$value)) {
                $uppercaseWords=$value;
                $result[$uppercaseWords]="";
            }else{
                $result[$uppercaseWords] .=$value;
            }
        }
        foreach ($result as &$lowercaseGroup) {
            $lowercaseGroup = trim($lowercaseGroup);
        }

        return response()->json($result);
    }
}
