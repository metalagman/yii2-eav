<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

/**
 * Class OptionValueHandler
 * @package lagman\eav
 */
class OptionValueHandler extends ValueHandler
{
    /**
     * @inheritdoc
     */
    public function load()
    {
        $valueModel = $this->getValueModel();
        return $valueModel->optionId;
    }

    /** @var AttributeHandler */
    public $attributeHandler;

    /**
     * @inheritdoc
     */
    public function save()
    {
        $dynamicModel = $this->attributeHandler->owner;
        $valueModel = $this->getValueModel();

        $valueModel->optionId =
            $dynamicModel->attributes[$this->attributeHandler->attributeModel->getPrimaryKey()];
        if (!$valueModel->save())
            throw new \Exception("Can't save value model");
    }
}