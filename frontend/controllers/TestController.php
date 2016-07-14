<?php
namespace frontend\controllers;

use common\models\database\TestValues;
use common\models\database\Test;
use common\models\database\Question;
use common\models\database\TestValuesMatrix;
use Yii;
use yii\web\Controller;
use yii\web\Session;
use yii\db\Query;
use yii\filters\VerbFilter;

/**
 * Site controller
*/
class TestController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js', ['depends' => 'yii\web\YiiAsset']);
        Yii::$app->view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/flexie/1.0.3/flexie.min.js', ['depends' => 'yii\web\YiiAsset']);
        Yii::$app->view->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bulma/0.0.16/css/bulma.min.css');
        Yii::$app->view->registerCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');

        $model = Test::find()->where(['sort' => 1])->all();
            return $this->render('_textimage', [
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionInittest($id)
    {  
        $session = Yii::$app->session;
        
        if ($session->isActive){
            if($session->get('test_id')==$id)
            {
                return $this->redirect(['test', 'number' => $session->get('passed_questions', 0)]);
            }
            else{
                if ($session->get('user_photo') != "") {
                    $delphoto = Yii::getAlias('@frontend') . '/web' . $session->get('user_photo');
                    unlink($delphoto);
                }
                $session->destroy();
                $session->open();
            }
        }

        $answers=[];
        $session->set('test_id', $id);
        $session->set('passed_questions', 0);
        $session->set('answewrs', $answers);
        $session->set('answewrsid', $answers);
        $session->close();

        return $this->redirect(['test', 'number' => 1]);
    }

    /**
    * Displays homepage.
    *
    * @return mixed
    */
    public function actionTest($number)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        //Check if not equal zero or empty
        if (!$number || !$session->get('test_id')) {
            $session->close();
            return $this->redirect(['site/list-test']);
        }

        $passedQuestions = $session->get('passed_questions');

        //Check if $require number is more the
        if ($passedQuestions + 2 < $number) {
            $session->close();
            return $this->actionTest($passedQuestions + 1);
        }
        $questionsQuantity = count(Test::findOne($session->get('test_id'))->question);

        //if isset post
        if (Yii::$app->request->post()) {
            $answewrs = $session->get('answewrs');
            $answewrsid=$session->get('answewrsid');
            $answewrs[$number - 1] = Yii::$app->request->post('answer');
            $answewrsid[Yii::$app->request->post('answewrid')] = $number - 1;
            $answewrs = $session->set('answewrs', $answewrs);
            $answewrs = $session->set('answewrsid', $answewrsid);
            $session->set('passed_questions', $number - 1);           
        }

        //if test has been passed
        if ($number > $questionsQuantity) {
            return $this->redirect(['/test/result', 'test' => $session->get('test_id')]);
        }

        $questionModel = Question::find()->where(['test_id' => $session->get('test_id')])->orderBy('priority')->offset($number - 1)->one();
        return $this->getQuestionSwith($questionModel, $number, $questionsQuantity);
    }

    /**
     * Switch form tampale when create new answers
     * @param object $model (model of Answer record)
     * @return mixed
     */
    protected function getQuestionSwith($model, $questionNumber, $questionsQuantity){
        Yii::$app->view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js', ['depends' => 'yii\web\YiiAsset']);
        Yii::$app->view->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/flexie/1.0.3/flexie.min.js', ['depends' => 'yii\web\YiiAsset']);
        Yii::$app->view->registerJsFile('js/jquery.cropit.js', ['depends' => 'frontend\assets\AppAsset']);
        
        Yii::$app->view->registerJsFile('js/main.js', ['depends' => 'frontend\assets\AppAsset']);
        
        Yii::$app->view->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bulma/0.0.16/css/bulma.min.css');
        Yii::$app->view->registerCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
        //meta
        Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $model->test->getAttribute('meta_description')]);
        Yii::$app->view->registerMetaTag(['name' => 'title', 'content' => $model->test->getAttribute('meta_title')]);
        Yii::$app->view->registerMetaTag(['name' => 'keys', 'content' => $model->test->getAttribute('meta_keys')]);

        switch ($model->questionType->getAttribute('slug')) {
            case 'sympleText':
                return $this->render('_sympletext', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'sympleImage':
                return $this->render('_sympleimage', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'textImage':
                return $this->render('_textimage', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'skinColor':
                return $this->render('_skincolor', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'aboutMeColour':
                return $this->render('_aboutmecolour', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'aboutMeTwo':
                return $this->render('_aboutmetwo', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'aboutMeOne':
                return $this->render('_aboutmeone', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'coloring':
                return $this->render('_coloring', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity,
                    ]);
                break;
            case 'face':
                return $this->render('_face', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity,
                    ]);
                break;
            case 'hair':
                return $this->render('_hair', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'eyes':
                return $this->render('_eyes', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'user_foto':
                return $this->render('_user_foto', [
                    'model' => $model,
                    'currentQuestion' => $questionNumber,
                    'questionsQuantity'=>$questionsQuantity,
                ]);
                break;
            default:
                $model->delete();
                return $this->redirect(['index']);
                break;
        }
    }

     /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionResult($test)
    {        
        $session = Yii::$app->session;

        if($session->get('test_id')!=$test)
        {
            return $this->redirect(['test', 'number' => $session->get('passed_questions', 0)]);
        }
        
        $testModel=$this->findModel($test);
        
        if($testModel->getAttribute('result_type_id')==1){
            $answewrs = $session->get('answewrs');
            $result = 0;
            foreach ($answewrs as $answer) {
                $result += $answer;
            }
            
            $query = new Query;
            $query->select('answer, query_values')->from('test_values')->where(['and', "`from`<=$result", "`to`>=$result"])->andWhere(['test_id' => $test]);
            $result = $query->one();

            return $this->render('result', [ 
                           'result' => $result,
                    ]);
        }
        else{
            $answewrs = $session->get('answewrs');
            $answewrsNumbersByid=$session->get('answewrsid');
            
            $query = new Query;
            $query->select(' * ')->from('test_values_matrix')->where(['test_id' => $test])->andWhere(['active_flag' => 1]);
            $result = $query->one();
            
            if(!$result){
                return $this->redirect(['list-test']);
            }

            //get matrix
            $matrix = unserialize($result['serialize']);
            $testValueId=$matrix[$answewrs[$answewrsNumbersByid[$result['question_vertical_id']]]][$answewrs[$answewrsNumbersByid[$result['question_horizontal_id']]]];

            if(!$testValueId){
                return $this->redirect(['list-test']);
            }
            
            $query = new Query;
            $query->select('answer, query_values')->from('test_values')->where(['id' => $testValueId]);
            $result = $query->one();

            return $this->render('result', [ 
                           'result' => $result,
                    ]);
        }
    }

     /**
     * Finds the Test model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Test the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Test::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

