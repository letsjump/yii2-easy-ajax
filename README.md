# yii2-easy-ajax
Relax your keyboard with EasyAjax

EasyAjax are a bunch of Yii functions that allows you to speed up your app coding minimizing the amount of code you need to write to interact with Bootstap UI and with javascript in general.
Notifies, modals, tabs, pjax-refreshes, form validations among others can now be set up and launched with only a line of code into the controller's action response.  

For example,  `EasyAjax::modal('My modal content')` opens a modal with "My modal content" as _content_, while `EasyAjax::reloadPjax(['#p0'])` reloads the content of pjax-container identified by `id="p0"`

EasyAjax further provides:

- Complete and ready-to-use **ajax CRUD**, thanks to the integrated _Gii module_
- Fully configurable functionalities both globally and action-specific
- Customizable HTML templates
- Extensible with your own functions

## Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```composer require --prefer-dist letsjump/yii2-easy-ajax```

or add

```"letsjump/yii2-easy-ajax": "~1.0.0"```

to the `require` section of your composer.json.

## Configuration

To use this extension, add the following code to your web application configuration (config/web.php):

```php
'components' => [
    'easyAjax' => [
        'class' => 'letsjump\easyAjax\EasyAjaxBase',
        'customOptions' => [
            /* refer to plugin documentation */
        ]
    ],
]
```

To use the integrated Gii Module, add the following code to your application configuration:

```php
if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', /** your IPs */],
        'generators' => [
            'crud' => [ 
                'class'     => 'letsjump\easyAjax\gii\crud\Generator',
                'templates' => [ //setting for out templates
                    'yii2-easy-ajax' => '@vendor/letsjump/yii2-easy-ajax/gii/crud/default',
                ]
            ],
        ],
    ];
}
```

Please note: The integrate Gii module allows you to generate the code for AJAX as well as the standard CRUD.

## Usage

### 1. Requests

EasyAjax requests are simple jQuery AJAX requests that need to refer to a controller action appropriately configured:

The easiest way to perform an EasyAjax request to a controller action is adding the `data-yea=1` attribute to the Html tag in charge of the action:

```html
<a data-yea="1" class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['controller/action-notify']) ?>">notify something</a>
```

or

```html
<button data-yea="1" data-form-id="friend-form" type="submit" class="btn btn-primary pull-right">Save</button>
```

> NOTE:
> 
> To explore any other Html attributes available and to know all the details, please refer to the guide

### 2 Responses

The controller actions interacting with EasyAjax should return a Json array, and they only need to contain one or more EasyAjax methods in their response array. 

In the following example, EasyAjax: 

```php
public function actionSaveMyModel()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // your model validation and save logic...

    return [
        \letsjump\easyAjax\EasyAjax::modalClose(),
        \letsjump\easyAjax\EasyAjax::reloadPjax(['#p0']),
        \letsjump\easyAjax\EasyAjax::notifySuccess('Your model has been saved')
    ];
}
```
- Closes a bootstrap modal opened in the UI
- Update the pjax-container `#p0`
- Shows a Bootstrap Notify informing the user of the successful operation

Here are the available methods in detail:

#### Modals

Le bootstrap modal delle EasyAjax sono completamente configurabili, e per instanziarle è possibile utilizzare il metodo: `EasyAjax::modal(content, title, *form_id*, *size*, *footer*, *options*)`

With EasyAjax, you can completely configure the Bootstrap Modals using the following: `EasyAjax::modal(content, title, *form_id*, *size*, *footer*, *options*)`

```php
public function actionBasicModal()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    return [
        \letsjump\easyAjax\EasyAjax::modal('This is the modal content', 'Modal title'),
    ];
}
```

It is also available a method to remotely close a Modal opened in the UI:

```php
public function actionCloseModal()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    return [
        \letsjump\easyAjax\EasyAjax::modalClose(),
    ];
}

```

 > You can edit the modal layout and many other settings. Please refer to the guide for all the available options.

