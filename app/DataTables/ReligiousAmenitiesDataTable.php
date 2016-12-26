<?php

/**
 * ReligiousAmenities DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    ReligiousAmenities
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\ReligiousAmenities;
use Yajra\Datatables\Services\DataTable;

class ReligiousAmenitiesDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id', 'type_id', 'name', 'description', 'status'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $Religiousamenities = $this->query();

        return $this->datatables
            ->of($Religiousamenities)
            ->addColumn('action', function ($Religiousamenities) {   
                return '<a href="'.url('admin/edit_religious_amenity/'.$Religiousamenities->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url('admin/delete_religious_amenity/'.$Religiousamenities->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $Religiousamenities = ReligiousAmenities::select();

        return $this->applyScopes($Religiousamenities);
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
            'type_id',
            'name',
            'description',
            // 'icon',
            'status'
        ])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
        ]);
    }
}
