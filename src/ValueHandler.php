<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

use yii\db\ActiveRecord;

/**
 * Class ValueHandler
 * @package lagman\eav
 *
 * @property ActiveRecord $valueModel
 */
abstract class ValueHandler
{
    /** @var AttributeHandler */
    public $attributeHandler;

    /**
     * @return ActiveRecord
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getValueModel()
    {
        $dynamicModel = $this->attributeHandler->owner;
        /** @var ActiveRecord $valueClass */
        $valueClass = $dynamicModel->valueClass;

        $valueModel = $valueClass::findOne([
            'entityId' => $dynamicModel->entityModel->getPrimaryKey(),
            'attributeId' => $this->attributeHandler->attributeModel->getPrimaryKey(),
        ]);

        if (!$valueModel instanceof ActiveRecord) {
            /** @var ActiveRecord $valueModel */
            $valueModel = new $valueClass;
            $valueModel->entityId = $dynamicModel->entityModel->getPrimaryKey();
            $valueModel->attributeId = $this->attributeHandler->attributeModel->getPrimaryKey();
            if (!$valueModel->save())
                throw new \Exception("Can't save value model");
        }

        return $valueModel;
    }

    abstract public function load();

    abstract public function save();
}