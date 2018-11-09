<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use HTMLDomParser;
use Curl;




class TestController extends Controller
{
    // 어디까지 읽어들일것인가
    // a 태그 한번더 컬해서 링크를 화면 표시하도록
    // 받아오기 
    // 뷰에서 좌측 읽어들인 내용 우측 에러 내용 분리
    // web php에서 입력 받는 부분 작성
    //javascrpt로 되어있는 부분은 삭제
    // str_getcsv 로 csv 파일 형식 다운로드 가능

    
    public function test($test){
        // url check
        //$url = filter_var($url, FILTER_SANITIZE_URL);
        // Validate url
        // if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
        //     echo("$url is a valid URL");
        // } else {
        //     echo("$url is not a valid URL");
        // return false;
        // }
        $results;
        $arrIdx = 0;
        $arrIdx2 = 0;

        $test = $this->getUrl($test);
        $response = $this->getCurl($test); // 받은 입력값에 컬
        // $headers = $response->headers; // http status code 또한 불러오게함
        // print_r($response->status);
        // return;
        $dom = $this->domParser($response->content); // 입력값 돔 파싱
        $a = $dom->find('a'); // 돔 파싱 부분 중 a 태그만 표시
        // $errorcode = $this->getCurl($test); //에러 코드 표시

        foreach($a as $b){

            
           //  echo $b->plaintext; // a 태그중 플레인텍스트만 화면표시
            if(strpos($b->href, 'javascript:') === false && strpos($b->href, '#') === false && $b->href !== '/')
            {
                
                $url = $b->href;
           
             //   if(strpos($url,'html') === true)  $this->getUrl($url);  // html 의 경우 앞부분 주소를 추가 ☆ (앞부분은 어디인가?)
             //   else  
               $this->getUrl($url);//http 또는 https가 없으면 http 또는 https를 붙혀준다.

                $response2 = $this->getCurl($url); // 
               
             //    echo $url; 
                // echo "<br>"; // 엔터키 입력

                if($response2->status !==200 && $response2->status !==301 && $response2->status !==302  && $response2->status !== 0 ) {    //   스테이터스가 0인 경우(자바스크립트임) -->200(정상 스테이터스)가 아닐시에 반환

                    $results['fail']['data'][$arrIdx][] = $response2->status;
               //     alert ['status'][$arrIdx];
                    $results['fail']['data'][$arrIdx][] = $url;
                    $arrIdx++;
                    // echo $response2->status;  // 에러 코드 표시
                    // echo "<br>";
                    // echo "<a href='".$b->href."'>".$b->plaintext."</a>";
                    // echo "<br>";
            //  echo $a->plaintext; // 에러 관련 정보 a tag 내의 설명항목 <에러생김>☆
                }else if($response2->status !== 0){
                    
                    $results['succ']['data'][$arrIdx2][] = $response2->status;
               //     alert ['status'][$arrIdx];
                    $results['succ']['data'][$arrIdx2][] = $url;
                    $arrIdx2++;
                }
           
            }
        }
        
        return json_encode($results);

    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
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

    /*
     function ajaxExample(){
        // 사용자 링크를 갖고 온다.
        var userId = $("#link").val();
         
        // 사용자 ID(문자열)을 name형태로 담는다.
        var allData = { "userId": userId};
         
        $.ajax({
            url:"goUrl.do",
            type:'GET',
            data: allData,
            success:function(data){
                alert("완료!");
                window.opener.location.reload();
                self.close();
            },
            error:function(jqXHR, textStatus, errorThrown){
                alert("에러 발생~~ \n" + textStatus + " : " + errorThrown);
                self.close();
            }
        });
    }
*/ 
        }// 함수 분리해서 하나는 전체 정보, 하나는 일부 정보를 가져오도록 분리




 //   private function getStatusfromCurl($link){ // 컬 요청에 ->withResponseHeaders()->returnResponseObject()를 추가하여  
  //      return Curl::to($link)->returnResponseObject()->get();
   // }

   // 추가 입력 부분(읽어들이고싶은 URL이 포함된 부분만 선택가능한 추가적인 요소 = 검색 옵션 추가)
   //STRPOS 나 PREG_MATCH(HTML) 

   // CSV파일로 다운 영역


