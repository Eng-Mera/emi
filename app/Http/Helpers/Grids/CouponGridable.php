<?php

/**
 * CouponGridable
 *
 * Coupon Grid Component
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

namespace App\Http\Helpers\Grids;

use App\Coupon;
use App\Role;
use App\User;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\RenderFunc;

use HTML;
use Nayjest\Grids\ObjectDataRow;
use Nayjest\Grids\Grid;

class CouponGridable
{

    use Gridable;

    public function getColumns()
    {
        $ret = [];

        $ret[] = (new FieldConfig)
            ->setName('id')
            ->setLabel('ID')
            ->setSortable(true)
            ->setSorting(Grid::SORT_ASC)
            ->addFilter(
                (new FilterConfig)
                    ->setOperator(FilterConfig::OPERATOR_EQ)
            );
        $ret[] = (new FieldConfig)
            ->setName('code')
            ->setLabel('Code')
            ->setCallback(function ($val) {
                return $val;
            })
            ->setSortable(true)
            ->addFilter(
                (new FilterConfig)
                    ->setOperator(FilterConfig::OPERATOR_LIKE)
            );
        $ret[] = (new FieldConfig)
            ->setName('value')
            ->setLabel('Discount Value')
            ->setSortable(true)
            ->setCallback(function ($val, ObjectDataRow $row) {

                return $row->getSrc()->type == Coupon::COUPON_TYPE_FIXED ? $val . ' EGP' : $val . '%';
            })
            ->addFilter(
                (new FilterConfig)
                    ->setOperator(FilterConfig::OPERATOR_LIKE)
            );
        $ret[] = (new FieldConfig)
            ->setName('reusable')
            ->setLabel('Reusable')
            ->setSortable(true)
//            ->addFilter((new FilterConfig)->setOperator(FilterConfig::OPERATOR_LIKE))
            ->setCallback(array($this, 'formatCouponReusable'));
        $ret[] = (new FieldConfig)
            ->setName('user_id')
            ->setLabel('User')
            ->setSortable(true)
//            ->addFilter((new FilterConfig)->setOperator(FilterConfig::OPERATOR_EQ))
            ->setCallback(function ($val, ObjectDataRow $row) {
                return $this->formatCouponUser($row->getSrc());
            });

        if (User::getCurrentUser()->hasRole(Role::SUPER_ADMIN)) {

            $ret[] = (new FieldConfig)
                ->setName('restaurant_id')
                ->setLabel('Restaurant')
                ->setSortable(true)
//                ->addFilter((new FilterConfig)->setOperator(FilterConfig::OPERATOR_EQ))
                ->setCallback(function ($val, ObjectDataRow $row) {
                    return $this->formatCouponRestaurant($row->getSrc());
                });
        }

        $ret[] = (new FieldConfig)
            ->setName('expired_at')
            ->setLabel('expired_at')
            ->setSortable(true)
            ->setCallback(function ($val) {
                return $val;
            })
            ->addFilter(
                (new FilterConfig)
                    ->setOperator(FilterConfig::OPERATOR_LIKE)
            );

        $ret[] = (new FieldConfig)
            ->setName('created_at')
            ->setLabel('Date of Creation')
            ->setSortable(true)
            ->setCallback(function ($val) {
                return $val;
            })
            ->addFilter(
                (new FilterConfig)
                    ->setOperator(FilterConfig::OPERATOR_LIKE)
            );


        $ret[] = (new FieldConfig)
            ->setName('id')
            ->setLabel('Actions')
            ->setSortable(false)
            ->setCallback(array($this, 'renderActionsColumn'));

        return $ret;
    }

    public function getHeader()
    {
        return (new THead)
            ->setComponents([
                (new ColumnHeadersRow)
                ,
                (new FiltersRow)
                    ->addComponents([
                        (new RenderFunc(function () {
                            return HTML::style('js/daterangepicker/daterangepicker-bs3.css')
                            . HTML::script('js/moment/moment-with-locales.js')
                            . HTML::script('js/daterangepicker/daterangepicker.js')
                            . "<style>
                                                .daterangepicker td.available.active,
                                                .daterangepicker li.active,
                                                .daterangepicker li:hover {
                                                    color:black !important;
                                                    font-weight: bold;
                                                }
                                           </style>";
                        }))

                    ])
            ]);
    }

    public function renderActionsColumn($id, ObjectDataRow $row)
    {
        $id = $row->getSrc()->id;
        $view_url = route('show_coupon', [
            'coupon' => $id
        ]);
        $edit_url = route('edit_coupon', [
            'coupon' => $id
        ]);

        $view = view('admin.coupons.partials.actions', [
            'view_url' => $view_url,
            'edit_url' => $edit_url,
        ]);
        return $view->render();
    }

}
