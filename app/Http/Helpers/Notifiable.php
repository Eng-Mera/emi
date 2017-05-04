<?php
/**
 * Notifiable Trait
 *
 * a wrapper for push notification service
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Http\Helpers;

use App\Exceptions\PushNotificationException;
use App\User;
use App\UserDevice;
use PushNotification;
use Sly\NotificationPusher\Adapter\Apns;
use ZendService\Apple\Apns\Response\Message;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

trait Notifiable
{
    /**
     * @param String $message
     * @param Array $devices = [
     *              'IOS' => [
     *                  'kfhlk89r38r3798ro3h2$$'
     *              ],
     *              'ANDROID' => [
     *                  '@@333987979%%33jkjhjk'
     *              ]
     *          ]
     * @param Array $params custom variables sent in payload to application
     */
    public function push($message, $devices, $params = [])
    {

        $message = PushNotification::Message($message, $params);

        // normalize to PushNotification::Device
        foreach ($devices as $type => $devices_by_type) {
            foreach ($devices_by_type as $key => $device) {
                if ($type == 'ANDROID') {
                    $devices[$type][$key] = $device;
                } else {
                    $devices[$type][$key] = PushNotification::Device($device);
                }
            }
        }

        $sync_collection = [];

        // loop different device types
        foreach ($devices as $device_type => $devices_array) {

            if ($device_type == 'ANDROID') {
                $this->androidHandler($devices_array, $message, $params);
            } else {

                $devices_collection = PushNotification::DeviceCollection($devices_array);
                $sync_collection[] = PushNotification::app($device_type)
                    ->to($devices_collection)
                    ->send($message);
            }
        }

        if (empty($sync_collection))
            return;

        // get response for each device push @todo
        foreach ($sync_collection as $sub_collection) {
            foreach ($sub_collection->pushManager as $push) {

                $response = $push->getAdapter()->getResponse();

                if (get_class($response) == Message::class && $response->getCode() === 0) {

                } elseif (get_class($sub_collection->adapter) == Apns::class) {

                    $errors = [
                        0 => 'No errors encountered',
                        1 => 'Processing error',
                        2 => 'Missing device token',
                        3 => 'Missing topic',
                        4 => 'Missing payload',
                        5 => 'Invalid token size',
                        6 => 'Invalid topic size',
                        7 => 'Invalid payload size',
                        8 => 'Invalid token',
                        255 => 'None (unknown)'
                    ];

                    $error = isset($errors[$response->getCode()]) ? @$errors[$response->getCode()] : $errors[255];

                    throw new PushNotificationException($error);

                } elseif ($response->getFailureCount() > 0) {
                    throw new PushNotificationException();
                }
            }
        }
    }

    /**
     * Special Dirty method added as a fast solution for android push notification
     *
     * @TODO Need to be enhanced
     *
     * @param $devices_array
     * @param $message
     * @param $params
     * @return bool
     * @throws PushNotificationException
     */
    public function androidHandler($devices_array, $message, $params)
    {
        $devices_tokens = array_values(array_unique($devices_array));

        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder(@$params['custom']['title']);
        $notificationBuilder->setBody(@$params['custom']['message'])
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($params['custom']);

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($devices_tokens, $option, $notification, $data);

        $this->deleteTokens($downstreamResponse->tokensToDelete());

        if ($downstreamResponse->numberSuccess() == count($devices_tokens)) {
            return true;
        }

        throw new PushNotificationException(trans('Notification hasn\'t be sent!'));
    }

    /**
     * Delete no longer working tokens.
     *
     * @param $tokens
     * @return bool
     */
    private function deleteTokens($tokens)
    {
        if (!is_array($tokens) || empty($tokens)) {
            return false;
        }

        UserDevice::whereIn('device_id', $tokens)->delete();

        return true;
    }

    /**
     * formats array as required
     *
     * @param User $user
     * @return array
     */
    public function formatUserDevicesArray(User $user)
    {
        $devices = [];

        // loop user devices
        foreach ($user->devices()->get() as $device) {
            $devices[$device->device_type][] = $device->device_id;
        }

        return $devices;
    }
}