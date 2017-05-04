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

use App\Http\Helpers\Grids\Gridable;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\RenderFunc;
use Nayjest\Grids\Components\Filters\DateRangePicker;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\Components\RecordsPerPage;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Components\ExcelExport;
use Nayjest\Grids\Components\HtmlTag;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\TotalsRow;
use Nayjest\Grids\Components\Laravel5\Pager;
use Nayjest\Grids\Components\ShowingRecords;
use HTML;
use Nayjest\Grids\ObjectDataRow;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;
use Nayjest\Grids\EloquentDataProvider;
use App\Coupon;

class ReservationGridable {
    
    use Gridable;

    public function getColumns() {
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
                ->setLabel('Value')
                ->setSortable(true)
                ->setCallback(function ($val, ObjectDataRow $row) {
                    $this->formatCouponValue($row->getSrc());
                })
                ->addFilter(
                (new FilterConfig)
                ->setOperator(FilterConfig::OPERATOR_LIKE)
        );

        $ret[] = (new FieldConfig)
                        ->setName('reusable')
                        ->setLabel('Reusable')
                        ->setSortable(true)
                        ->addFilter(
                                (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )->setCallback(array($this, 'formatCouponReusable'));

        $ret[] = (new FieldConfig)
                ->setName('user_id')
                ->setLabel('User')
                ->setSortable(true)
                ->addFilter((new FilterConfig)->setOperator(FilterConfig::OPERATOR_EQ))
                ->setCallback(function ($val, ObjectDataRow $row) {
            return $this->formatCouponUser($row->getSrc());
        });

        $ret[] = (new FieldConfig)
                ->setName('id')
                ->setLabel('Actions')
                ->setSortable(false)
                ->setCallback(array($this, 'renderActionsColumn'));

        return $ret;
    }

    public function getHeader() {
        return (new THead)
                        ->setComponents([
                            (new ColumnHeadersRow),
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
                                ->setRenderSection('filters_row_column_expired_at'),
                                (new DateRangePicker)
                                ->setName('created_at')
                                ->setRenderSection('filters_row_column_expired_at')
                                ->setDefaultValue(['1990-01-01', date('Y-m-d')])
                            ])
        ]);
    }

    public function renderActionsColumn($id, ObjectDataRow $row) {
        $id = $row->getSrc()->id;
        $view_url = route('show_coupon', [
            'coupon' => $id
        ]);
        $edit_url = route('edit_coupon', [
            'coupon' => $id
        ]);
        $delete_url = route('delete_coupon', [
            'coupon' => $id
        ]);
        $view = view('admin.coupons.partials.actions', [
            'view_url' => $view_url,
            'edit_url' => $edit_url,
            'delete_url' => $delete_url
        ]);
        return $view->render();
    }

}
