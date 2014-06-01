<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class EavBehavior
 * @package lagman\eav
 *
 * @mixin ActiveRecord
 * @property DynamicModel $eav;
 */
class EavBehavior extends Behavior
{
    /** @var array */
    public $config = [];

    protected $dynamicModel;

    /**
     * @return DynamicModel
     */
    public function getEav()
    {
        if (!$this->dynamicModel instanceof DynamicModel) {
            $this->dynamicModel = DynamicModel::create($this->owner, $this->config);
        }
        return $this->dynamicModel;
    }
}