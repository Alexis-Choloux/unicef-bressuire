<?php

namespace App\Classe;
use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '2ec50772d56d4fe713c4a6013c716338';
    private $api_key_secret = '16aa61229a0cd8d741be60c3eeedc54d';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact@alexis-choloux.com",
                        'Name' => "Unicef Bressuire"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 2738909,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
