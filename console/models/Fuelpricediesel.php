<?php

namespace console\models;

use yii\base\Model;

/**
 * Country Model
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class Fuelpricediesel extends Model {

    public $price;
    public $state;
    public $state_code;
    public $city;
    public $type;
    public $change_diff;
    public $updated;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'fuel_price_diesel';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey() {
        return ['id'];
    }

    /**
     * Define rules for validation
     */
    public function rules() {
        return [
                //  [['code', 'name', 'population'], 'required']
        ];
    }

}
