<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Coupon;
use App\Http\Helpers\Grids\CouponGridable;
use App\Http\Helpers\Grids\CartFormatters;
use App\Http\Repositories\CouponRepository;
use App\Exceptions\CustomValidationException;

class AdminCouponController extends Controller
{

    use CartFormatters;

    private $coupon_repository;

    public function __construct()
    {
        parent::__construct();
        $this->coupon_repository = new CouponRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj_grid = (new CouponGridable())->build(Coupon::roles());

        $grid = $obj_grid->render();

        return view('admin.coupons.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('admin.coupons.create',['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'code',
            'value',
            'type',
            'reusable',
            'expired_at',
            'user_id'
        ]);
        
        try {
            $this->coupon_repository->handleStoreCoupon($data);
        } catch (CustomValidationException $ex) {
            return redirect()
                ->route('create_coupon')
                ->withErrors($ex->getValidator())
                ->withInput();
        }

        if ($request->input('saveAndAddAnother')) {

            return redirect()
                ->route('create_coupon');
        }

        return redirect()
            ->route('list_coupons')
            ->with('content-message', trans('Coupon was created successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', [
            'coupon' => $coupon,
            'value' => $this->formatCouponValue($coupon),
            'reusable' => $this->formatCouponReusable($coupon->reusable),
            'user' => $this->formatCouponUser($coupon)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', [
            'coupon' => $coupon,
            'value' => $this->formatCouponValue($coupon),
            'reusable' => $this->formatCouponReusable($coupon->reusable),
            'user' => $this->formatCouponUser($coupon)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->only([
            'code',
            'value',
            'type',
            'reusable',
            'expired_at',
            'user_id'
        ]);

        try {
            $this->coupon_repository->handleUpdateCoupon($coupon, $data);
        } catch (CustomValidationException $ex) {
            return redirect()
                ->route('edit_coupon', ['coupon' => $coupon])
                ->withErrors($ex->getValidator())
                ->withInput();
        }

        return redirect()
            ->route('list_coupons')
            ->with('content-message', trans('Coupon was updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        dd(\App\Reservation::whereCouponId($coupon->id)->count());
    }

}
