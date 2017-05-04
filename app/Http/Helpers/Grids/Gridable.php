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

use Illuminate\Database\Eloquent\Builder;
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

trait Gridable
{

    use CartFormatters;

    public function build(Builder $query)
    {
        $grid_config = new GridConfig;
        $grid_config->setDataProvider(new EloquentDataProvider($query));

        $grid_config->setName('coupons_grid');
        $grid_config->setPageSize(\Config::get('nilecode.backend.pagination.page_size'));
        $grid_config->setColumns($this->getColumns());
        $grid_config->setComponents($this->getComponents());

        return new Grid($grid_config);
    }

    public function getComponents()
    {
        $ret = [];

        $ret[] = $this->getHeader();
        $ret[] = $this->getFlat();
        $ret[] = $this->getFooter();

        return $ret;
    }

    public function getFlat()
    {
        return (new OneCellRow)
            ->setRenderSection(RenderableRegistry::SECTION_END)
            ->setComponents([
                new RecordsPerPage,
                new ColumnsHider,
                (new CsvExport)
                    ->setFileName('my_report' . date('Y-m-d'))
                ,
                new ExcelExport(),
                (new HtmlTag)
                    ->setContent('<span class="glyphicon glyphicon-refresh"></span> Filter')
                    ->setTagName('button')
                    ->setRenderSection(RenderableRegistry::SECTION_END)
                    ->setAttributes([
                        'class' => 'btn btn-success btn-sm'
                    ])
            ]);
    }

    public function getFooter() {
        return (new TFoot)
            ->setComponents([
                (new TotalsRow(['value_sum'])),
                (new TotalsRow(['value_avg']))
                    ->setFieldOperations([
                        'value_sum' => TotalsRow::OPERATION_AVG,
                        'value_avg' => TotalsRow::OPERATION_SUM,
                    ])
                ,
                (new OneCellRow)
                    ->setComponents([
                        new Pager,
                        (new HtmlTag)
                            ->setAttributes(['class' => 'pull-right'])
                            ->addComponent(new ShowingRecords)
                        ,
                    ])
            ]);
    }
}
