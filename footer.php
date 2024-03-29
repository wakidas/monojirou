<footer id="footer">
  Copyright <a href="index.php">MONOJIROU</a>. All Right Reserved.
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


 <script>
  $(function(){
    
//    広告アニメーション
//    
//      $('#ads').textillate({
//        loop: true,
//        autoStart: true
//      });
//    
    
    
//    フッターを最下部に固定
    var $ftr = $('#footer');
    if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
      $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
    }
//    メッセージ
    var $jsShowMsg = $('#js-show-msg');
    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
      $jsShowMsg.slideToggle('slow');
      setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
    }
    
//    画像ライブプレビュー
    var $dropArea = $('.area-drop');
    var $fileInput = $('.input-file');
    $dropArea.on('dragover', function(e){
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', '3px #ccc dashed');
    });
    $dropArea.on('dragleave', function(e){
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', 'none');
    });
    $fileInput.on('change', function(e){
      $dropArea.css('border', 'none');
      var file = this.files[0],         // 2 file配列にファイルが入ってます
          $img = $(this).siblings('.prev-img'), // 3 jQueryのsiblingsメソッドで兄弟のimg取得
          fileReader = new FileReader();  // 4 ファイルを読み込むFileReaderオブジェクト
      
//      5. 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
      fileReader.onload = function(event) {
//        読み込んだデータをimgに設定
        $img.attr('src', event.target.result).show();
      };
      
//       6. 画像読み込み
      fileReader.readAsDataURL(file);
      
    });
    
    //テキストエリアカウント
    var $countUp = $('#js-count'),
        $countView = $('#js-count-view');
    $countUp.on('keyup', function(e){
      $countView.html($(this).val().length);
    });
    
    //画像切り替え
    var $switchImgSubs = $('.js-switch-img-sub'),
        $switchImgMain = $('#js-switch-img-main');
    $switchImgSubs.on('click',function(e){
      $switchImgMain.attr('src',$(this).attr('src'));
    });
    
//    お気に入り登録・削除
    var $like,
        likeProductId;
    $like = $('.js-click-like') || null; //nullというのはnull値という値で、「変数の中身は空ですよ」と明示するために使う値.
//    もしjs-click-likeのDOMがとれなかったら"undefined"が入るので、 ||nullの条件も入れてやることでバリデーションを行なっている
    likeProductId = $like.data('productid') || null;
    //数値の０はfalseと判定されてしまう。product_idが０の場合もありえるので、０もtrueとする場合にはundefinedとnullを判定する
    if(likeProductId !== undefined && likeProductId !== null){
      $like.on('click',function(){
        var $this = $(this);
        $.ajax ({
          type: "POST",
          url: "ajaxLike.php",
          data: { productId : likeProductId}
        }).done(function( data ){
          console.log('Ajax Success');
          //クラス属性をtoggleでつけ外しする
          $this.toggleClass('active');
        }).fail(function( msg ) {
          console.log('Ajax Error');
        });
      });
    }
    
    
  });
   
   //現在のurl取得
   var url = window.location.href;
   //urlからファイル名（拡張子なし）取得
   var filename = url.match(".+/(.+?)\.[a-z]+([\?#;].*)?$")[1];
//   console.log(filename);

   if(filename !== "msg"){
   $('#'+ filename).addClass('active');
   }else{
    $('#msgInfo').addClass('active');
   }
   
  </script>