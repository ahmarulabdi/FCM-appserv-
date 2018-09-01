<?php
/**
 *
 *
 * Created by PhpStorm.
 * User: rndmjck
 * Date: 01/09/18
 * Time: 6:01
 *
 * library ini digunakan untuk mengirim request
 * pembuatan notifikasi ke Firebase Cloud Message via web
 * ke perangkat android yang terkoneksi dengan Firebase Console
 *
 *
 *
 * penggunaan dengan topic
 * $fcm = new FCMService();
 * $fcm->setFieldToWithTopic("foobar");
 * $fcm->setFieldNotification(["title" => "foo" , "body" => "bar" ]);
 * $fcm->send();
 *
 * penggunaan per device token
 * $fcm = new FCMService();
 * $fcm->setFieldToWithKey("foobar");
 * $fcm->setFieldNotification(["title" => "foo" , "body" => "bar" ]);
 * $fcm->send();
 */

class FCMServiceToAndroid
{

    private $path_to_fcm = "https://fcm.googleapis.com/fcm/send";
    private $firebase_server_key = "AAAAJvTs4h4:APA91bFjUccuPGPv79VUZscxOqQ7jsVSki7yH8724b6wGus4T1SGOtYLu6RqsomGIqOSWbtYX9TXyL58PgVGlU_cDHJPZ2-U8Q_IVZUMBws17IShJYv2Ycbs5i4sqJ57hqL7thcvOudJ";
    private $headers;
    private $field;

    public function __construct()
    {
        $this->headers = ["Authorization:key=" . $this->firebase_server_key, "Content-Type:application/json"];
    }

    /**
     * kirim notif dengan topic
     * @param $topic  topics fcm
     * @return array berupa isi array assoc untuk field to
     */
    public function setToWithTopic($toWithTopic)
    {
        $this->field['to'] = "/topics/" . $toWithTopic;
    }

    /**
     *  kirim notif dengan key token per device
     * @param $key key fcm per device
     * @return array assoc untuk field to
     */
    public function setToWithKey($toWithKey)
    {
        $this->field['to'] = $toWithKey;
    }

    /**
     * isi dari notifcation di android
     * @param $title title notificatino
     * @param $body message notification
     * @return array assoc untuk field notification
     */
    public function setNotification($title, $body)
    {
        $this->field['notification'] = ["title" => $title, "body" => $body];
    }


    /**
     * kirim request ke firebase untuk kirim notifikasi ke android device menggunakan CURL
     * @param $to
     * @param $notification
     * @return array
     */
    public
    function send()
    {


        /**
         * @var JSON STRING $payload
         * set field menjadi json string
         */
        $payload = json_encode($this->field);
        /**
         * @var CURL $curl_session
         *memulai sesi curl
         */
        $curl_session = curl_init();


        /**
         * setup CURL untuk firebase cloud message
         */
        curl_setopt($curl_session, CURLOPT_URL, $this->path_to_fcm);
        curl_setopt($curl_session, CURLOPT_POST, true);
        curl_setopt($curl_session, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

        /**
         * hasil dari curl
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
