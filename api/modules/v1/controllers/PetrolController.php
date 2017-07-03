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

        $date = (!empty($date)) ? date('Y-m-d', strtotime($date)) : '';
        $search = ['state_code' => $state, 'city' => $city, 'updated' => $date];
        $search = array_filter($search);

        $modelClass = $this->modelClass;
        $model = $modelClass::find()->where($search)->all();
        $response = [];
        
        
        //petrol
        foreach ($model as $value) {
            $stateCode = $value->state_code;
            $response['petrol'][] = ['state_code' => $value->state_code, 'city' => $value->city, 'price' => $value->price, 'change' => $value->change_diff, 'updated' => $value->updated, 'company' => $value->type];
        }

        if (!$this->search_array("iocl", $response) && !empty($response)) {
            $modelIocl = $modelClass::find()->where(['state_code' => $stateCode, 'type' => 'iocl'])->one();
            $response['petrol'][] = ['state_code' => $modelIocl->state_code, 'city' => $modelIocl->city, 'price' => $modelIocl->price, 'change' => $modelIocl->change_diff, 'updated' => $value->updated, 'company' => 'iocl'];
        }

        //diesel
        $model_diesel = \api\modules\v1\models\Diesel::find()->where($search)->all();
        foreach ($model_diesel as $value) {
            $stateCode = $value->state_code;
            $response['diesel'][] = ['state_code' => $value->state_code, 'city' => $value->city, 'price' => $value->price, 'change' => $value->change_diff, 'updated' => $value->updated, 'company' => $value->type];
        }

        if (!$this->search_array("iocl", $response) && !empty($response)) {
            $modelIocl = \api\modules\v1\models\Diesel::find()->where(['state_code' => $stateCode, 'type' => 'iocl'])->one();
            $response['diesel'][] = ['state_code' => $modelIocl->state_code, 'city' => $modelIocl->city, 'price' => $modelIocl->price, 'change' => $modelIocl->change_diff, 'updated' => $value->updated, 'company' => 'iocl'];
        }

        if (empty($response))
            return ['status' => 404, 'message' => 'Data not found.'];

        return ['status'=>200,'message'=>'Data found.','data'=>$response];
    }

    public function search_array($needle, $haystack) {
        if (in_array($needle, $haystack)) {
            return true;
        }
        foreach ($haystack as $element) {
            if (is_array($element) && $this->search_array($needle, $element))
                return true;
        }
        return false;
    }

}
