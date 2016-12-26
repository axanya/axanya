<?php

/**
 * Reviews DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Reviews
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Reviews;
use Yajra\Datatables\Services\DataTable;
use Auth;

class ReviewsDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $reviews = $this->query();

        return $this->datatables
            ->of($reviews)
            ->addColumn('action', function ($reviews) {

                $edit = '<a href="'.url('admin/edit_review/'.$reviews->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;';

                // $delete = '<a data-href="'.url('admin/delete_review/'.$reviews->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit;
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
        $reviews = Reviews::join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'reviews.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reviews.user_from');
                            })
                        ->join('users as users_to', function($join) {
                                $join->on('users_to.id', '=', 'reviews.user_to');
                            })
                        ->select(['reviews.id as id', 'reservation_id', 'rooms.name as room_name', 'users.first_name as user_from', 'users_to.first_name as user_to', 'review_by', 'comments', 'reviews.created_at as created_at', 'reviews.updated_at as updated_at']);

        return $this->applyScopes($reviews);
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
            'reservation_id',
            'room_name',
            'user_from',
            'user_to',
            'review_by',
            'comments',
            'created_at',
            'updated_at'
        ])*/
        ->addColumn(['data' => 'id', 'name' => 'reviews.id', 'title' => 'Id'])
        ->addColumn(['data' => 'reservation_id', 'name' => 'reservation_id', 'title' => 'Reservation Id'])
        ->addColumn(['data' => 'room_name', 'name' => 'rooms.name', 'title' => 'Room Name'])
        ->addColumn(['data' => 'user_from', 'name' => 'users.first_name', 'title' => 'User From'])
        ->addColumn(['data' => 'user_to', 'name' => 'users_to.first_name', 'title' => 'User To'])
        ->addColumn(['data' => 'review_by', 'name' => 'review_by', 'title' => 'Review By'])
        ->addColumn(['data' => 'comments', 'name' => 'comments', 'title' => 'Comments'])
        ->addColumn(['data' => 'created_at', 'name' => 'reviews.created_at', 'title' => 'Created At'])
        ->addColumn(['data' => 'updated_at', 'name' => 'reviews.updated_at', 'title' => 'Updated At'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            // 'dom' => 'Bfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
            'order' => [0, 'desc'],
        ]);
    }
}
