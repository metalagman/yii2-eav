<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

use lagman\eav\interfaces\AttributeModel;
use yii\base\InvalidParamException;
use yii\base\Widget;

/**
 * Class AttributeHandler
 * @package lagman\eav
 */
class AttributeHandler extends Widget
{
    const VALUE_HANDLER_CLASS = '\lagman\eav\RawValueHandler';
    /** @var DynamicModel */
    public $owner;
    /** @var ValueHandler */
    public $valueHandler;
    /** @var AttributeModel */
    public $attributeModel;

    /**
     * @param DynamicModel $owner
     * @param AttributeModel $attributeModel
     * @return AttributeHandler
     * @throws \yii\base\InvalidConfigException
     */
    public static function load($owner, $attributeModel)
    {
        if (!class_exists($class = $attributeModel->type->handlerClass))
            throw new InvalidParamException('Unknown class: ' . $class);

        $handler = \Yii::createObject([
            'class' => $class,
            'owner' => $owner,
            'attributeModel' => $attributeModel
        ]);
        $handler->init();

        return $handler;
    }

    public function init()
    {
        $this->valueHandler = \Yii::createObject([
            'class' => static::VALUE_HANDLER_CLASS,
            'attributeHandler' => $this,
        ]);
    }

    public function getOptions()
    {
        $result = [];
        $options = $this->attributeModel->getRelation(
            $this->owner->config->attributeOptionsRelation
        )->all();
        foreach ($options as $option)
            $result[] = $option->getPrimaryKey();
        return $result;
    }
}