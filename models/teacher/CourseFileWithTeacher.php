<?php

namespace app\models\teacher;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the model class for table "teacher_course".
 *
 * 
 */
class CourseFileWithTeacher extends \app\models\CourseFile
{
   
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = [
             'fileDownloadLink' => '下载链接'
        ];
        return array_merge(parent::attributeLabels(), $attributeLabels);
    }
    
    //TODO:未进行下载安全加密
    /**
     * 生成当前文件的action下载链接
     * @return string
     */
    public function getFileDownloadLink()
    {
        $url = Url::to(['teacher-course/download-course-file', 'fileid' => $this->file_id]);
        $options =  [];
        return Html::a('下载', $url, $options);
    }
    
}
