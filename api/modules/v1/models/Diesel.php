<?php

namespace api\modules\v1\models;

use \yii\db\ActiveRecord;

/**
 * Country Model
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class Diesel extends ActiveRecord {

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
            //[['code', 'name', 'population'], 'required']
        ];
    }

}
