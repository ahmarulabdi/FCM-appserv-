<?php
/**
 *
 *
 * Created by PhpStorm.
 * User: rndmjck
 * Date: 01/09/18
 * Time: 6:01
 *
 * with subscribe to topic
 * $fcm = new FCMService();
 * $fcm->setFieldToWithTopic("foobar");
 * $fcm->setFieldNotification(["title" => "foo" , "body" => "bar" ]);
 * $fcm->send();
 *
 * with token
 * $fcm = new FCMService();
 * $fcm->setFieldToWithKey("foobar");
 * $fcm->setFieldNotification(["title" => "foo" , "body" => "bar" ]);
 * $fcm->send();
 */

class FCMAppServerService
{

    private $path_to_fcm = "https://fcm.googleapis.com/fcm/send";
    private $firebase_server_key = "AAAAJvTs4h##################################OudJ";
    private $headers;
    private $field;

    public function __construct()
    {
        $this->headers = ["Authorization:key=" . $this->firebase_server_key, "Content-Type:application/json"];
    }

    /**
     * sending notification by topic
     * @param $topic  topics fcm
     * @return array is an array assoc for field to
     */
    public function setToWithTopic($toWithTopic)
    {
        $this->field['to'] = "/topics/" . $toWithTopic;
    }

    /**
     *  sending notification with key
     * @param $key key fcm 
     * @return array assoc for field to
     */
    public function setToWithKey($toWithKey)
    {
        $this->field['to'] = $toWithKey;
    }

    /**
     * body of notifcation
     * @param $title title notificatino
     * @param $body body notification
     * @return array assoc for field notification
     */
    public function setNotification($title, $body)
    {
        $this->field['notification'] = ["title" => $title, "body" => $body];
    }


    /**
     * make request to firebase cloud messaging service for sending notification to app client through CURL
     * @param $to
     * @param $notification
     * @return array
     */
    public function send()
    {


        /**
         * @var JSON STRING $payload
         * set field to json
         */
        $payload = json_encode($this->field);
        /**
         * @var CURL $curl_session
         * start  curl session
         */
        $curl_session = curl_init();


        /**
         * setup CURL for firebase cloud messaging
         */
        curl_setopt($curl_session, CURLOPT_URL, $this->path_to_fcm);
        curl_setopt($curl_session, CURLOPT_POST, true);
        curl_setopt($curl_session, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

        /**
         * result of curl
         * @var CURL $curl_result
         */
        $curl_result = curl_exec($curl_session);
        curl_close($curl_session);

        $result = [
            "field" => $this->field,
            "curl_result" => $curl_result,
        ];

        return $result;


    }


}
