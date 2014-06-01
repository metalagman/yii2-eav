<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

use yii\base\Object;

/**
 * Class DynamicModelConfig
 * @package lagman\eav
 */
class DynamicModelConfig extends Object
{
    public $entityAttributesRelation = 'eavAttributes';
    /** @var string Class to use for storing values */
    public $valueClass;
    /** @var string Field to use in value-entity relation */
    public $valueEntityLink = 'entityId';
    /** @var string Field to use in value-attribute relation */
    public $valueAttributeLink = 'attributeId';
    /** @var string Field used to store value 'as-is' */
    public $valueRawDataField = 'value';
    /** @var string Field used to store value as option */
    public $valueOptionLink = 'optionId';
    /** @var string Relation used to obtain options of the attribute */
    public $attributeOptionsRelation = 'options';
}