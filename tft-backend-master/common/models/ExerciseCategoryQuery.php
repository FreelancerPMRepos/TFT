<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[\common\models\ExerciseCategory]].
 *
 * @see \common\models\ExerciseCategory
 */
class ExerciseCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\ExerciseCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ExerciseCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
