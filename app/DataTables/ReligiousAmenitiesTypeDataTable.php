<?php

/**
 * Religious Amenities Type DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Religious Amenities Type
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\ReligiousAmenitiesType;
use Yajra\Datatables\Services\DataTable;

class ReligiousAmenitiesTypeDataTable extends DataTable
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
        $religious_amenities_type = $this->query();

        return $this->datatables
            ->of($religious_amenities_type)
            ->addColumn('action', function ($religious_amenities_type) {   
                return '<a href="'.url('admin/edit_religious_amenities_type/'.$religious_amenities_type->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url('admin/delete_religious_amenities_type/'.$religious_amenities_type->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $religious_amenities_type = ReligiousAmenitiesType::select();

        return $this->applyScopes($religious_amenities_type);
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
