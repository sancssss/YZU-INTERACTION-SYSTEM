<?php

namespace app\controllers;
use app\models\student\CourseWithStudent;
use app\models\Course;
use app\models\StudentCourse;
use app\models\StudentCourseSearch;
use yii\data\ActiveDataProvider;
use Yii;

class StudentCourseController extends \yii\web\Controller
{
    /**
     * 首页显示已经选中的课程
     * @return type
     */
   public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CourseWithStudent::find()
                ->innerJoinWith('studentCourses')
                ->where(['student_number' => Yii::$app->user->getId(), 'verified' => 1]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 显示课程详情：作业/教师/课程
     */
    public function actionCourse($cid)
    {
        return $this->render('course', [
            'model' => $this->findModel($cid),
        ]);
    }
    
    /**
     * 申请课程页面：显示未被批准的申请列表和已被同意的课程
     */
    public function actionCoursesList()
    {
         $dataProvider = new ActiveDataProvider([
            'query' => CourseWithStudent::find()
                ->innerJoinWith('studentCourses')
                ->where(['student_number' => Yii::$app->user->getId()]),
        ]);

        return $this->render('course-list', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * 申请课程号为cid的课程
     * @param type $cid
     */
    public function actionRegisterCourse($cid)
    {
        $model = new StudentCourse();
        $model->student_number = Yii::$app->user->getId();
        $model->course_id = $cid;
        $model->verified = 0;
        if($model->save()){
            Yii::$app->session->setFlash('info', '申请成功!请等待批准.');
        }else{
             Yii::$app->session->setFlash('info', '申请失败.');
        }
        
        $this->redirect(['/student-course/find-course']);
    }
    
    /**
     * 取消申请课程号为cid的-待同意-课程
     * @param type $cid
     */
    public function actionCancelCourse($cid)
    {
        $model = StudentCourse::find(['course_id' => $cid, 'verified' => 0, 'student_number' => Yii::$app->user->getId()])->one();
        if(!$model->delete()){
             Yii::$app->session->setFlash('info', '取消失败.');
        }
        Yii::$app->session->setFlash('info', '取消成功.');
         $this->redirect(['/student-course/courses-list']);
    }

    /**
     * 查找课程
     */
    public function actionFindCourse()
    {
        $searchModel = new StudentCourseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('find-course', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
     /**
     * Finds the Course model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Course the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cid)
    {
        //查询时候用where实现条件就会使find中的条件失效
        if (($model = CourseWithStudent::find()->innerJoinWith('studentNumbers')->where(['student_number' => Yii::$app->user->getId(), 'teacher_course.course_id' => $cid])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
}