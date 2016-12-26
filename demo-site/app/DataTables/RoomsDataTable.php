<?php

/**
 * Rooms DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Rooms
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Rooms;
use Yajra\Datatables\Services\DataTable;
use Auth;

class RoomsDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';

    protected $exportColumns = ['id', 'user_id', 'host_name', 'name', 'summary', 'property_type', 'property_type_name', 'room_type', 'room_type_name', 'accommodates', 'bedrooms', 'beds', 'bed_type', 'bathrooms', 'amenities', 'calendar_type', 'status', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $rooms = $this->query();

        return $this->datatables
            ->of($rooms)
            ->addColumn('popular', function ($rooms) {

                $class = ($rooms->popular == 'No') ? 'danger' : 'success';

                $popular = '<a href="'.url('admin/popular_room/'.$rooms->room_id).'" class="btn btn-xs btn-'.$class.'">'.$rooms->popular.'</a>';

                return $popular;
            })
            ->addColumn('action', function ($rooms) {

                $edit = (Auth::admin()->user()->can('edit_room')) ? '<a href="'.url('admin/edit_room/'.$rooms->room_id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';

                $delete = (Auth::admin()
                    ->user()
                    ->can('delete_room')) ? '<a data-href="' . url('admin/delete_room/' . $rooms->room_id) . '" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>' : '';

                return $edit . $delete;
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
        $rooms = Rooms::join('users', function($join) {
                                $join->on('users.id', '=', 'rooms.user_id');
                            })
                        ->join('property_type', function($join) {
                                $join->on('property_type.id', '=', 'rooms.property_type');
                            })
                        ->join('room_type', function($join) {
                                $join->on('room_type.id', '=', 'rooms.room_type');
                            })
                        ->select(['rooms.id as room_id', 'rooms.name as room_name', 'rooms.status as room_status', 'rooms.created_at as room_created_at', 'rooms.updated_at as room_updated_at', 'rooms.*', 'users.*', 'property_type.*', 'room_type.*']);

        return $this->applyScopes($rooms);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        /*->columns([
            'id',
            'name',
            'host_name',
            'property_type_name',
            'room_type_name',
            'status',
            'created_at',
            'updated_at'
        ])*/
        ->addColumn(['data' => 'room_id', 'name' => 'rooms.id', 'title' => 'Id'])
        ->addColumn(['data' => 'room_name', 'name' => 'rooms.name', 'title' => 'Name'])
        ->addColumn(['data' => 'host_name', 'name' => 'users.first_name', 'title' => 'Host Name'])
        ->addColumn(['data' => 'property_type_name', 'name' => 'property_type.name', 'title' => 'Property Type'])
        ->addColumn(['data' => 'room_status', 'name' => 'rooms.status', 'title' => 'Status'])
        ->addColumn(['data' => 'room_created_at', 'name' => 'rooms.created_at', 'title' => 'Created At'])
        ->addColumn(['data' => 'room_updated_at', 'name' => 'rooms.updated_at', 'title' => 'Updated At'])
        ->addColumn(['data' => 'popular', 'name' => 'popular', 'title' => 'Popular'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            // 'dom' => 'Bfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
            'order' => [0, 'desc'],
        ]);
    }
}
