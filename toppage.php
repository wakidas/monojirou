<?php 
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　」');
debug('」」」」」」」」」」」」」」」」」」」」」」」」」」」」」」」」');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// GETパラメータを取得
//----------------------------------
// カレントページ

$currentPageNum = (!empty($_GET['p'])) ? (int)$_GET['p'] : 1; //デフォルトは１ページ目
//カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
//パラメータに不正な値が入っているかチェック
if(!is_int($currentPageNum)){
  error_log('エラー発生:指定ページに不正な値が入りました。');
  header("Location:toppage.php");//トップページへ
}
//表示件数
$listSpan = 20;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //1ページ目なら（１−１）*20 = 0、２ページ目なら（２−１）＊２０　＝　２０
//DBから商品データを取得
$dbProductData = getProductList($currentMinNum, $category, $sort);
//DBからカテゴリデータを取得
$dbCategoryData = getCategory();
//debug('DBデータ：'.print_r($dbFormData,true));
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<!--ヘッダータグ-->
<?php 
$siteTitle ='HOME';
require('head.php');
?>

  <body class="page-toppage page-2colum">


  <!--  ヘッダー-->
  <?php 
  require('header.php');
  ?>

    <div class="msg">
      <p id="js-show-msg" style="display:none;" class="msg-slide">
        <?php echo getSessionFlash('msg_success'); ?>
      </p>
    </div>

    <!--  広告タブ-->
    <?php 
    require('ads.php');
    ?>

    <!--メニュータブ-->
    <?php 
    require('menuTab.php');
    ?>

    <!-- メインコンテンツ -->
  <div id="contents" class="site-width">
    
<!--   カテゴリー別-->
   <section id="top-category">
   <h2>ジャンルで選ぶ</h2>
    <div class="category-list">
     
      <div class="list <?php if(getFormData('c_id',true) == 0 ){ echo 'active'; } ?>">
        <a href="toppage.php#top-category" style="font-weight:bold;font-size:20px;">　全商品一覧</a>
      </div>
      
      <?php 
      foreach($dbCategoryData as $key => $val){
      ?>
      <div class="list <?php if(getFormData('c_id',true) == $val['id'] ){ echo 'active'; } ?>">
        <a href="toppage.php<?php echo '?c_id='.$val['id'] ?>#top-category">　<?php echo $val['name']?></a>
      </div>
      <?php 
      }
      ?>
      
    </div>
    <div class="underline"></div>
   </section>
    
<!--    サイドバー-->
    <section id="sidebar">
      <form name="" method="get">
        <h1 class="title">カテゴリー</h1>
        <div class="selectbox">
          <span class="icn_select"></span>
            <select name="c_id" id="">
              <option value="0" <?php if(getFormData('c_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
              <?php 
                foreach($dbCategoryData as $key => $val){
              ?>
              <option value="<?php echo $val['id'] ?>" <?php if(getFormData('c_id',true) == $val['id'] ){ echo 'selected'; } ?> >
                <?php echo $val['name']; ?>
              </option>
              <?php 
                }
              ?>
            </select>
        </div>
        <h1 class="title">表示順</h1>
        <div class="selectbox">
          <span class="icn_select"></span>
            <select name="sort">
              <option value="0" <?php if(getFormData('sort',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
              <option value="1" <?php if(getFormData('sort',true) == 1 ){ echo 'selected'; } ?> >金額が安い順</option>
              <option value="2" <?php if(getFormData('sort',true) == 2 ){ echo 'selected'; } ?> >金額が高い順</option>
            </select>
        </div>
        <input type="submit" value="検索">
      </form>
      
    </section>

<!--   Main-->
     <section id="main">
       <div class="search-title">
         <div class="search-left">
           <span class="total-num"><?php echo sanitize($dbProductData['total']); ?></span>件の商品が見つかりました。
         </div>
         <div class="search-right">
           <span class="num"><?php echo (!empty($dbProductData['data'])) ? $currentMinNum+1 : 0; ?></span> - <span class="num"><?php echo $currentMinNum+count($dbProductData['data']); ?></span>件 / <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>件中
         </div>
       </div>
       <div class="panel-list">
        
         <?php 
         foreach($dbProductData['data'] as $key => $val):

         ?>
          
           <a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel">
           
            
             <div class="panel-head">
               <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['name']); ?>">
             </div>
             <div class="panel-body">
               <p class="panel-title"><?php echo mb_substr(sanitize($val['name']),0,10); ?><?php if((mb_strlen(sanitize($val['name']))) >= 10){echo '...'; } ?> <span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
             </div>
           </a>
           <?php 
            endforeach;
         ?>
         
       </div>
       
       <?php 
       pagination($currentPageNum, $dbProductData['total_page']); 
       ?>
       
     </section>
 

  </div>







  <!-- footer -->
  <?php
  require('footer.php'); 
  ?>
  <script>

  </script>


</body>
</html>
