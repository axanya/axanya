<?php

/**
 * Users DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Users
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\User;
use Yajra\Datatables\Services\DataTable;
use Auth;

class UsersDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';

    protected $exportColumns = [ 'id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at' ];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $users = $this->query();

        return $this->datatables
            ->of($users)
            ->addColumn('action', function ($users) {

                $edit = (Auth::admin()->user()->can('edit_user')) ? '<a href="'.url('admin/edit_user/'.$users->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';

                $delete = (Auth::admin()
                    ->user()
                    ->can('delete_user')) ? '<a data-href="' . url('admin/delete_user/' . $users->id) . '" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>' : '';

                return $edit . $delete;
            })->addColumn('phone_numbers', function ($users)
            {
                $phone_numbers = '';
                foreach ($users->users_phone_numbers as $k => $phone_number)
                {
                    if ($phone_number->status == 'Confirmed')
                    {
                        $phone_numbers .= $phone_number->phone_number_full . ', ';
                    }
                }

                return trim($phone_numbers, ', ');
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
        $users = User::select();

        return $this->applyScopes($users);
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
            'first_name',
            'last_name',
            'email',
        ])->addColumn(['data'       => 'phone_numbers',
                       'name'       => 'phone_number',
                       'title'      => 'Phone Numbers',
                       'orderable'  => false,
                       'searchable' => true
            ])->columns([
                'status',
                'created_at',
                'updated_at'
            ])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            // 'dom' => 'Bfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
            'order' => [0, 'desc'],
        ]);
    }
}
