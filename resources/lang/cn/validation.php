<?php
return [
	 	'username' => [
            'required' => '帳號不能空白',
            'unique' => '此帳號已經有人使用過'
        ],
        'password_confirmation' => [
            'same' => '輸入的密碼與上方欄位不相同',
            'required' => '此欄位不能空白'
        ],
        'password' => [
            'required' => '密碼不能空白'
        ],
        'name' => [
            'required' => '名字不能空白(輸入中文、英文皆可)'
        ]
];