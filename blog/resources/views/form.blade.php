<!DOCTYPE HTML>
<html>
<head>
<title>SiteChecker</title>
<meta charset="utf-8">
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,300italic" rel="stylesheet" type="text/css">
<!--[if lte IE 8]>
<script src="css/ie/html5shiv.js"></script>
<![endif]-->
<script src="js/jquery.min.js"></script>
<script src="js/jquery.poptrox-2.2.js"></script>
<script src="js/skel.min.js"></script>
<script src="js/init.js"></script>
<noscript>
<link rel="stylesheet" href="css/skel-noscript.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/loading.css">
</noscript>
<script>
$(document).ready(function(){

});

/* sortingNumber() : 숫자인 실수만으로 되어있을 때, 적용될 함수 */ 

function sortingNumber( a , b ){  

        if ( typeof a == "number" && typeof b == "number" ) return a - b; 

        // 천단위 쉼표와 공백문자만 삭제하기.  
        var a = ( a + "" ).replace( /[,\s\xA0]+/g , "" ); 
        var b = ( b + "" ).replace( /[,\s\xA0]+/g , "" ); 

        var numA = parseFloat( a ) + ""; 
        var numB = parseFloat( b ) + ""; 

        if ( numA == "NaN" || numB == "NaN" || a != numA || b != numB ) return false; 

        return parseFloat( a ) - parseFloat( b ); 
} 


/* changeForSorting() : 문자열 바꾸기. */ 

function changeForSorting( first , second ){  

        // 문자열의 복사본 만들기. 
        var a = first.toString().replace( /[\s\xA0]+/g , " " ); 
        var b = second.toString().replace( /[\s\xA0]+/g , " " ); 

        var change = { first : a, second : b }; 

        if ( a.search( /\d/ ) < 0 || b.search( /\d/ ) < 0 || a.length == 0 || b.length == 0 ) return change; 

        var regExp = /(\d),(\d)/g; // 천단위 쉼표를 찾기 위한 정규식. 

        a = a.replace( regExp , "$1" + "$2" ); 
        b = b.replace( regExp , "$1" + "$2" ); 

        var unit = 0; 
        var aNb = a + " " + b; 
        var numbers = aNb.match( /\d+/g ); // 문자열에 들어있는 숫자 찾기 

        for ( var x = 0; x < numbers.length; x++ ){ 

                var length = numbers[ x ].length; 
                if ( unit < length ) unit = length; 
        } 

        var addZero = function( string ){ // 숫자들의 단위 맞추기 

                var match = string.match( /^0+/ ); 

                if ( string.length == unit ) return ( match == null ) ? string : match + string; 

                var zero = "0"; 

                for ( var x = string.length; x < unit; x++ ) string = zero + string; 

                return ( match == null ) ? string : match + string; 
        }; 

        change.first = a.replace( /\d+/g, addZero ); 
        change.second = b.replace( /\d+/g, addZero ); 

        return change; 
} 

/*    ordinary() 
 * 
 * 1. ascending : 오름차순 
 * 2. descending : 내림차순 
*/ 

function ordinary(){ 

        var compare = function( a , b ){ 

                var sorting = sortingNumber( a , b ); 

                if ( typeof sorting == "number" ) return sorting; 

                var change = changeForSorting( a , b ); 

                var a = change.first; 
                var b = change.second; 

                return ( a < b ) ? -1 : ( a == b ) ? 0 : 1; 
        }; 

        var ascendingOrder = function( a , b ){  return compare( a , b );  }; 
        var descendingOrder = function( a , b ){  return compare( b , a );  }; 

        return { ascending : ascendingOrder, descending : descendingOrder }; 
} 


/*    ignoreCase() : 대소문자 무시 ( toLowerCase ) 
 * 
 * 1. ascending : 오름차순 
 * 2. descending : 내림차순 
*/ 

function ignoreCase(){ 

        var compare = function( a , b ){ 

                var sorting = sortingNumber( a , b ); 

                if ( typeof sorting == "number" ) return sorting; 

                var change = changeForSorting( a , b ); 

                var a = change.first.toLowerCase(); 
                var b = change.second.toLowerCase(); 

                return ( a < b ) ? -1 : ( a == b ) ? 0 : 1;   // 또는,  return a.localeCompare ( b );
        }; 

        var ascendingOrder = function( a , b ){  return compare( a , b );  }; 
        var descendingOrder = function( a , b ){  return compare( b , a );  }; 

        return { ascending : ascendingOrder, descending : descendingOrder }; 
} 


/*    byLocale() : 로컬에 따라 ( localeCompare ) - 언어에 따라, 결과가 다를 수 있음. 
 * 
 * 1. ascending : 오름차순 
 * 2. descending : 내림차순 
*/ 

