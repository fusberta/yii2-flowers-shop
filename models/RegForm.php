<?php

namespace app\models;

use Yii;

use app\models\Users;

class RegForm extends Users
{
    public $confirm_password;
    public $agree;
    public function rules()
    {
        return [
            [
                ['username', 'email', 'password', 'confirm_password', 'agree'],
                'required'
            ],
            ['first_name','string','max' => 50],
            [
                ['first_name'], 
                'match', 
                'pattern' => '/^[А-Яа-яЁё\s\-]+$/u', 
                'message' => 'Допустимы только кириллические символы, пробелы и тире.',
            ],
            [
                ['last_name'], 
                'match', 
                'pattern' => '/^[А-Яа-яЁё\s\-]+$/u', 
                'message' => 'Допустимы только кириллические символы, пробелы и тире.'],
            [
                ['username'],
                'match',
                'pattern' => '/^[A-Za-z0-9]{5,}$/',
                'message' => 'Используйте минимум 5 латинских букв или цифр'
            ],
            [
                ['password'],
                'match',
                'pattern' => '/^[A-Za-z0-9]{5,}$/',
                'message' => 'Используйте минимум 5 латинских букв или цифр'
            ],
            [['email'], 'email'],
            [
                ['confirm_password'],
                'compare',
                'compareAttribute' => 'password'
            ],
            [['email', 'username'], 'unique'],
            [
                ['agree'],
                'compare',
                'compareValue' => true,
                'message' => ''
            ],
            [['username', 'email', 'password'], 'string', 'max' => 255],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'username' => 'Имя пользователя',
            'email' => 'Адрес электронной почты',
            'password' => 'Пароль',
            'confirm_password' => 'Повторите пароль',
            'agree' => 'Подтвердите согласие на обработку персональных данных',
        ];
    }
}
?>