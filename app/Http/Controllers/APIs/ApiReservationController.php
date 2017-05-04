<?php
/**
 * Short description
 *
 * Long description for ApiReservationController.php (if any)...
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

namespace App\Http\Controllers\APIs;

use App\Events\ReservationChangeWasRequested;
use App\Events\ReservationWasApproved;
use App\Events\ReservationWasCancelled;
use App\Events\ReservationWasRejected;
use App\Events\ReservationWasRescheduled;
use App\Exceptions\CartException;
use App\Exceptions\CustomValidationException;
use App\Exceptions\ORMException;
use App\Http\Helpers\CartTrait;
use App\Http\Helpers\QueryableTrait;
use App\Http\Helpers\ReservableTrait;
use App\Http\Repositories\ReservationRepository;
use App\Jobs\sendExportedWalkInEmail;
use App\Jobs\sendReservationApprovedNotifications;
use App\Jobs\sendReservationChangedNotifications;
use App\Jobs\sendReservationPaidNotifications;
use App\Jobs\sendReservationPendingNotifications;
use App\Jobs\sendReservationRejectedNotifications;
use App\Reservation;
use App\Restaurant;
use App\Role;
use App\Transformers\ReservationTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use Event;
use App\Coupon;
use Illuminate\Validation\ValidationException;
use App\Http\Helpers\ReservationSearchQuery;
use Validator;

/**
 * Class ApiReservationController
 * @package App\Http\Controllers\APIs
 * @Resource("Reservations", uri="/api/v1/reservation")
 */
class ApiReservationController extends Controller
{
    /**
     * Include Helpers
     */
    use Helpers;

    /**
     * Include common cart functionality
     */
    use CartTrait;

    /**
     * Include common database functionality
     */
    use QueryableTrait;

    /**
     * Reservation Related Leverage
     */
    use ReservableTrait;

    /**
     * Reservation Search Query
     */
    use ReservationSearchQuery;

    /**
     * @var CouponRepository
     */
    private $reservation_repository;

    public function __construct()
    {
        parent::__construct();
        $this->reservation_repository = new ReservationRepository();
    }

    public function index(Requests\ReservationRequest $request)
    {
        $fields = $request->only([
            'per_page',
            'restaurant_id',
            'page',
            'user_id',
            'status',
            'note',
            'number_of_people',
            'time',
            'date',
            'total',
            'coupon_id',
            'discount',
            'amount',
            'referrer_code',
            'advance_payment',
            'created_at',
            'updated_at'
        ]);

        $restaurant = User::getManagersRestaurant();

        if ($restaurant) {
            $fields['restaurant_id'] = $restaurant->id;
        }

        try {
            $models = $this->reservation_repository->listReservations($fields);

        } catch (CustomValidationException $ex) {

            return $this->response->errorBadRequest($ex->getValidator()->errors());
        }

//        return $this->response->paginator($models, new ReservationTransformer);
        return $this->response->created('', $models);
    }