function byLocale(){ 

        var compare = function( a , b ){ 

                var sorting = sortingNumber( a , b ); 

                if ( typeof sorting == "number" ) return sorting; 

                var change = changeForSorting( a , b ); 

                var a = change.first; 
                var b = change.second; 

                return a.localeCompare( b ); 
        }; 

        var ascendingOrder = function( a , b ){  return compare( a , b );  }; 
        var descendingOrder = function( a , b ){  return compare( b , a );  }; 

        return { ascending : ascendingOrder, descending : descendingOrder }; 
} 



function wrapWindowByMask() {
        //화면의 높이와 너비를 구한다.
        var maskHeight = $(document).height(); 
//      var maskWidth = $(document).width();
        var maskWidth = window.document.body.clientWidth;
         
        var mask = "<div id='mask' style='position:absolute; z-index:9000; background-color:#000000; display:none; left:0; top:1000;'></div>";
        var loadingImg = '';
         
        loadingImg += "<div id='loadingImg' style='position:absolute; left:50%; top:40%; display:none; z-index:10000;'>";
        loadingImg += " <img src='images/loading.gif'/>";
        loadingImg += "</div>";  
     
        //화면에 레이어 추가
        $('body')
            .append(mask)
            .append(loadingImg)
           
        //마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채운다.
        $('#mask').css({
                'width' : maskWidth
                , 'height': maskHeight
                , 'opacity' : '0.3'
        }); 
     
        //마스크 표시
        $('#mask').show();   
     
        //로딩중 이미지 표시
        $('#loadingImg').show();
    }

function closeWindowByMask() {
        $('#mask, #loadingImg').hide();
        $('#mask, #loadingImg').remove();  
    }

function clickXXX(args){
  wrapWindowByMask();  
  var url = $(args).val();
  url = url.replace("https://","");
  url = url.replace("http://","");
  
   
  $.ajax(
          { 
                type: 'get' 
                , url: '/test/'+url //입력 부분 추가 
                , dataType : 'json' // 받아올 데이터 형식
                , success: function(data) 
                {
                  var append1 = "";
                  var append2 = "";
                  var tbody1 = $("#succ > .inner > table > tbody");  
                  var tbody2 = $("#first > .inner > table > tbody");  
                  tbody1.html("");
                  tbody2.html("");
                  for(i=0;i<data.succ.status.length;i++){
                    append1 += "<tr>";
                    append1 += "<td>";
                    append1 += data.succ.status[i];
                    append1 += "</td>";
                    append1 += "<td>";
                    append1 += data.succ.url[i];
                    append1 += "</td>";
                    append1 += "</tr>";
                  }
                  for(i=0;i<data.fail.status.length;i++){
                    append2 += "<tr>";
                    append2 += "<td>";
                    append2 += data.fail.status[i];
                    append2 += "</td>";
                    append2 += "<td>";
                    append2 += data.fail.url[i];
                    append2 += "</td>";
                    append2 += "</tr>";
                  }
                  
                  tbody1.append(append1);
                  tbody2.append(append2);
                  closeWindowByMask(); 
                } 
                , error: function(data)
                {
                  closeWindowByMask(); 
                  alert('유효하지 않은 URL 입니다');
                                   
                }
              
      } );
}
</script>
<!--[if lte IE 8]>
<link rel="stylesheet" href="css/ie/v8.css">
<![endif]-->
</head>
<script>
    
</script>
<body>
<section id="header">
  <header>
    <h1>SiteChecker</h1>
    <p>By 2012305072 Lim Jun Young</p>
  </header>
  <body>
    <div> 
      <label>Please Enter Your Link : </label><input class="button style2 scrolly" type="text" id="inputTest" name="" onKeyPress="if(event.keyCode==13){clickXXX(this);location.href='#first'}"  />
    </div> <!--엔터키로 검색할 경우에도 스크롤 내려가는 기능 추가 필요-->
    
  </body>
  <br>
  <footer><a id="a" href="#first" class="button style2 scrolly" onclick="javascript:clickXXX($('#inputTest'));" >Submit</a> </footer>
</section>
<article id = "first" class="container box style1"> 
<div class="inner">
<!-- 동적 테이블 -->
    <table border="1" id="table">
      <colgroup>
        <!-- column 의 설정을 할수 있다. -->
        <col style="width:30px;">
        <col style="width:100px;">
        <col style="width:150px;">
        <col style="width:50px;">
      </colgroup>

      <thead>
        <tr>
          <th>Status</th>
          <th>Url</th>
        </tr>
      </thead>
      <tbody>
        
        </tr>
      </tbody>
    </table>
  </div>
</article>


<article id="succ" class="container box style1"> 
  <div class="inner">
