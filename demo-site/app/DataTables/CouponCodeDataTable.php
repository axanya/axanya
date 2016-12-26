<?php

/**
 * Coupon Code DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Coupon Code
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\CouponCode;
use Yajra\Datatables\Services\DataTable;

class CouponCodeDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id', 'coupon_code', 'amount', 'currency_code', 'expired_at','status'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $coupon_code = $this->query();

        return $this->datatables
            ->of($coupon_code)
            ->addColumn('action', function ($coupon_code) {   
                return '<a href="'.url('admin/edit_coupon_code/'.$coupon_code->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url('admin/delete_coupon_code/'.$coupon_code->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $coupon_code = CouponCode::get();

        return $this->applyScopes($coupon_code);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        ->columns(['id', 'coupon_code', 'amount', 'currency_code', 'expired_at','status'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
        ]);
    }
}
