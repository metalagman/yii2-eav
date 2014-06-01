<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav\inputs;

use lagman\eav\AttributeHandler;

class TextInput extends AttributeHandler
{
    public function init()
    {
        parent::init();
        $this->owner->addRule($this->attributeModel->getPrimaryKey(), 'string', ['max' => 255]);
    }

    public function run()
    {
        return $this->owner->activeForm->field($this->owner, $this->attributeModel->primaryKey)
            ->textInput();
    }
}