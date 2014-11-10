<?php

namespace app\modules\directory\controllers;

use yii\web\Controller;
use app\modules\directory\directoryModule;
use app\modules\directory\models\forms\TypeForm;
use app\modules\directory\models\forms\DataForm;
use app\modules\directory\models\db\Types;
use app\modules\directory\models\db\Data;
use app\modules\directory\helpers\ajaxJSONResponseHelper;
use app\modules\directory\helpers\modelErrorsToStringHelper;
use app\modules\directory\helpers\boolSaveHelper;

class EditController extends Controller {
    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }
    
    public function actionTypes(){
        if($cmd = \Yii::$app->request->get('cmd', false)) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            switch ($cmd) {
                case 'create':
                case 'update':
                    $form = new TypeForm;
                    $form->attributes = \Yii::$app->request->post('TypeForm');
                    if($form->validate()) {
                        if($cmd === 'create') {//create type
                            try {
                                $type = new Types;
                                $type->name = $form->name;
                                $type->type = $form->type;
                                $type->description = !isset($form->description) || strlen($form->description) === 0 ? null : $form->description;
                                $type->validate = !isset($form->validate) || strlen($form->validate) === 0 ? null : $form->validate;
                                $type->save();
                                return ajaxJSONResponseHelper::createResponse();
                            } catch (\Exception $ex) {
                                return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                            }
                        } else {//update type
                            try {
                                if(\Yii::$app->request->get('id', false)) {
                                    Types::updateAll([
                                        'name' => $form->name,
                                        'type' => $form->type,
                                        'description' => !isset($form->description) || strlen($form->description) === 0 ? null : $form->description,
                                        'validate' => !isset($form->validate) || strlen($form->validate) === 0 ? null : $form->validate,
                                            ], 'id = :id', [':id' => \Yii::$app->request->get('id')]);
                                } else {
                                    return ajaxJSONResponseHelper::createResponse(false, 
                                            directoryModule::t('search', 'Do not pass parameters <{parameter}>.', ['parameter' => 'id']));
                                }
                                return ajaxJSONResponseHelper::createResponse(true);
                            } catch (\Exception $ex) {
                                return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                            }
                        }
                    } else {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                modelErrorsToStringHelper::to($form->errors));
                    }
                    break;
                case 'delete':
                    if(\Yii::$app->request->get('id', false)) {
                        try {
                            Types::deleteAll('id=:id', [':id' => \Yii::$app->request->get('id')]);
                        } catch (\Exception $ex) {
                            return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                        }
                    } else {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                directoryModule::t('search', 'Do not pass parameters <{parametr}>.', ['parametr' => 'id']));
                    }
                    return ajaxJSONResponseHelper::createResponse(true);
            }
        }
        
        $model = new \app\modules\directory\models\search\TypesSearch();
        $model->attributes = \Yii::$app->request->get('TypesSearch');
        
        if(\Yii::$app->request->isPjax) {
            return $this->renderPartial('types_grid', ['dataModel' => $model]);
        }
        
        return $this->render('types', ['formModel' => new TypeForm, 'dataModel' => $model]);
    }
    
    public function actionData(){
        if($cmd = \Yii::$app->request->get('cmd', false)) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            switch ($cmd) {
                case 'create':
                case 'update':
                    $form = new DataForm;
                    $form->attributes = \Yii::$app->request->post('DataForm');
                    if($form->validate()) {
                        if($cmd === 'create') {
                            try {
                                $data = new Data;
                                $data->type_id = $form->typeId;
                                $ppp = Types::find()->where(['id' => $form->typeId])->one();
                                $data->visible = boolSaveHelper::boolean2string((boolean)$form->visible);
                                $data->description = !isset($form->description) || strlen($form->description) === 0 ? null : $form->description;
                                switch($ppp->type) {
                                    case 'string':
                                        $data->value = $form->value;
                                        break;
                                    case 'text':
                                        $data->text = $form->text;
                                        $data->value = empty($form->keywords) ? null : $form->keywords;
                                        break;
                                    case 'file':
                                        $data->value = empty($form->keywords) ? null : $form->keywords;
                                        $form->file->saveAs('uploads/'. mt_rand(0, mt_getrandmax()).'.'.$form->file->extension);
                                        break;
                                    case 'image':
                                        $data->value = empty($form->keywords) ? null : $form->keywords;
                                        $form->image->saveAs('uploads/'. mt_rand(0, mt_getrandmax()).'.'.$form->file->extension);
                                        break;
                                }
                                $data->save();
                                return ajaxJSONResponseHelper::createResponse();
                            } catch (\Exception $ex) {
                                return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                            }
                        } else {
                        }
                    } else {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                modelErrorsToStringHelper::to($form->errors));
                    }
                    break;
                case 'delete':
                    break;
            }
        }
        
        $model = new \app\modules\directory\models\search\DataSearch();
        $model->attributes = \Yii::$app->request->get('DataSearch');
        
        $typesData = new \app\modules\directory\models\search\TypesSearch();
        $typesData->pagination = 7;
        
        if(\Yii::$app->request->isPjax) {
            switch (\Yii::$app->request->get('_pjax')) {
                case '#typesCompactGridPjaxWidget':
                    return $this->renderPartial('types_compact_grid', ['typesDataModel' => $typesData]);
            }
        }
        
        return $this->render('data', ['formModel' => new DataForm, 'typesDataModel' => $typesData, 'dataModel' => $model]);
    }
    
    public function actionRecords(){
        return $this->render('records');
    }
    
    public function actionDirectories(){
        return $this->render('directories');
    }
    
    public function actionHierarchies(){
        return $this->render('hierarchies');
    }
}

