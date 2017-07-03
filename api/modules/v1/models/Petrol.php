<?php

namespace api\modules\v1\models;

use \yii\db\ActiveRecord;

/**
 * Country Model
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class Petrol extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'fuel_price_petrol';
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
