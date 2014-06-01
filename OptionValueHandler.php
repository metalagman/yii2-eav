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
    /** @var AttributeHandler */
    public $attributeHandler;

    public function load()
    {
        $dynamicModel = $this->attributeHandler->owner;
        $valueModel = $this->getValueModel();
        return $valueModel->{$dynamicModel->config->valueOptionLink};
    }

    public function save()
    {
        $dynamicModel = $this->attributeHandler->owner;
        $valueModel = $this->getValueModel();

        $valueModel->{$dynamicModel->config->valueOptionLink} =
            $dynamicModel->attributes[$this->attributeHandler->attributeModel->getPrimaryKey()];
        if (!$valueModel->save())
            throw new \Exception("Can't save value model");
    }
}