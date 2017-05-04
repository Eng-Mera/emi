<?php

namespace App\Listeners;

use App\Coupon;
use App\Exceptions\MailException;
use App\Http\Helpers\PromotableTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserLevelWasPromoted as UserLevelWasPromotedEvent;
use App\User;
use Mockery\CountValidator\Exception;

class UserLevelWasPromoted
{
    /**
     * Promotable
     */
    use PromotableTrait;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  UserLevelWasPromoted $event
     * @return void
     */
    public function handle(UserLevelWasPromotedEvent $event)
    {
        // generate promo codes
        if(empty($event->coupon))
            $promo = $this->generateUserCoupons($event->user->id, $event->user->rate_score);
        else
            $promo = $event->coupon;

        if (empty($promo))
            return;

        // send e-mail notification with codes // sms // push
        $this->sendPromoToCustomer($promo, $event->user);
    }

    /**
     * send e-mail with promo code to customer
     * 
     * @param Coupon $promo
     * @param User $customer
     * @throws MailException
     */
    public function sendPromoToCustomer(Coupon $promo, User $customer)
    {
        try {
            \Mail::send('emails.cart.customer.coupon',
                [
                    'promo' => $promo,
                    'customer' => $customer
                ],
                function ($msg) use ($promo, $customer) {
                    $msg->from(\Config::get('nilecode.emails.sales.email'), trans(\Config::get('nilecode.emails.sales.name')));
                    $msg->to($customer->email, $customer->name)->subject(trans('Apply Promo Code & Enjoy Discounts'));
                });
        } catch (\Exception $ex) {
            throw new MailException(trans('Coupon was generated, but e-mail could not be sent'));
        }
    }
}
