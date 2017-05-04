<?php
/**
 * Short description
 *
 * Long description for DeviceRepository.php (if any)...
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Http\Repositories;

use App\UserDevice;
use App\Exceptions\CustomValidationException;
use App\Exceptions\ORMException;

class DeviceRepository
{
    public function validateRepository($data)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'device_id' => 'required',
            'device_type' => 'required|in:' . UserDevice::DEVICE_IOS . ',' . UserDevice::DEVICE_ANDROID
        ];

        $validator = \Validator::make($data, $rules);

        if ($validator->fails())
            throw new CustomValidationException($validator);
    }

    public function saveUserDevice($data)
    {
        $result = UserDevice::create($data);

        if (!$result)
            throw new ORMException;

        return $result;
    }

    public function updateUserDevice(UserDevice $user_device, $data)
    {
        $result = $user_device->update($data);

        if (!$result)
            throw new ORMException;
    }

    /**
     * check if the combo(user_id+device_type) already exist in database
     *
     * @param $data
     * @return bool
     */
    public function exists($data)
    {
        $count = UserDevice::whereUserId($data['user_id'])
            ->whereDeviceType($data['device_type'])
            ->count();

        return $count > 0;
    }

    /**
     * get a single entry
     *
     * @param $data
     * @return mixed
     */
    public function getUserDevice($data)
    {
        return UserDevice::whereUserId($data['user_id'])
            ->whereDeviceType($data['device_type'])
            ->first();
    }

    /**
     * main function
     *
     * @param $data
     * @return mixed|static
     * @throws CustomValidationException
     * @throws ORMException
     */
    public function handleSaveDevice($data)
    {
        $this->validateRepository($data);
        if ($this->exists($data)) {
            $user_device = $this->getUserDevice($data);
            $this->updateUserDevice($user_device, $data);
            return $user_device;
        } else {
            return $this->saveUserDevice($data);
        }
    }
}