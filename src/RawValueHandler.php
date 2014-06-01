<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

/**
 * Class RawValueHandler
 * @package lagman\eav
 */
class RawValueHandler extends ValueHandler
{
    /**
     * @inheritdoc
     */
    public function load()
    {
        $valueModel = $this->getValueModel();
        return $valueModel->value;
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        $dynamicModel = $this->attributeHandler->owner;
        $valueModel = $this->getValueModel();

        $valueModel->value =
            $dynamicModel->attributes[$this->attributeHandler->attributeModel->getPrimaryKey()];
        if (!$valueModel->save())
            throw new \Exception("Can't save value model");
    }
}