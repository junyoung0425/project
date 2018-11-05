<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use HTMLDomParser;
use Curl;

class TestController_origin extends Controller
{
    // 어디까지 읽어들일것인가
    // a 태그 한번더 컬해서 링크를 화면 표시하도록
    // 받아오기 
    // 뷰에서 좌측 읽어들인 내용 우측 에러 내용 분리
    // web php에서 입력 받는 부분 작성
    //javascrpt로 되어있는 부분은 삭제
    // str_getcsv 로 csv 파일 형식 다운로드 가능


    public function test($test){
        echo $test;
        $test = $this->getUrl($test);
        $response = $this->getCurl($test); // 받은 입력값에 컬
        // $headers = $response->headers; // http status code 또한 불러오게함
        // print_r($response->status);
        // return;
        $dom = $this->domParser($response->content); // 입력값 돔 파싱
        $a = $dom->find('a'); // 돔 파싱 부분 중 a 태그만 표시
        $javascript = 'javascript:;';
        // $errorcode = $this->getCurl($test); //에러 코드 표시

        foreach($a as $b){

            
           //  echo $b->plaintext; // a 태그중 플레인텍스트만 화면표시
            if(strpos($b->href,$javascript) !== false || $b->href == "/" || strpos($b->href, '#') !== false) // 조건문 자바스크립트 항목일때는 표시하지 않는다
            {
               continue;
            }
            $url = $b->href;
         //   echo $url; // a 태그중 href(링크만) 화면에 표시
            //'/'시작하면
            if(strpos($url, '/') === 0){
                $url = $test.$url;
            }
         //   echo "<br>";
            echo $url; 
            $this->getUrl($url);
            //http 또는 https가 없으면 http 또는 https를 붙혀준다.
           $response2 = $this->getCurl($url); // 
           echo "<br>"; // 엔터키 입력

           if($response2->status !=200 ) {
            echo $response2->status;  // 에러 코드 표시
            echo "<br>";
          //  echo $a->plaintext; // 에러 관련 정보 a tag 내의 설명항목 <에러생김>☆
            }

        }

    }
    private function getUrl($url)
    {
        if(strpos($url, 'https://') === false){
            $url = 'https://'.$url;
        }//http 또는 https가 없으면 http 또는 https를 붙혀준다.

        else{

            $url = $url;

        }//http 또는 https가 있으면 그대로 (테스트 불가능, 수정 필요)

    
        return $url;
    }    

    private function domParser($html){
       
        $dom = HTMLDomParser::str_get_html($html);
        return $dom;
    }
    private function getCurl($link){ // 컬 요청에 ->withResponseHeaders()->returnResponseObject()를 추가하여  
        return Curl::to($link)->returnResponseObject()->get();

    }// 함수 분리해서 하나는 전체 정보, 하나는 일부 정보를 가져오도록 분리

    private function getStatusfromCurl($link){ // 컬 요청에 ->withResponseHeaders()->returnResponseObject()를 추가하여  
        return Curl::to($link)->returnResponseObject();
    }

        }// 함수 분리해서 하나는 전체 정보, 하나는 일부 정보를 가져오도록 분리

 //   private function getStatusfromCurl($link){ // 컬 요청에 ->withResponseHeaders()->returnResponseObject()를 추가하여  
  //      return Curl::to($link)->returnResponseObject()->get();
   // }


