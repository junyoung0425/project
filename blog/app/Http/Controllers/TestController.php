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

    
    public function test(Request $request){
        $results;
        try{

            $basicUrl = $request->input("url");
            $params = $request->input("params");
            if($params){
                $basicUrl .= "?";
                $basicUrl .= $params;
            }
            $basicUrl = (urldecode($basicUrl));
            $results;
            $arrIdx = 0;
            $arrIdx2 = 0;
    
            $httpUrl = $this->getUrl($basicUrl);
    
            $response = $this->getCurl($httpUrl); // 받은 입력값에 컬
    
            if(isset($response->error)){
                $httpUrl = $this->getUrl($basicUrl, 1); // 받은 입력값에 컬
                $response = $this->getCurl($httpUrl);
            }

            $dom = $this->domParser($response->content); // 입력값 돔 파싱
            $a = $dom->find('a'); // 돔 파싱 부분 중 a 태그만 표시
            $totalCnt = 0;
            foreach($a as $b){
                // if($totalCnt++ > 50){
                //     break;
                // }
               //  echo $b->plaintext; // a 태그중 플레인텍스트만 화면표시
                if(strpos($b->href, 'javascript:') === false && strpos($b->href, '#') === false && $b->href !== '/')
                {
                    
                    $url = $b->href;
               
                   $this->getUrl($url);//http 또는 https가 없으면 http 또는 https를 붙혀준다.
    
                    $response2 = $this->getCurl($url); // 
                   
    
                    if($response2->status !==200 && $response2->status !==301 && $response2->status !==302  && $response2->status !== 0 ) {    //   스테이터스가 0인 경우(자바스크립트임) -->200(정상 스테이터스)가 아닐시에 반환
    
                        $results['fail']['data'][$arrIdx][] = $response2->status;
                        $results['fail']['data'][$arrIdx][] = $url;
                        $arrIdx++;
                       
                    }else if($response2->status !== 0){
                        
                        $results['succ']['data'][$arrIdx2][] = $response2->status;
                        $results['succ']['data'][$arrIdx2][] = $url;
                        $arrIdx2++;
                    }
                }
            }     
        }catch(\Exception $e){
            
        }
        return json_encode($results);

    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    private function getUrl($url, $isHttp = 0)
    {
        $parHttp = "https://";
        
        if($isHttp){
            $parHttp = "http://";
        }

        if(strpos($url, $parHttp) === false){
            $url = $parHttp.$url;
        }

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


