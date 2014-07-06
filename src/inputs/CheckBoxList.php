<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav\inputs;

use lagman\eav\AttributeHandler;
use yii\helpers\ArrayHelper;

class CheckBoxList extends AttributeHandler
{
    const VALUE_HANDLER_CLASS = '\lagman\eav\MultipleOptionsValueHandler';

    public function init()
    {
        parent::init();

        $this->owner->addRule($this->getAttributeName(), 'in', [
            'range' => $this->getOptions(),
            'allowArray' => true,
        ]);
    }

    public function run()
    {
        return $this->owner->activeForm->field($this->owner, $this->getAttributeName())
            ->checkboxList(
                ArrayHelper::map($this->attributeModel->getOptions()->asArray()->all(), 'id', 'value')
            );
    }
}