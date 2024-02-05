<?php

namespace app\controllers;

use app\models\Cart;
use app\models\CartSearch;
use app\models\Products;
use app\models\Users;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * CartController implements the CRUD actions for Cart model.
 */
class CartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }
    public function beforeAction($action)
    {
        if ($action->id == 'create' || $action->id == 'delete' || $action->id == 'update' || $action->id == 'checkout')
            $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Lists all Cart models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CartSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cart model.
     * @param int $cart_id Cart ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cart_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cart_id),
        ]);
    }

    /**
     * Creates a new Cart model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // public function actionCreate()
    // {
    //     $model = new Cart();

    //     if ($this->request->isPost) {
    //         if ($model->load($this->request->post()) && $model->save()) {
    //             return $this->redirect(['view', 'cart_id' => $model->cart_id]);
    //         }
    //     } else {
    //         $model->loadDefaultValues();
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }
    public function actionCreate()
    {
        $product_id = Yii::$app->request->post('product_id');
        $items = Yii::$app->request->post('count');
        $product = Products::findOne($product_id);
        if (!$product)
            return false;
        if ($product->stock_quantity > 0) {
            $product->save(false);
            $model = Cart::find()->where(['user_id' => Yii::$app->user->identity->id])->
                andWhere(['product_id' => $product_id])->one();
            if ($model) {
                $model->quantity += $items;
                $model->save();
                return $product->stock_quantity;
            }
            $model = new Cart();
            $model->user_id = Yii::$app->user->identity->id;
            $model->product_id = $product->product_id;
            $model->quantity = $items;
            if ($model->save(false))
                return $product->stock_quantity;
        }
        return 'false';
    }


    /**
     * Updates an existing Cart model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cart_id Cart ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $rawBody = Yii::$app->request->rawBody;
        $requestData = json_decode($rawBody, true);

        $cartItemId = isset($requestData['id']) ? $requestData['id'] : null;
        $quantityChange = isset($requestData['quantityChange']) ? $requestData['quantityChange'] : null;

        $cartItem = Cart::findOne($cartItemId);

        if ($cartItem && is_numeric($quantityChange)) {
            $product = $cartItem->product;
            $newQuantity = $cartItem->quantity + $quantityChange;

            if ($newQuantity > $product->stock_quantity) {
                return ['success' => false, 'error' => 'not enough stock'];
            } elseif ($newQuantity <= 0) {
                $cartItem->delete();
                return ['success' => false, 'error' => 'Item deleted'];
            } elseif ($newQuantity <= $product->stock_quantity && $newQuantity > 0) {
                $cartItem->quantity = $newQuantity;
                $cartItem->save();

                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'Invalid quantity or out of stock'];
            }
        } else {
            return ['success' => false, 'error' => 'Invalid request ' . $rawBody];
        }
    }



    /**
     * Deletes an existing Cart model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cart_id Cart ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cart_id)
    {
        $cartItem = Cart::findOne($cart_id);

        if ($cartItem) {
            $cartItem->delete();
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => true];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => false, 'error' => 'Item not found'];
        }
    }

    /**
     * Finds the Cart model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cart_id Cart ID
     * @return Cart the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cart_id)
    {
        if (($model = Cart::findOne(['cart_id' => $cart_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
