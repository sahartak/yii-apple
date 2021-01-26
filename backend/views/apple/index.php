<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;
use common\models\Apple;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AppleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apples';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Apples', ['generate'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Remove All', ['delete-all'], ['class' => 'btn btn-danger']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'color',
                'value' => function(Apple $model) {
                    return "<span style='padding: 5px 15px; background-color: {$model->color}' title='{$model->color}'></span>";
                },
                'format' => 'html',
                'filter' => false
            ],
            [
                'attribute' => 'status',
                'value' => 'statusText',
                'filter' => Apple::$statuses
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ],
            ],
            [
                'attribute' => 'fall_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ],
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'size',
                'readonly' => function(Apple $model) {
                 return !$model->canEat || $model->size == 0;
                },
                'refreshGrid' => true,
                'editableOptions' => [
                    'options' => ['value' => ''],
                    'header' => 'Eat apple (percent)',
                    'inputType' => Editable::INPUT_TEXT,
                    'formOptions' => ['action' => ['eat']],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{fall}  {delete}',
                'buttons' => [
                    'fall' => function ($url, $model) {
                        if ($model->on_tree) {
                            return Html::a('<button class="btn btn-primary btn-sm">Fall</button>', $url,
                                ['title' => 'Fall', 'data-pjax' => '0']);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if ($model->size == 0) {
                            return Html::a('<button class="btn btn-danger btn-sm">Delete</button>', $url,
                                [
                                    'title' => 'Delete',
                                    'data-pjax' => '0',
                                    'data-confirm' => 'Are you sure you want to delete this item?',
                                    'data-method' => 'post'
                                ]);
                        }
                    }
                ],
            ]
        ],
    ]); ?>
</div>
