<?php

/**
 * Wishlist DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Wishlist
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Wishlists;
use Yajra\Datatables\Services\DataTable;
use Auth;

class WishlistDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $wishlists = $this->query();

        return $this->datatables
            ->of($wishlists)
            ->addColumn('pick', function ($wishlists) {

                $class = ($wishlists->pick == 'No') ? 'danger' : 'success';

                $pick = '<a href="'.url('admin/pick_wishlist/'.$wishlists->id).'" class="btn btn-xs btn-'.$class.'">'.$wishlists->pick.'</a>';

                return $pick;
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
        $wishlists = Wishlists::join('users', function($join) {
                                $join->on('users.id', '=', 'wishlists.user_id');
                            })->wherePrivacy('0')->select(['wishlists.id','wishlists.user_id','wishlists.name','wishlists.pick','users.first_name']);

        return $this->applyScopes($wishlists);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        ->addColumn(['data' => 'id', 'name' => 'wishlists.id', 'title' => 'Id'])
        ->addColumn(['data' => 'first_name', 'name' => 'users.first_name', 'title' => 'User Name'])
        ->addColumn(['data' => 'name', 'name' => 'wishlists.name', 'title' => 'Wish List Name'])
        ->addColumn(['data' => 'all_rooms_count', 'name' => 'all_rooms_count', 'title' => 'Lists Count'])
        ->addColumn(['data' => 'pick', 'name' => 'pick', 'title' => 'Pick'])
        ->parameters([
            'dom' => 'lBfrtip',
            // 'dom' => 'Bfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
            'order' => [0, 'desc'],
        ]);
    }
}
