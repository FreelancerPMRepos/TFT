<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[\common\models\Exercise]].
 *
 * @see \common\models\Exercise
 */
class ExerciseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Exercise[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Exercise|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
