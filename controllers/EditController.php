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
use yii\web\UploadedFile;
use app\modules\directory\models\db\views\LowerData;
use app\modules\directory\helpers\dataGridCellViewHelper;
use app\modules\directory\models\forms\RecordForm;
use app\modules\directory\models\forms\RecordDataItemForm;
use app\modules\directory\models\forms\DirectoryForm;
use app\modules\directory\models\forms\DirectoryItemForm;
use app\modules\directory\models\db\Records;
use app\modules\directory\models\db\RecordsData;
use app\modules\directory\models\db\Directories;
use app\modules\directory\models\db\RecordsDirectory;

class EditController extends Controller {
    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }
    
    public function actionTypes() {
        if(($cmd = \Yii::$app->request->get('cmd', false)) && \Yii::$app->request->isAjax) {
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
                                if($type->save()) {
                                    $ct = $type->attributes;
                                    $ct['typeDiaplay'] = directoryModule::ht('edit', $ct['type']);
                                    return ajaxJSONResponseHelper::createResponse(true, $ct);
                                } else {
                                    return ajaxJSONResponseHelper::createResponse(false, directoryModule::ht('edit', 'Error saving type in the database.'));
                                }
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
                                    if(\Yii::$app->request->get('return', false)) {
                                        $ct = [];
                                        $ct['id'] = \Yii::$app->request->get('id');
                                        $ct['name'] = $form->name;
                                        $ct['type'] = $form->type;
                                        $ct['description'] = isset($form->description) ? $form->description : null;
                                        $ct['validate'] = isset($form->validate) ? $form->validate : null;
                                        return ajaxJSONResponseHelper::createResponse(true, 'ok', $ct);
                                    }
                                } else {
                                    return ajaxJSONResponseHelper::createResponse(false, 
                                            directoryModule::ht('search', 'Do not pass parameters <{parameter}>.', ['parameter' => 'id']));
                                }
                                return ajaxJSONResponseHelper::createResponse();
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
                            if(\Yii::$app->request->get('confirm', 'no') == 'yes') {
                                Types::deleteAll('id=:id', [':id' => \Yii::$app->request->get('id')]);
                            } else {
                                $dataCount = Data::find()->where('type_id=:id', 
                                                [':id' => \Yii::$app->request->get('id')])->with('type')->count();
                                if($dataCount > 0) {
                                    return ajaxJSONResponseHelper::createResponse(true, 'query', 
                                            ['message' => directoryModule::ht('edit', 'With the type of associated data. When you delete a type type, they will be removed. Remove?'),
                                                'id' => \Yii::$app->request->get('id', false)]);
                                } else {
                                    Types::deleteAll('id=:id', [':id' => \Yii::$app->request->get('id')]);
                                }
                            }
                        } catch (\Exception $ex) {
                            return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                        }
                    } else {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                directoryModule::ht('search', 'Do not pass parameters <{parametr}>.', ['parametr' => 'id']));
                    }
                    return ajaxJSONResponseHelper::createResponse(true);
                default:
                    return ajaxJSONResponseHelper::createResponse(false, directoryModule::ht('search', 'Unknown command.'));
            }
        }
        
        $model = new \app\modules\directory\models\search\TypesSearch();
        $model->attributes = \Yii::$app->request->get('TypesSearch');
        
        if(\Yii::$app->request->isPjax) {
            $control = \Yii::$app->request->get('_pjax');

            if($control == '#typesGridPjaxWidget') {
                return $this->renderPartial('types_grid', ['dataModel' => $model]);
            } elseif(preg_match('/#typesCompactGridPjaxWidget(?P<uid>[\d]+)/', $control, $matches) > 0) {
                return $this->renderPartial('dialogs/types-compact-grid', ['typesDataModel' => $model, 'uid' => $matches['uid']]);
            } else {
                throw new \yii\web\HttpException(404, 'The requested Item could not be found.');
            }
        }
        
        return $this->render('types', ['dataModel' => $model]);
    }
    
    public function actionData() {
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
                                $dataType = Types::find()->where(['id' => $form->typeId])->one();
                                $data->visible = boolSaveHelper::boolean2string((boolean)$form->visible);
                                $data->description = !isset($form->description) || strlen($form->description) === 0 ? null : $form->description;
                                switch($dataType->type) {
                                    case 'string':
                                        $data->value = $form->value;
                                        break;
                                    case 'text':
                                        $data->text = $form->text;
                                        $data->value = empty($form->keywords) ? null : $form->keywords;
                                        break;
                                    case 'file':
                                        $data->value = empty($form->keywords) ? null : $form->keywords;
                                        $form->file = UploadedFile::getInstance($form, 'file');
                                        $file =  '/file_'.mt_rand(0, mt_getrandmax()).'.'.$form->file->extension;
                                        if($form->file->saveAs(\Yii::getAlias(directoryModule::$SETTING['uploadPathLocal']).$file)) {
                                            $data->text = \Yii::getAlias(directoryModule::$SETTING['uploadPathWeb']).$file;
                                        } else {
                                            throw new \Exception(directoryModule::ht('edit', 'Error when saving a file.'));
                                        }
                                        break;
                                    case 'image':
                                        $data->value = empty($form->keywords) ? null : $form->keywords;
                                        $form->image = UploadedFile::getInstance($form, 'image');
                                        $file =  '/image_'.mt_rand(0, mt_getrandmax()).'.'.$form->image->extension;
                                        if($form->image->saveAs(\Yii::getAlias(directoryModule::$SETTING['uploadPathLocal']).$file)) {
                                            $data->text = \Yii::getAlias(directoryModule::$SETTING['uploadPathWeb']).$file;
                                        } else {
                                            throw new \Exception(directoryModule::ht('edit', 'Error when saving a file.'));
                                        }
                                        break;
                                }
                                $data->save();
                                $newData = LowerData::find()->where('id=:id', [':id' => $data->id])->one()->attributes;
                                $newData['valueDisplay'] = dataGridCellViewHelper::getValueDataString($newData['type_type'], $newData['original_value'], $newData['original_text']);
                                return ajaxJSONResponseHelper::createResponse(true, $newData);
                            } catch (\Exception $ex) {
                                return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                            }
                        } else {
                            try {
                                if(\Yii::$app->request->get('id', false)) {
                                    $attributes = [
                                        'type_id' => $form->typeId,
                                        'visible' => boolSaveHelper::boolean2string((boolean)$form->visible),
                                        'description' => isset($form->description) ? $form->description : null
                                    ];
                                    
                                    $transaction = \Yii::$app->db->beginTransaction();
                                    
                                    try {
                                        $dataType = Types::find()->where(['id' => $form->typeId])->one();
                                        $dataCurr = Data::find()
                                                ->where([Data::tableName().'.id' => \Yii::$app->request->get('id')])
                                                ->innerJoinWith('type', true)
                                                ->one();
                                        $delFileFunc = function($filePath) {
                                            $path = str_replace(
                                                    \Yii::getAlias(directoryModule::$SETTING['uploadPathWeb']), 
                                                    \Yii::getAlias(directoryModule::$SETTING['uploadPathLocal']), $filePath);
                                            if(file_exists($path)) {
                                                unlink($path);
                                            }
                                        };
                                        
                                        switch($dataType->type) {
                                            case 'string':
                                                $attributes['value'] = $form->value;
                                                $attributes['text'] = null;
                                                if(($dataCurr->type->type == 'file') || ($dataCurr->type->type == 'image')) {
                                                    $delFileFunc($dataCurr->text);
                                                }
                                                break;
                                            case 'text':
                                                $attributes['text'] = $form->text;
                                                $attributes['value'] = empty($form->keywords) ? null : $form->keywords;
                                                if(($dataCurr->type->type == 'file') || ($dataCurr->type->type == 'image')) {
                                                    $delFileFunc($dataCurr->text);
                                                }
                                                break;
                                            case 'file':
                                                $attributes['value'] = empty($form->keywords) ? null : $form->keywords;
                                                if($form->replase == 'no') {
                                                    if($dataCurr->type->type != 'file') {
                                                        throw new \Exception(directoryModule::ht('edit', 'Error when saving a file.'));
                                                    }
                                                } elseif($form->replase == 'change') {
                                                    $form->file = UploadedFile::getInstance($form, 'file');
                                                    $file =  '/file_'.mt_rand(0, mt_getrandmax()).'.'.$form->file->extension;
                                                    if($form->file->saveAs(\Yii::getAlias(directoryModule::$SETTING['uploadPathLocal']).$file)) {
                                                        $attributes['text'] = \Yii::getAlias(directoryModule::$SETTING['uploadPathWeb']).$file;
                                                    } else {
                                                        throw new \Exception(directoryModule::ht('edit', 'Error when saving a file.'));
                                                    }
                                                    if(($dataCurr->type->type == 'image') || 
                                                            ($dataCurr->type->type == 'file')) {
                                                        $delFileFunc($dataCurr->text);
                                                    }
                                                } else {
                                                    throw new \Exception(directoryModule::ht('edit', 'Error when saving a file.'));
                                                }
                                                break;
                                            case 'image':
                                                $attributes['value'] = empty($form->keywords) ? null : $form->keywords;
                                                if($form->replase == 'no') {
                                                    if($dataCurr->type->type != 'image') {
                                                        throw new \Exception(directoryModule::ht('edit', 'Error when saving a file.'));
                                                    }
                                                } elseif($form->replase == 'change') {
                                                    $form->image = UploadedFile::getInstance($form, 'image');
                                                    $file =  '/image_'.mt_rand(0, mt_getrandmax()).'.'.$form->image->extension;
                                                    if($form->image->saveAs(\Yii::getAlias(directoryModule::$SETTING['uploadPathLocal']).$file)) {
                                                        $attributes['text'] = \Yii::getAlias(directoryModule::$SETTING['uploadPathWeb']).$file;
                                                    } else {
                                                        throw new \Exception(directoryModule::ht('edit', 'Error when saving a file.'));
                                                    }
                                                    if(($dataCurr->type->type == 'image') || 
                                                            ($dataCurr->type->type == 'file')) {
                                                        $delFileFunc($dataCurr->text);
                                                    }
                                                } else {
                                                    throw new \Exception(directoryModule::ht('edit', 'Error when saving a file.'));
                                                }
                                                break;
                                        }
                                        
                                        Data::updateAll($attributes, 'id = :id', [':id' => \Yii::$app->request->get('id')]);
                                        $transaction->commit();
                                    } catch (\Exception $ex) {
                                        $transaction->rollBack();
                                        throw $ex;
                                    }
                                    
                                    return ajaxJSONResponseHelper::createResponse();
                                } else {
                                    return ajaxJSONResponseHelper::createResponse(false, 
                                            directoryModule::ht('search', 'Do not pass parameters <{parameter}>.', ['parameter' => 'id']));
                                }
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
                            $delete = function($id) {
                                $dataCurr = Data::find()
                                                ->where([Data::tableName().'.id' => $id])
                                                ->innerJoinWith('type', true)
                                                ->one();
                                if(($dataCurr->type->type == 'file') || 
                                        ($dataCurr->type->type == 'image')) {
                                    $path = str_replace(
                                            \Yii::getAlias(directoryModule::$SETTING['uploadPathWeb']), 
                                            \Yii::getAlias(directoryModule::$SETTING['uploadPathLocal']), $dataCurr->text);
                                    if(file_exists($path)) {
                                        unlink($path);
                                    }
                                }
                                
                                Data::deleteAll('id=:id', [':id' => $id]);
                            };
                            
                            $transaction = \Yii::$app->db->beginTransaction();
                            
                            try {
                                if(\Yii::$app->request->get('confirm', 'no') == 'yes') {
                                    $delete(\Yii::$app->request->get('id'));
                                } else {
                                    $dataCount = Data::find()->where('type_id=:id', 
                                                    [':id' => \Yii::$app->request->get('id')])->with('recordsdata')->count();
                                    if($dataCount > 0) {
                                        return ajaxJSONResponseHelper::createResponse(true, 'query', 
                                                ['message' => directoryModule::ht('edit', 'With the type of associated data. When you delete a type type, they will be removed. Remove?'),
                                                    'id' => \Yii::$app->request->get('id', false)]);
                                    } else {
                                        $delete(\Yii::$app->request->get('id'));
                                    }
                                }
                                $transaction->commit();
                            } catch (\Exception $ex) {
                                $transaction->rollBack();
                                throw $ex;
                            }
                        } catch (\Exception $ex) {
                            return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                        }
                    } else {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                directoryModule::ht('search', 'Do not pass parameters <{parametr}>.', ['parametr' => 'id']));
                    }
                    return ajaxJSONResponseHelper::createResponse(true);
                default:
                    return ajaxJSONResponseHelper::createResponse(false, directoryModule::ht('search', 'Unknown command.'));
            }
        }
        
        $model = new \app\modules\directory\models\search\DataSearch();
        $model->attributes = \Yii::$app->request->get('DataSearch');
        
        if(\Yii::$app->request->isPjax) {
            $control = \Yii::$app->request->get('_pjax');

            if($control == '#dataGridPjaxWidget') {
                return $this->renderPartial('data_grid', ['dataModel' => $model]);
            } elseif(preg_match('/#dataCompactGridPjaxWidget(?P<uid>[\d]+)/', $control, $matches) > 0) {
                return $this->renderPartial('dialogs/data-compact-grid', ['typesDataModel' => $model, 'uid' => $matches['uid']]);
            } else {
                throw new \yii\web\HttpException(404, 'The requested Item could not be found.');
            }
        }
        
        return $this->render('data', ['dataModel' => $model]);
    }
    
    public function actionRecords() {
        if(($cmd = \Yii::$app->request->get('cmd', false)) && \Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            switch($cmd) {
                case 'create':
                case 'update':
                    $recordForm = new RecordForm;
                    $recordForm->attributes = \Yii::$app->request->post('RecordForm');
                    if(!$recordForm->validate()) {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                modelErrorsToStringHelper::to($recordForm->errors));
                    }
                    
                    $counter = 0;
                    $recordFormItems = [];
                    $formItems = \Yii::$app->request->post('RecordDataItemForm');
                    if(isset($formItems) && is_array($formItems) && (count($formItems) > 0)) {
                        foreach ($formItems as $formItem) {
                            $recordFormItem = new RecordDataItemForm;
                            $recordFormItem->attributes = $formItem;
                            ++$counter;
                            if(!$recordFormItem->validate()) {
                                return ajaxJSONResponseHelper::createResponse(false, 
                                        modelErrorsToStringHelper::to($recordFormItem->errors, 
                                                '['.directoryModule::ht('edit', 'string').' - '.$counter.']'));
                            }
                            $recordFormItems[] = $recordFormItem;
                        }
                    }
                    
                    $counter = 0;
                    $directoriesFormItems = [];
                    $formDirectoriesItems = \Yii::$app->request->post('DirectoryItemForm');
                    if(isset($formDirectoriesItems) && is_array($formDirectoriesItems) && (count($formDirectoriesItems) > 0)) {
                        foreach ($formDirectoriesItems as $formDirectoriesItem) {
                            $directoriesFormItem = new DirectoryItemForm;
                            $directoriesFormItem->attributes = $formDirectoriesItem;
                            ++$counter;
                            if(!$directoriesFormItem->validate()) {
                                return ajaxJSONResponseHelper::createResponse(false, 
                                        modelErrorsToStringHelper::to($directoriesFormItem->errors, 
                                                '['.directoryModule::ht('edit', 'string').' - '.$counter.']'));
                            }
                            $directoriesFormItems[] = $directoriesFormItem;
                        }
                    }
                    
                    $result = [];
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        if($cmd === 'create') {
                            $newRecord = new Records;
                            $newRecord->visible = boolSaveHelper::boolean2string((boolean)$recordForm->visible);
                            if(!$newRecord->save()) {
                                return ajaxJSONResponseHelper::createResponse(false, 
                                        directoryModule::ht('edit', 'Error saving record in the database.'));
                            }
                            
                            $result = $newRecord->attributes;
                            
                        } else {
                            if(\Yii::$app->request->get('id', false)) {
                                Records::updateAll(['visible' => boolSaveHelper::boolean2string((boolean)$recordForm->visible)], 
                                        'id=:id', [':id' => \Yii::$app->request->get('id')]);
                                
                                $result['id'] = \Yii::$app->request->get('id');
                                $result['visible'] = \Yii::$app->request->get('id');
                                
                                RecordsData::deleteAll('record_id=:record_id', [':record_id' => $result['id']]);
                                RecordsDirectory::deleteAll('record_id=:record_id', [':record_id' => $result['id']]);
                            } else {
                                return ajaxJSONResponseHelper::createResponse(false, 
                                        directoryModule::ht('search', 'Do not pass parameters <{parameter}>.', ['parameter' => 'id']));
                            }
                        }

                        $result['items'] = [];
                        $result['directories'] = [];
                        
                        foreach ($recordFormItems as $recordItem) {
                            $newLinkData = new RecordsData;
                            $newLinkData->record_id = $result['id'];
                            $newLinkData->data_id = $recordItem->dataId;
                            $newLinkData->visible = boolSaveHelper::boolean2string((boolean)$recordItem->visible);
                            $newLinkData->position = $recordItem->position;
                            $newLinkData->sub_position = $recordItem->subPosition;
                            
                            if(!$newLinkData->save()) {
                                return ajaxJSONResponseHelper::createResponse(false, 
                                        directoryModule::ht('edit', 'Error saving record element in the database.'));
                            }
                            
                            $result['items'][] = $newLinkData->attributes;
                        }
                        
                        foreach ($directoriesFormItems as $directoriesFormItem) {
                            $newLinkDirectory = new RecordsDirectory;
                            $newLinkDirectory->record_id = $result['id'];
                            $newLinkDirectory->directory_id = $directoriesFormItem->directoryId;
                            $newLinkDirectory->visible = boolSaveHelper::boolean2string((boolean)$directoriesFormItem->visible);
                            
                            if(!$newLinkDirectory->save()) {
                                return ajaxJSONResponseHelper::createResponse(false, 
                                        directoryModule::ht('edit', 'Error saving directory element in the database.'));
                            }
                            
                            $result['directories'][] = $newLinkDirectory->attributes;
                        }
                        
                        $transaction->commit();
                        return ajaxJSONResponseHelper::createResponse(true, $result);
                    } catch (\Exception $ex) {
                        $transaction->rollBack();
                        return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                    }
                    break;
                case 'delete':
                    if(\Yii::$app->request->get('id', false)) {
                        try {
                            Records::deleteAll('id=:id', [':id' => \Yii::$app->request->get('id')]);
                        } catch (\Exception $ex) {
                            return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                        }
                    } else {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                directoryModule::ht('search', 'Do not pass parameters <{parametr}>.', ['parametr' => 'id']));
                    }
                    return ajaxJSONResponseHelper::createResponse(true);
                default:
                    return ajaxJSONResponseHelper::createResponse(false, directoryModule::ht('search', 'Unknown command.'));
            }
        }
        
        
        $model = new \app\modules\directory\models\search\RecordsSearch();
        $model->attributes = \Yii::$app->request->get('RecordsSearch');
        
        
        return $this->render('records', ['dataModel' => $model]);
    }
    
    public function actionDirectories() {
        if(($cmd = \Yii::$app->request->get('cmd', false)) && \Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            switch($cmd) {
                case 'create':
                case 'update':
                    $directoryForm = new DirectoryForm;
                    $directoryForm->attributes = \Yii::$app->request->post('DirectoryForm');
                    if(!$directoryForm->validate()) {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                modelErrorsToStringHelper::to($directoryForm->errors));
                    }
                    
                    try {
                        if($cmd === 'create') {
                            $directory = new Directories;
                            $directory->name = $directoryForm->name;
                            $directory->description = !isset($directoryForm->description) || strlen($directoryForm->description) === 0 ? null : $directoryForm->description;
                            $directory->visible = boolSaveHelper::boolean2string((boolean)$directoryForm->visible);

                            if(!$directory->save()) {
                                return ajaxJSONResponseHelper::createResponse(false, 
                                        directoryModule::ht('edit', 'Error saving directory in the database.'));
                            }
                            
                            return ajaxJSONResponseHelper::createResponse(true, $directory->attributes);
                        } else {
                            if(\Yii::$app->request->get('id', false)) {
                                Directories::updateAll([
                                    'name' => $directoryForm->name,
                                    'description' => !isset($directoryForm->description) || strlen($directoryForm->description) === 0 ? null : $directoryForm->description,
                                    'visible' => boolSaveHelper::boolean2string((boolean)$directoryForm->visible)
                                        ], 'id = :id', [':id' => \Yii::$app->request->get('id')]);
                                if(\Yii::$app->request->get('return', false)) {
                                    $ct = [];
                                    $ct['id'] = \Yii::$app->request->get('id');
                                    $ct['name'] = $directoryForm->name;
                                    $ct['visible'] = $directoryForm->visible;
                                    $ct['description'] = isset($form->description) ? $form->description : null;
                                    return ajaxJSONResponseHelper::createResponse(true, 'ok', $ct);
                                }
                            } else {
                                return ajaxJSONResponseHelper::createResponse(false, 
                                        directoryModule::ht('search', 'Do not pass parameters <{parameter}>.', ['parameter' => 'id']));
                            }
                            
                            return ajaxJSONResponseHelper::createResponse();
                        }
                    } catch (\Exception $ex) {
                        return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                    }
                    
                    break;
                case 'delete':
                    if(\Yii::$app->request->get('id', false)) {
                        try {
                            Directories::deleteAll('id=:id', [':id' => \Yii::$app->request->get('id')]);
                        } catch (\Exception $ex) {
                            return ajaxJSONResponseHelper::createResponse(false, $ex->getMessage());
                        }
                    } else {
                        return ajaxJSONResponseHelper::createResponse(false, 
                                directoryModule::ht('search', 'Do not pass parameters <{parametr}>.', ['parametr' => 'id']));
                    }
                    return ajaxJSONResponseHelper::createResponse(true);
                default:
                    return ajaxJSONResponseHelper::createResponse(false, directoryModule::ht('search', 'Unknown command.'));
            }
        }
        
        $model = new \app\modules\directory\models\search\DirectoriesSearch();
        $model->attributes = \Yii::$app->request->get('DirectoriesSearch');
        
        if(\Yii::$app->request->isPjax) {
            $control = \Yii::$app->request->get('_pjax');

            if($control == '#directoriesGridPjaxWidget') {
                return $this->renderPartial('directories_grid', ['dataModel' => $model]);
            } elseif(preg_match('/#directoriesCompactGridPjaxWidget(?P<uid>[\d]+)/', $control, $matches) > 0) {
                return $this->renderPartial('dialogs/directory-compact-grid', ['directoryDataModel' => $model, 'uid' => $matches['uid']]);
            } else {
                throw new \yii\web\HttpException(404, 'The requested Item could not be found.');
            }
        }
        
        return $this->render('directories', ['dataModel' => $model]);
    }
    
    public function actionHierarchies() {
        return $this->render('hierarchies');
    }

    public function actionStatistic() {
        return $this->render('statistic');
    }
}

