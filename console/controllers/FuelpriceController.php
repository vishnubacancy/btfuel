<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\ActiveRecord;
use console\models\Fuelpricepetrol;
use console\models\Fuelpricediesel;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class FuelpriceController extends Controller {

    public function actionIndex() {

        Yii::$app->cache->flush();

        $stateData = Yii::$app->params['globalState'];

        //petrol
        foreach ($stateData as $state_code => $state) {

            $data = $this->sendcurl('p', $state_code);
            
            if($data == '0') {continue;}
            $data = json_decode($data);

            $updated = date('Y-m-d', strtotime($data->updated));
            $state = $data->state;
            foreach ($data->prices as $type => $array) {
                $type = $type;
                foreach ($array as $value) {
                    $city = $value->city;
                    $price = $value->price;
                    $change_diff = $value->change;

                    $model = new Fuelpricepetrol();
                    $model->price = $price;
                    $model->state = $state;
                    $model->state_code = $state_code;
                    $model->city = $city;
                    $model->type = $type;
                    $model->change_diff = $change_diff;
                    $model->updated = $updated;
                    $command = Yii::$app->db->createCommand();

                    $command->insert('fuel_price_petrol', array(
                        'price' => $price,
                        'state' => $state,
                        'state_code' => $state_code,
                        'city' => $city,
                        'type' => $type,
                        'change_diff' => $change_diff,
                        'updated' => $updated,
                            )
                    )->execute();
                }
            }
        }
        
        //diesel
        foreach ($stateData as $state_code => $state) {

            $data = $this->sendcurl('d', $state_code);
            
            if($data == '0') {continue;}
            $data = json_decode($data);

            $updated = date('Y-m-d', strtotime($data->updated));
            $state = $data->state;
            foreach ($data->prices as $type => $array) {
                $type = $type;
                foreach ($array as $value) {
                    $city = $value->city;
                    $price = $value->price;
                    $change_diff = $value->change;

                    $model = new Fuelpricediesel();
                    $model->price = $price;
                    $model->state = $state;
                    $model->state_code = $state_code;
                    $model->city = $city;
                    $model->type = $type;
                    $model->change_diff = $change_diff;
                    $model->updated = $updated;
                    $command = Yii::$app->db->createCommand();

                    $command->insert('fuel_price_diesel', array(
                        'price' => $price,
                        'state' => $state,
                        'state_code' => $state_code,
                        'city' => $city,
                        'type' => $type,
                        'change_diff' => $change_diff,
                        'updated' => $updated,
                            )
                    )->execute();
                }
            }
        }
    }

    public function sendcurl($fuel, $state) {
        $url = Yii::$app->params['fuelPriceUrl'];
        $key = Yii::$app->params['fuelPriceKey'];


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"fuel\": \"{$fuel}\",\n  \"state\": \"{$state}\"\n}",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 97f79b55-2007-b1ea-0fad-1fcdfc36a1eb",
                "x-mashape-key: {$key}"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 0;
        } else {
            return $response;
        }
    }

}
