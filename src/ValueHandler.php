<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

use lagman\eav\interfaces\ValueModel;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Class ValueHandler
 * @package lagman\eav
 *
 * @property ValueModel $valueModel
 */
abstract class ValueHandler
{
    /** @var AttributeHandler */
    public $attributeHandler;

    /**
     * @return ValueModel|static
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getValueModel()
    {
        $dynamicModel = $this->attributeHandler->owner;
        /** @var ValueModel $valueClass */
        $valueClass = $dynamicModel->config->valueClass;

        $valueModel = $valueClass::findOne([
            $dynamicModel->config->valueEntityLink => $dynamicModel->entityModel->getPrimaryKey(),
            $dynamicModel->config->valueAttributeLink => $this->attributeHandler->attributeModel->getPrimaryKey(),
        ]);

        if (!$valueModel instanceof ActiveRecord) {
            /** @var ValueModel $model */
            $valueModel = new $valueClass;
            $valueModel->{$dynamicModel->config->valueEntityLink} = $dynamicModel->entityModel->primaryKey;
            $valueModel->{$dynamicModel->config->valueAttributeLink} = $this->attributeHandler->attributeModel->primaryKey;
            if (!$valueModel->save())
                throw new Exception("Can't save value model");
        }

        return $valueModel;
    }

    abstract public function load();

    abstract public function save();
}