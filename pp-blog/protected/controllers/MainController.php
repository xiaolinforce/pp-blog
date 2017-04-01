<?php

class MainController extends Controller
{
	
	public function actionIndex() {
		$this->renderPartial('index');
	}
	
	public function actionGetAllPost() {
		
		$sSqlStatement = "SELECT id, title, composer
			FROM `post`
			WHERE status = 1 AND deleted = 0
			ORDER BY created_date DESC";
		
		$aPosts = Yii::app()->db->createCommand($sSqlStatement)->queryAll();
		
		echo json_encode($aPosts);
	}
	
	public function actionSavePost() {
		
		$model = new Post();
		$model->title = $_POST['title'];
		$model->content = $_POST['content'];
		$model->composer = $_POST['composer'];
		$model->created_date = date('Y-m-d H:i:s');
		
		if( $model->save() )
			echo 1;
		else
			echo 0;
	}
	
	public function actionGetPostContent( $iId ) {
		
		$sSqlStatement = "SELECT title, content FROM `post` WHERE id = :id";
		
		$aContent = Yii::app()->db->createCommand($sSqlStatement)
			->bindValues([
					':id' => $iId,
			])
			->queryAll();
			
		echo json_encode($aContent[0]);
	}
	
}
