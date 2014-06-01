<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

use Yii;
use yii\base\DynamicModel as BaseDynamicModel;
use yii\widgets\ActiveForm;

/**
 * Class DynamicModel
 * @package lagman\eav
 */
class DynamicModel extends BaseDynamicModel
{
    /** @var interfaces\EntityModel */
    public $entityModel;
    /** @var DynamicModelConfig */
    public $config;
    /** @var AttributeHandler[] */
    public $handlers;
    /** @var ActiveForm */
    public $activeForm;
    /** @var string[] */
    private $attributeLabels = [];

    /**
     * Constructor for creating form model from entity object
     *
     * @param interfaces\EntityModel $owner
     * @param DynamicModelConfig $config
     * @return static
     */
    public static function create($owner, $config)
    {
        $model = new DynamicModel();
        $model->entityModel = $owner;

        if (!$config instanceof DynamicModelConfig) {
            $config['class'] = DynamicModelConfig::className();
            $config = \Yii::createObject($config);
        }

        $model->config = $config;

        foreach ($owner->getRelation($config->entityAttributesRelation)->all() as $attribute) {
            $handler = AttributeHandler::load($model, $attribute);

            $model->defineAttribute($attribute->primaryKey, $handler->valueHandler->load());
            $model->defineAttributeLabel($attribute->primaryKey, $attribute->getAttribute('name'));

            $model->handlers[$attribute->primaryKey] = $handler;
        }

        return $model;
    }

    /**
     * Defines label for dynamic attribute
     *
     * @param integer $attribute
     * @param string $label
     */
    public function defineAttributeLabel($attribute, $label)
    {
        $this->attributeLabels[$attribute] = $label;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->attributeLabels;
    }

    public function save($runValidation = true, $attributes = null)
    {
        if ($runValidation && !$this->validate($attributes)) {
            Yii::info('Dynamic model data were not save due to validation error.', __METHOD__);
            return false;
        }

        $db = $this->entityModel->getDb();

        $transaction = $db->beginTransaction();
        try {
            foreach ($this->handlers as $handler) {
                $handler->valueHandler->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

//    /**
//     * @inheritdoc
//     */
//    public function scenarios()
//    {
//        return [
//            'default' => [],
//        ];
//    }
}