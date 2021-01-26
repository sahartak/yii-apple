<?php

namespace common\models;

use yii\base\Exception;


/**
 * This is the model class for table "{{%apples}}".
 *
 * @property int $id
 * @property string|null $color
 * @property int|null $on_tree
 * @property float|null $size
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $fall_at
 */
class Apple extends \yii\db\ActiveRecord
{
    const SPOIL_TIME = 5*3600; // spoiled after 5h

    const STATUS_ON_TREE = 'on_tree';
    const STATUS_FALLEN = 'fallen';
    const STATUS_SPOILED = 'spoiled';

    public static $statuses = [
        self::STATUS_ON_TREE => 'On tree',
        self::STATUS_FALLEN => 'Fallen',
        self::STATUS_SPOILED => 'Spoiled',
    ];

    /**
     * Colors array
     */
    public static $colors = ['red', 'green', 'orange', 'yellow'];

    /**
     * Set apple color value
     *
     * @param string|null $color
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($color = null, array $config = [])
    {
        if (!empty($color) && in_array($color, static::$colors)) {
            $this->color = $color;
        }

        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apples}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'fall_at'], 'integer'],
            [['size'], 'number'],
            [['color'], 'string'],
            ['created_at', 'default','value'=> rand(1, time())],
            ['size', 'default', 'value' => 1],
            ['on_tree', 'default', 'value' => true],
            [['on_tree'], 'filter', 'filter' => 'boolval'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'on_tree' => 'On Tree',
            'size' => 'Size',
            'created_at' => 'Created At',
            'fall_at' => 'Fall At',
        ];
    }

    /**
     * Set apple new size
     * @param float $percent
     * @return bool
     */
    function eat(float $percent) :string
    {
        if ($percent > 100) {
            $message = 'Percent is greater then 100';
        } elseif ($percent /100 > $this->size) {
            $message = 'Percent is greater then apple size';
        } elseif ($percent < 0) {
            $message = 'Percent is less then 0';
        } elseif (!$this->canEat) {
            $message = 'When hanging on a tree or spoiled, then you can’t eat.';
        }else {
            $this->size -= $percent / 100;
            $message = $this->save() ? "Successfully eaten {$percent}% an apple" : 'Error';
        }

        return $message;
    }

    /**
     * Check if can eat apple
     * @return bool
     */
    public function getCanEat() :bool
    {
        if(!$this->on_tree && $this->fall_at > time() - self::SPOIL_TIME){
            return true;
        }
        return false;
    }

    /**
     * Apple Fall to ground
     * @return bool
     */
    public function fallToGround() :bool
    {
        $this->fall_at = time();
        $this->on_tree = false;
        return $this->save();
    }

    /**
     * Apple status text
     * @return string
     */
    public function getStatusText() :string
    {
        if ($this->on_tree) {
            $status = self::STATUS_ON_TREE;
        }else {
            $status = ($this->fall_at > time() - self::SPOIL_TIME) ? self::STATUS_FALLEN : self::STATUS_SPOILED;
        }
        return self::$statuses[$status];
    }
}
