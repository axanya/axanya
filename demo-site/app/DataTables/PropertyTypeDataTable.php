<?php

/**
 * Property Type DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Property Type
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\PropertyType;
use Yajra\Datatables\Services\DataTable;

class PropertyTypeDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id', 'name', 'description', 'status'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $property_type = $this->query();

        return $this->datatables
            ->of($property_type)
            ->addColumn('action', function ($property_type) {   
                return '<a href="'.url('admin/edit_property_type/'.$property_type->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a class="btn btn-xs btn-primary" data-href="'.url('admin/delete_property_type/'.$property_type->id).'" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $property_type = PropertyType::select();

        return $this->applyScopes($property_type);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        ->columns([
            'id',
            'name',
            'description',
            'status'
        ])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
        ]);
    }
}