    /**
     *
     * @return \Dingo\Api\Http\Response|void
     * @Versions({"v1"})
     * @Post("/")
     * @Parameters({
     *          @Parameter("user_id",
     *                     type="Integer",
     *                     required=true,
     *                     description="",
     *                     default=null),
     *          @Parameter("restaurant_id",
     *                     type="Integer",
     *                     required=true,
     *                     description="",
     *                     default=null),
     *          @Parameter("date",
     *                     type="Date",
     *                     required=true,
     *                     description="",
     *                     default=null),
     *          @Parameter("time",
     *                     type="Time",
     *                     required=true,
     *                     description="",
     *                     default=null),
     *          @Parameter("number_of_people",
     *                     type="Integer",
     *                     required=true,
     *                     description="",
     *                     default=null),
     *          @Parameter("advance_payment",
     *                     type="Boolean",
     *                     required=true,
     *                     description="",
     *                     default=null),
     *          @Parameter("coupon_code",
     *                     type="String",
     *                     required=true,
     *                     description="",
     *                     default=null),
     *     })
     */
    public function store(ReservationRequest $request)
    {
        $fields = $request->only([
            'date',
            'time',
            'number_of_people',
            'restaurant_id',
            'user_id',
            'advance_payment',
            'coupon_code',
            'option',
            'referrer_code'
        ]);

        $data = [
            'reservation' => $fields
        ];

        $data = array_merge($data, $fields);

        $rules = [
            'date' => 'date_format:Y-n-j',
            'time' => 'date_format:H:i|custom_after_now',
            'number_of_people' => 'numeric',
            'restaurant_id' => 'exists:restaurants,id,reservable_online,1',
            'user_id' => 'exists:users,id',
            'advance_payment' => 'boolean',
//            'date' => 'required|date_format:Y-n-j',
//            'time' => 'required|date_format:H:i|custom_after_now',
//            'number_of_people' => 'required|numeric',
//            'restaurant_id' => 'required|exists:restaurants,id,reservable_online,1',
//            'user_id' => 'required|exists:users,id',
//            'advance_payment' => 'required|boolean',
            'coupon_code' => 'coupon_check|reservation_is_coupon_owner',
            'option' => 'sometimes|in:INDOORS,OUTDOORS,INOUT|check_reservation_is_valid_spatial_option',
            'referrer_code' => 'string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }

        $defaults = $this->populateFields($fields['restaurant_id'], $fields['number_of_people'], $fields['coupon_code'], $fields['date']);

        $defaults['status'] = Reservation::STATUS_PENDING;

        $reservation = Reservation::create(array_merge($fields, $defaults));

        if (!$reservation) {
            return $this->response->errorInternal(trans('reservation could not be created'));
        }

        /**
         * Fire event to handle notifications
         */
        try {
            $this->dispatch(new sendReservationPendingNotifications($reservation));
        } catch (CartException $ex) {
            return $this->response->errorBadRequest($ex->getMessage());
        } catch (Exception $ex) {
            return $this->response->errorInternal(trans('Reservation was created, but notification could not be sent'));
        }

        return $this->response->created($request->getUri(), $reservation);
    }

    /**
     * User Story:
     * As a reservation manager
     * I want to approve a reservation made by a diner
     * so that he can dine in
     *
     * @Post("/{reservation_id}/accept")
     * @Versions({"v1"})
     * @Parameters({
     *          @Parameter(
     *              "reservation_id",
     *              type="integer",
     *              required=true,
     *              description="",
     *              default=null
     *          )
     *     })
     */
    public function accept(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        // validation step to avoid changing state of already paid or cancelled reservations
        $data = [
            'reservation' => $reservation
        ];

        $rules = [
            'reservation' => 'reservation_status_pending_check'
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }

        // accept by changing status
        $reservation->status = Reservation::STATUS_APPROVED;
        $result = $reservation->update();

        if (!$result) {
            return $this->response->errorInternal(trans('Reservation could not be updated'));
        }

        // trigger event after accept
        try {
            dispatch(new sendReservationApprovedNotifications($reservation));
        } catch (Exception $ex) {

            return $this->response->errorInternal(trans('Reservation was accepted, but notification could not be sent'));
        }
        return $this->response->created($request->getUri(), $reservation);
    }

    /**
     * User Story:
     * As a reservation manager
     * I want to change the reservation made by a diner
     * that he arrived
     *
     * @Post("/{reservation_id}/arrived")
     * @Versions({"v1"})
     * @Parameters({
     *          @Parameter(
     *              "reservation_id",
     *              type="integer",
     *              required=true,
     *              description="",
     *              default=null
     *          )
     *     })
     */
    public function arrived(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        // validation step to avoid changing state of already paid or cancelled reservations
        $data = [
            'reservation' => $reservation
        ];

        $rules = [
            'reservation' => 'reservation_status_paid_check'
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }

        // accept by changing status
        $reservation->status = Reservation::STATUS_ARRIVED;
        $result = $reservation->update();

        if (!$result) {
            return $this->response->errorInternal(trans('Reservation could not be updated'));
        }

        return $this->response->created($request->getUri(), $reservation);
    }

    /**
     * The following procedures does three things:
     * Validate that a reservation is still pendning
     * Reject a reservation
     * Fire Notification
     *
     * @return \Dingo\Api\Http\Response|void
     * @Versions({"v1"})
     * @Post("/{reservation_id}/reject")
     */
    public function reject(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        // validate
        $rules = [
            'reservation' => 'reservation_status_pending_check'
        ];

        $data = [
            'reservation' => $reservation
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }

        // update
        $reservation->status = Reservation::STATUS_REJECTED;

        $result = $reservation->update();

        if (!$result) {
            return $this->response->errorInternal(trans('Reservation could not be rejected'));
        }

        // trigger event after reject
        try {
//            Event::fire(new ReservationWasRejected($reservation));
            dispatch(new sendReservationRejectedNotifications($reservation));
        } catch (Exception $ex) {
            return $this->response->errorInternal(trans('Reservation was rejected, but notification could not be sent'));
        }

        return $this->response->accepted($request->getUri(), $reservation);
    }

    /**
     * Display the specified resource.
     *
     * @Get("/{reservation_id}")
     * @Versions({"v1"})
     * @Parameters({
     *     @Parameter("reservation_id",
     *                type="Integer",
     *                required=true,
     *                description="",
     *                default=null),
     *     })
     */
    public function show(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        $data = [
            'reservation' => $reservation
        ];

        $rules = [
            'reservation' => 'reservation_can_view'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return $this->response->errorForbidden();
        }

        // query again with related
        $reservation = $this->eagerLoadReservation($reservation->id);
        return $this->response->created('', $reservation);

//        return $this->response->accepted($request->getUri(), $reservation);
    }

    /**
     * Create a new reservation with status change,
     * and relate it to the original reservation via reservation_id
     * and it should validate that the required changes are not the same
     * as the original reservation.
     *
     * @param Request $request
     * @param Reservation $reservation
     */
    public function change(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        // validation
        $fields = $request->only([
            'date',
            'time',
            'number_of_people',
            'restaurant_id',
            'user_id',
            'advance_payment',
            'coupon_code',
            'option',
            'referrer_code',
        ]);

        $data = [
            'reservation' => $reservation
        ];

        $data = array_merge($data, $fields);

        $rules = [
            'reservation' => 'reservation_can_change|reservation_status_pending_check|reservation_datetime_passed',
            'date' => 'date_format:Y-n-j',
            'time' => 'date_format:H:i|custom_after_now',
            'number_of_people' => 'numeric',
            'restaurant_id' => 'exists:restaurants,id,reservable_online,1',
            'user_id' => 'exists:users,id',
            'advance_payment' => 'boolean',
            'coupon_code' => 'coupon_check|reservation_is_coupon_owner',
            'option' => 'sometimes|in:INDOORS,OUTDOORS,INOUT|check_reservation_is_valid_spatial_option'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException( trans('Your request can not be completed'), $validator->errors());
        }

        // populate new fields, compare with old values, loop and perserve old values only
        $changed_data = $this->populateFieldsForChange($fields, $reservation);
        $filtered_attributes = $this->distillChanged($changed_data, $reservation);
//        $result = $this->reservationChangeTransaction($filtered_attributes, $reservation);
        $roles = [Role::RESERVATION_MANAGER, Role::RESTAURANT_MANAGER, Role::SUPER_ADMIN];
        if (User::getCurrentUser()->hasRole($roles)) {
            $result = $reservation->update(['status' => Reservation::STATUS_APPROVED]);
        } else {
            $result = $reservation->update(['status' => Reservation::STATUS_CHANGE_REQUESTED]);
        }

        $updated = $reservation->fill($filtered_attributes)->save();

        if (!$updated) {
            return $this->response->errorInternal(trans('Status could not be updated'));
        }

        // query again with related
        $reservation = $this->eagerLoadReservation($reservation->id);

        // trigger notifications listeners
        //Event::fire(new ReservationChangeWasRequested($reservation));

        try {
            dispatch(new sendReservationChangedNotifications($reservation));
        } catch (Exception $ex) {
            return $this->response->errorInternal(trans('Reservation was accepted, but notification could not be sent'));
        }

        return $this->response->accepted($request->getUri(), $reservation);
    }

    public function reschedule(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        // validation
        $fields = $request->only([]);

        $fields['reservation'] = $reservation;

        $rules = [
            'reservation' => 'reservation_can_reschedule|reservation_status_reschedule_check',
        ];

        $validator = Validator::make($fields, $rules);

        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }

        // query again with related
        $reservation = $this->eagerLoadReservation($reservation->id);

        // apply & update reservation status to rescheduled
        $result = $this->applyReservationChanges($reservation);

        if (!$result) {
            return $this->response->errorInternal(trans('Changes could not be applied'));
        }

        // trigger notifications
        Event::fire(new ReservationWasRescheduled($reservation));

        // query again with related to reflect changes
        $reservation = $this->eagerLoadReservation($reservation->id);

        return $this->response->accepted($request->getUri(), $reservation);
    }

