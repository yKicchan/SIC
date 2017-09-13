<?php
class FlagController extends AppController{

  //路線表のis_late列を全て0にするメソッド
  public function initializeAction(){
    $sql = "UPDATE `route` SET `is_late` = 0";
    $model = new AppModel();

    if($model->query($sql)){
      echo "初期化成功";
    }else{
      echo "初期化失敗";
    }
  }
}
