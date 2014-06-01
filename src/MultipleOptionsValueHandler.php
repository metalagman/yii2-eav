<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace lagman\eav;

use lagman\eav\interfaces\ValueModel;
use yii\db\ActiveRecord;

/**
 * Class MultipleOptionsValueHandler
 * @package lagman\eav
 */
class MultipleOptionsValueHandler extends ValueHandler
{
    /** @var AttributeHandler */
    public $attributeHandler;

    public function load()
    {
        $dynamicModel = $this->attributeHandler->owner;
        /** @var ValueModel $valueClass */
        $valueClass = $dynamicModel->config->valueClass;

        $models = $valueClass::findAll([
            $dynamicModel->config->valueEntityLink => $dynamicModel->entityModel->getPrimaryKey(),
            $dynamicModel->config->valueAttributeLink => $this->attributeHandler->attributeModel->getPrimaryKey(),
        ]);

        $values = [];
        foreach ($models as $model) {
            $values[] = $model->{$dynamicModel->config->valueOptionLink};
        }

        return $values;
    }

    public function save()
    {
        $dynamicModel = $this->attributeHandler->owner;
        /** @var ValueModel $valueClass */
        $valueClass = $dynamicModel->config->valueClass;

        $baseQuery = $valueClass::find([
            $dynamicModel->config->valueEntityLink => $dynamicModel->entityModel->getPrimaryKey(),
            $dynamicModel->config->valueAttributeLink => $this->attributeHandler->attributeModel->getPrimaryKey(),
        ]);

        $allOptions = [];
        $options = $this->attributeHandler->attributeModel->getRelation(
            $dynamicModel->config->attributeOptionsRelation
        )->all();
        foreach ($options as $option)
            $allOptions[] = $option->getPrimaryKey();

        $query = clone $baseQuery;
        $query->andWhere("{$dynamicModel->config->valueOptionLink} NOT IN (:options)", [
            'options' => implode(',', $allOptions),
        ]);
        $valueClass::deleteAll($query->where);

        // then we delete unselected options
        $selectedOptions = $dynamicModel->attributes[$this->attributeHandler->attributeModel->getPrimaryKey()];
        if (!is_array($selectedOptions))
            $selectedOptions = [];
        $deleteOptions = array_diff($allOptions, $selectedOptions);

        $query = clone $baseQuery;
        $query->andWhere("{$dynamicModel->config->valueOptionLink} IN (:options)", [
            'options' => implode(',', $deleteOptions),
        ]);
        $valueClass::deleteAll($query->where);

        // third we insert missing options
        foreach ($selectedOptions as $id) {
            $query = clone $baseQuery;
            $query->andWhere([$dynamicModel->config->valueOptionLink => $id]);

            $valueModel = $query->one();

            if (!$valueModel instanceof ActiveRecord) {
                /** @var ValueModel $valueModel */
                $valueModel = new $valueClass;
                $valueModel->{$dynamicModel->config->valueEntityLink} = $dynamicModel->entityModel->getPrimaryKey();
                $valueModel->{$dynamicModel->config->valueAttributeLink} = $this->attributeHandler->attributeModel->getPrimaryKey();
                $valueModel->{$dynamicModel->config->valueOptionLink} = $id;
                if (!$valueModel->save())
                    throw new \Exception("Can't save value model");
            }
        }
    }
}