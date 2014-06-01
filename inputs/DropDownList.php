<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav\inputs;

use lagman\eav\AttributeHandler;
use yii\helpers\ArrayHelper;

class DropDownList extends AttributeHandler
{
    public function init()
    {
        parent::init();

        $this->owner->addRule($this->attributeModel->getPrimaryKey(), 'in', [
            'range' => $this->getOptions(),
        ]);
    }

    public function run()
    {
        return $this->owner->activeForm->field($this->owner, $this->attributeModel->getPrimaryKey())
            ->dropDownList(
                ArrayHelper::map($this->attributeModel->getOptions()->asArray()->all(), 'id', 'value')
            );
    }
}