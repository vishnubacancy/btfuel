<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class StateController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\Petrol';

    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex() {

        $modelClass = $this->modelClass;
        $model = $modelClass::find()->groupBy('state')->orderBy(['state'=>SORT_ASC])->all();
        $response = [];
        foreach ($model as $value) {
            $response[] = ['state_code' => $value->state_code, 'state_name' => $value->state];
        }
        if (empty($response))
            return ['status' => 400, 'message' => 'Data not found.'];

        return ['status'=>200,'message'=>'Data found.','data'=>$response];
    }
    

}
