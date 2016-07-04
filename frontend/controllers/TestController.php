<?php
namespace frontend\controllers;

use common\models\database\TestValues;
use common\models\database\Test;
use common\models\database\Question;
use Yii;
use yii\web\Controller;
use yii\web\Session;

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
                $session->destroy();
                $session->open();
            }
        }
        
        $answers=[];

        $session->set('test_id', $id);
        $session->set('passed_questions', 0);
        $session->set('answewrs', $answers);
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
        if (!$session->isActive){
            $session->open();
        }

        //Check if not equal zero or empty
        if (!$number || !$session->get('test_id')){
             $session->close();
             return $this->redirect(['site/list-test']);
        }

        $passedQuestions=$session->get('passed_questions');

        //Check if $require number is more the
        if($passedQuestions+2<$number){
            $session->close();
            return $this->actionTest($passedQuestions+1);
        }
        
        $questionsQuantity = count(Test::findOne($session->get('test_id'))->question);

        //if isset post
        if(Yii::$app->request->post())
        {
            $answewrs = $session->get('answewrs');
            $answewrs[$number-1] = Yii::$app->request->post('answer');
            $answewrs = $session->set('answewrs', $answewrs);
            $session->set('passed_questions', $number-1);
        }

        //if test has been passed
        if($number>$questionsQuantity){
            return $this->redirect(['/payment/index', 'test' => $session->get('test_id')]);
        }

        $questionModel= Question::find()->where(['test_id'=> $session->get('test_id')])->orderBy('priority')->offset($number-1)->one();
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
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            case 'face':
                return $this->render('_face', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
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
                return $this->render('_hair', [ 
                        'model' => $model,
                        'currentQuestion' => $questionNumber,
                        'questionsQuantity'=>$questionsQuantity
                    ]);
                break;
            default:
                $model->delete();
                return $this->redirect(['index']);
                break;
        }
    }

}