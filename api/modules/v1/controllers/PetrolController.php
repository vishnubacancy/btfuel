<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class PetrolController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\Petrol';

    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex($state = '', $city = '', $date = '') {
        
        $date = (!empty($date))?date('Y-m-d', strtotime($date)):'';
        $search = ['state_code' => $state, 'city' => $city, 'updated' => $date];
        $search = array_filter($search);
        
        $modelClass = $this->modelClass;
        $model = $modelClass::find()->where($search)->all();


        //petrol
        $response = [];
        foreach ($model as $value) {
            $stateCode=$value->state_code;
            $response['petrol'][$value->type][] = ['state_code'=>$value->state_code,'city' => $value->city, 'price' => $value->price, 'change' => $value->change_diff,'updated'=>$value->updated];
        }
        
        if(!array_key_exists("iocl",$response['petrol'])){
            $modelIocl = $modelClass::find()->where(['state_code'=>$stateCode,'type'=>'iocl'])->one();
            $response['petrol']['iocl'][] = ['state_code'=>$modelIocl->state_code,'city' => $modelIocl->city, 'price' => $modelIocl->price, 'change' => $modelIocl->change_diff,'updated'=>$value->updated];
            
        }

        //diesel
        $model_diesel = \api\modules\v1\models\Diesel::find()->where($search)->all();
        foreach ($model_diesel as $value) {
            $stateCode=$value->state_code;
            $response['diesel'][$value->type][] = ['state_code'=>$value->state_code,'city' => $value->city, 'price' => $value->price, 'change' => $value->change_diff,'updated'=>$value->updated];
        }
        
        if(!array_key_exists("iocl",$response['diesel'])){
            $modelIocl = \api\modules\v1\models\Diesel::find()->where(['state_code'=>$stateCode,'type'=>'iocl'])->one();
            $response['diesel']['iocl'][] = ['state_code'=>$modelIocl->state_code,'city' => $modelIocl->city, 'price' => $modelIocl->price, 'change' => $modelIocl->change_diff,'updated'=>$value->updated];
        }

        if (empty($response))
            return ['success' => false, 'message' => 'not found'];

        return $response;
    }

}