<!-- 동적 테이블 -->
    <table border="1" id="table">
      <colgroup>
        <!-- column 의 설정을 할수 있다. -->
        <col style="width:30px;">
        <col style="width:100px;">
        <col style="width:150px;">
        <col style="width:50px;">
      </colgroup>

      <thead>
        <tr>
          <th>Status</th> <!--<button onclick="sortTD ( 0 )">▲</button><button onclick="reverseTD ( 0 )">▼</button></th>-->
          <th>Url</th> <!--<button onclick="sortTD ( 1 )">▲</button><button onclick="reverseTD ( 1 )">▼</button></th>-->
        </tr>
      </thead>
      <tbody>
        
        </tr>
      </tbody>
    </table>
  </div>
</article>
<article class="container box style1 left"> 
  <div class="inner">
    <header>
      <h2>Mollis posuere<br>
        lectus lacus</h2>
    </header>
    <p>Rhoncus mattis egestas sed fusce sodales rutrum et etiam ullamcorper. Etiam egestas scelerisque ac duis magna lorem ipsum dolor.</p>
  </div>
</article>
<article class="container box style2">
  <header>
    <h2>검사 결과를 저장합니다</h2>
    <p>원하시는 파일 형식을 선택해 주세요</p>
  </header>
  <div class="inner gallery">
    <div class="row flush">
      <div class="3u"><a href="images/fulls/01.jpg" class="image full"><img src="images/thumbs/01.jpg" alt="" title="Ad infinitum"></a></div>
      <div class="3u"><a href="images/fulls/02.jpg" class="image full"><img src="images/thumbs/02.jpg" alt="" title="Dressed in Clarity"></a></div>
      <div class="3u"><a href="images/fulls/03.jpg" class="image full"><img src="images/thumbs/03.jpg" alt="" title="Raven"></a></div>
      <div class="3u"><a href="images/fulls/04.jpg" class="image full"><img src="images/thumbs/04.jpg" alt="" title="I'll have a cup of Disneyland, please"></a></div>
    </div>
    <div class="row flush">
      <div class="3u"><a href="images/fulls/05.jpg" class="image full"><img src="images/thumbs/05.jpg" alt="" title="Cherish"></a></div>
      <div class="3u"><a href="images/fulls/06.jpg" class="image full"><img src="images/thumbs/06.jpg" alt="" title="Different."></a></div>
      <div class="3u"><a href="images/fulls/07.jpg" class="image full"><img src="images/thumbs/07.jpg" alt="" title="History was made here"></a></div>
      <div class="3u"><a href="images/fulls/08.jpg" class="image full"><img src="images/thumbs/08.jpg" alt="" title="People come and go and walk away"></a></div>
    </div>
  </div>
</article>
<article class="container box style3">
  <header>
    <h2></h2>
    <p>페이지 이용에 도움을 주세요</p>
  </header>
  <form action="#">
    <div class="row half">
      <div class="6u">
        <input type="text" class="text" name="name" placeholder="Name">
      </div>
      <div class="6u">
        <input type="text" class="text" name="email" placeholder="Email">
      </div>
    </div>
    <div class="row half">
      <div class="12u">
        <textarea name="message" placeholder="Message"></textarea>
      </div>
    </div>
    <div class="row">
      <div class="12u">
        <ul class="actions">
          <li><a href="#" class="button form">Send Message</a></li>
        </ul>
      </div>
    </div>
  </form>
</article>
<section id="footer">
  <div class="copyright">
    <ul class="menu">
      <li>&copy; Untitled. All rights reserved.</li>
      <li>Design: <a href="http://html5up.net/">HTML5 UP</a></li>
    </ul>
  </div>
</section>

<script type="text/javascript">
$('#append_row').on("click", function () {
	$('#list_table').append(
		$('<tr>').append(
			$('<td>').append($('#add_no').val()),
			$('<td>').append($('#add_name').val()),
			$('<td>').append($('#add_birth').val()),
			$('<td>').append(
				// property와 attribute의 차이!!
				$('<a>').prop('href', '#').addClass('delete-link').append('Delete')
				// <a href="#" class="delete-link">Delete</a>
			)
		)
	);
});

$('#list_table').on("click", ".delete-link", function () {
  /*
  <tr>
    <td>3</td>
    <td>seok</td>
    <td>901217</td>
    <td>
      <a href="#" class="delete-link">Delete</a>
    </td>
  </tr>
  */
  // this == a의 부모의 부모는 tr태그
  $(this).parent().parent().remove();
})
</script>







</body>
</html>


$(function(){ $("#listButton").click(function(){ $.ajax({ type: 'post' , url: '/list.html' , dataType : 'html' , success: function(data) { $("#listDiv").html(data); } });	})	})