---
#### Notify

Le EasyAjax Notify sono shortcuts per controllare il plugin Bootstrap Notify. Gli assets del plugin sono bundled con le EasyAjax anche se è possibile disabilitarne l'integrazione, nel caso la vostra applicazione l'abbia già integrato con plugin di terze parti.

EasyAjax Notify allows to control the Bootstrap Notify plugin. The plugin assets are bundled within EasyAjax. 

> You can disable the asset inclusion if it is already bundled in your application

To call a notify, you can use `\letsjump\easyAjax\EasyAjax::notify(message, *title*, *settings*)`.
In the `settings` parameter, you can specify the type of notification  displayed (_info, success, warning or danger_).

Per velocizzare la scrittura del codice sono anche disponibili alcuni metodi shortcut per le notifiche più comuni: `EasyAjax::notifyInfo(title)`, `EasyAjax::notifySuccess(title)`, `EasyAjax::notifyWarning(title)`, `EasyAjax::notifyDanger(title)`.

To speed up your coding, I have created some shortcut methods for the most common notification types: `EasyAjax::notifyInfo(title)`, `EasyAjax::notifySuccess(title)`, `EasyAjax::notifyWarning(title)`, `EasyAjax::notifyDanger(title)`.

Example:

```php
public function actionNotify()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    return [
        \letsjump\easyAjax\EasyAjax::notifyInfo('This is an Info Notify!')
    ];
}
```

> Refer to the guide for all the available options.

---
#### Pjax Reload

`EasyAjax::pjaxReload(['#myPjaxID0', '#myPjaxID1', ...])` method allows to reload one or more Pjax containers.

```php
public function actionPjaxReload()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    return [
        \letsjump\easyAjax\EasyAjax::reloadPjax(['#p0'])
    ];
}
```

---
#### Form validation

`EasyAjax::formValidation(['#my-form-id'=>ActiveForm::validate(MyModelClass)])` method allows to display the validation results for the specified form.

```php
public function actionValidateForm()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $contactForm                = new ContactForm();
    
    return [
        \letsjump\easyAjax\EasyAjax::formValidation(['#contact-form' => \yii\widgets\ActiveForm::validate($contactForm)])
    ];
}
``` 

---
#### Ajax CRUD with Gii

The bundled Gii generator within EasyAjax allows you to instantly create the Ajax CRUD controller and views. 

![Gii module](docs/images/gii.jpg)

This will generate a CRUD schema which is identical to the original one, and an added `actionModal` into the controller code, and its relative `myViewFolder/_modal.php` view in charge of creating or updating the data in an Ajax way.

You can switch the Ajax CRUD by simply setting the 'modal' parameter of the integrated ActionColumn method: 

```php
// myViewFolder/index.php 

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        // ... your gridview fields
        [
            'class' => 'letsjump\easyAjax\helpers\ActionColumn',
            'modal' => true
        ],
    ],
]); ?>
```

> BONUS: Deleting a record doesn't imply a complete page refresh, therefore the GridView pagination will not be affected.

Refer to the guide for all the available options

---

#### Ajax tabs

Documentation in progress

---
#### Content replace
 
 `ContentReplace` replaces the content of a specific Html tag with the code sent by the EasyAjax response. It uses the `jQuery.html()` standard function. 
 
```php
public function actionContentReplace()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    return [
        \letsjump\easyAjax\EasyAjax::contentReplace(['#time-placeholder' => date('d/m/Y H:i:s')])
    ];
}
```

---
#### Redirect


---
#### Confirms

You can use the confirm method to call a Javascript `confirm()` from a controller action. By clicking on the "OK" button it will fire an Ajax request to the action specified in the `url` parameter.
```php
public function actionConfirm()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    return [
        \letsjump\easyAjax\EasyAjax::confirm('This will fire a growl. Ok?', \yii\helpers\Url::to(['site/notify']))
    ];
}
```
## Contributing

## Credits

## License
