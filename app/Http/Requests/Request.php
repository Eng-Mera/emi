<?php

namespace App\Http\Requests;


use App\User;
use Dingo\Api\Http\InternalRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class Request extends FormRequest
{
    protected $_currentUser;

    protected $_permissions = [
        'GET' => '',
        'POST' => '',
        'PUT' => '',
        'PATCH' => '',
        'DELETE' => ''
    ];

    protected $_object = [

    ];

    /**
     * Set Permissions for each request
     *
     * @return mixed
     */
    abstract function setObject();

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(\Illuminate\Http\Request $request)
    {
        $this->setObject();

        $currentUser = User::getCurrentUser();

        $action = (array)@explode('@', $this->route()->getAction()['uses']);

        if (!empty($action[1]) && $action[1] == 'index') {
            return $this->extendListValidation($request, $currentUser);
        }

        if (in_array($action[1], ['show'])) {
            return $this->extendReadValidation($request, $currentUser);
        }

        if ($currentUser && !$currentUser->isOwner($this->_object)) {
            return $this->extendedCRUDValidation($request, $currentUser);
        }

        return true;
    }

    /**
     * Extend these method in your Form Request class if you need to apply more validation in list action for specified validator.
     *
     * @param \Illuminate\Http\Request $request
     * @param $currentUser
     */
    protected function extendListValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        return true;
    }

    /**
     * Extend these method in your Form Request class if you need to apply more validation in list action for specified validator.
     *
     * @param \Illuminate\Http\Request $request
     * @param $currentUser
     */
    protected function extendReadValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        return true;
    }

    /**
     * Extend these method in your Form Request class if you need to apply more validation in CRUD actions.
     *
     * @param \Illuminate\Http\Request $request
     * @param $currentUser
     * @return bool
     */
    protected function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        return false;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return mixed
     */
    public function failedValidation(Validator $validator)
    {
        if ($this->container['request'] instanceof Request) {
            throw new BadRequestHttpException($validator->messages()->all()[0]);
        }

        parent::failedValidation($validator);

    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return mixed
     */
    protected function failedAuthorization()
    {
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('You aren\'t allowed to be here');

    }
}
