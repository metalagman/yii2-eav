<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class EavBehavior
 * @package lagman\eav
 *
 * @mixin ActiveRecord
 * @property DynamicModel $eav;
 * @property ActiveRecord $owner
 */
class EavBehavior extends Behavior
{
    /** @var array */
    public $valueClass;

    protected $dynamicModel;

    public function init()
    {
        assert(isset($this->valueClass));
    }

    /**
     * @return DynamicModel
     */
    public function getEav()
    {
        if (!$this->dynamicModel instanceof DynamicModel) {
            $this->dynamicModel = DynamicModel::create([
                'entityModel' => $this->owner,
                'valueClass' => $this->valueClass,
            ]);
        }
        return $this->dynamicModel;
    }
}