    public function cancel(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        $fields = $request->only([]);

        $fields['reservation'] = $reservation;

        // check status is not already cancelled
        // check user is either admin / res. mgr / diner owner of the res.
        $rules = [
            'reservation' => 'reservation_can_cancel|reservation_status_cancel_check'
        ];

        $validator = Validator::make($fields, $rules);

        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }

        $result = $reservation->update([
            'status' => Reservation::STATUS_CANCELLED
        ]);

        if (!$result) {
            $this->response->errorInternal(trans('Reservation could not be cancelled'));
        }

        // trigger notifications
        Event::fire(new ReservationWasCancelled($reservation));

        return $this->response->accepted($request->getUri(), $reservation);
    }


    public function getLastReservationTime(\Illuminate\Http\Request $request)
    {
        $model = Reservation::select(['created_at'])
            ->orderBy('created_at', 'DESC')
            ->first();

        Carbon::setLocale(\App::getLocale());

        return $this->response->array($model->created_at->diffForHumans());
    }

    public function countReservationPeople(\Illuminate\Http\Request $request)
    {
        return $this->response->array(Reservation::count('number_of_people'));
    }

    /**
     * @param Request $request
     * @param Reservation $reservation
     * @return \Dingo\Api\Http\Response|void
     */
    public function doPay(\Illuminate\Http\Request $request, Reservation $reservation)
    {
        $fields = $request->only([
            'external_reference',// => 'required',
            'request',// =>
            'response'
        ]);

        $fields['reservation'] = $reservation;

        try {
            $this->reservation_repository->doPay($fields);
        } catch (CustomValidationException $ex) {
            return $this->response->errorBadRequest($ex->getValidator()->errors());
        } catch (ORMException $ex) {
            return $this->response->errorInternal();
        } catch (Exception $ex) {
            return $this->response->errorInternal();
        }

        $this->dispatch(new sendReservationPaidNotifications($reservation));

        return $this->response->created($request->getUri(), $reservation);
    }

    /**
     * Send Walking email.
     *
     * @Get("/export-walk")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_id",type="integer",required=true,description="The Id of restaurant. ",default=0),
     *     @Parameter("exported_file", type="file", required=true, description="A search query.", default=""),
     * })
     */
    public function exportWalkingReport(\Illuminate\Http\Request $request, Restaurant $restaurant)
    {

        if ($restaurant->id != @User::getManagersRestaurant()->id) {
            return $this->response->errorUnauthorized('You aren\'t allowed to do this action');
        }

        $fields = $request->only(['exported_file']);

        $rules = [
            'exported_file' => 'required|file|mimes:csv,txt'
        ];

        $validator = Validator::make($fields, $rules);

        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }


        $this->dispatch(new sendExportedWalkInEmail($restaurant));

        return $this->response->created('', ['Email sent!']);

    }
}