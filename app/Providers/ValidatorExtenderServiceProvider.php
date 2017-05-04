<?php
/**
 * Short description
 *
 * Long description for ValidatorExtenderServiceProvider.php (if any)...
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use Lang;

class ValidatorExtenderServiceProvider extends ServiceProvider
{

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->extendCartValidator();
    }

    /**
     * @inheritdoc
     */
    public function register()
    {

    }

    /**
     * Cart Validation
     */
    public function extendCartValidator()
    {
        \Validator::extend('coupon_check', 'App\Validators\CartValidator@validateCoupon');
        \Validator::extend('custom_after_now', 'App\Validators\CartValidator@validateDateTimeAfterNow');
        \Validator::extend('reservation_status_check', 'App\Validators\CartValidator@validateReservationStatus');
        \Validator::extend('reservation_advance_payment_check', 'App\Validators\CartValidator@validateReservationAdvancePayment');
        \Validator::extend('reservation_status_pending_check', 'App\Validators\CartValidator@validateReservationIsPending');
        \Validator::extend('reservation_status_change_check', 'App\Validators\CartValidator@validateReservationStatusForChange');
        \Validator::extend('reservation_status_reschedule_check', 'App\Validators\CartValidator@validateReservationStatusForReschedule');
        \Validator::extend('reservation_status_cancel_check', 'App\Validators\CartValidator@validateReservationStatusForCancel');
        \Validator::extend('reservation_status_paid_check', 'App\Validators\CartValidator@validateReservationStatusForPaid');
        \Validator::extend('reservation_can_view', 'App\Validators\CartValidator@validateReservationCanView');
        \Validator::extend('reservation_can_change', 'App\Validators\CartValidator@validateReservationCanChange');
        \Validator::extend('reservation_can_reschedule', 'App\Validators\CartValidator@validateReservationCanReschedule');
        \Validator::extend('reservation_can_cancel', 'App\Validators\CartValidator@validateReservationCanCancel');
        \Validator::extend('reservation_datetime_passed', 'App\Validators\CartValidator@validateReservationDateHasNotPassed');
        \Validator::extend('reservation_list', 'App\Validators\CartValidator@validateReservationList');
        \Validator::extend('reservation_is_coupon_owner', 'App\Validators\CartValidator@validateReservationIsCouponOwner');
        \Validator::extend('check_can_view_user_related', 'App\Validators\CartValidator@validateCanViewUserRelated');
        \Validator::extend('check_user_can_make_reservation', 'App\Validators\CartValidator@validateUserCanMakeReservation');
        \Validator::extend('check_coupon_unique_code_user', 'App\Validators\CartValidator@validateCouponUniqueCodeUser');
        \Validator::extend('check_reservation_is_approved', 'App\Validators\CartValidator@validateIsReservationApproved');
        \Validator::extend('check_reservation_is_owner', 'App\Validators\CartValidator@validateIsReservationOwner');
        \Validator::extend('check_reservation_is_valid_spatial_option', 'App\Validators\CartValidator@validateIsValidSpatialOPtion');
    }
}