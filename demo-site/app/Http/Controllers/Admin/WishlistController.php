<?php

/**
 * Wish List Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Wish List
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\WishlistDataTable;
use App\Models\Wishlists;
use App\Http\Start\Helpers;
use Validator;

class WishlistController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Wishlists
     *
     * @param array $dataTable  Instance of WishlistDataTable
     * @return datatable
     */
    public function index(WishlistDataTable $dataTable)
    {
        return $dataTable->render('admin.wishlists.view');
    }

    public function pick(Request $request)
    {
        $prev = Wishlists::find($request->id)->pick;

        if($prev == 'Yes')
            Wishlists::where('id',$request->id)->update(['pick'=>'No']);
        else
            Wishlists::where('id',$request->id)->update(['pick'=>'Yes']);

        $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
        return redirect('admin/wishlists');
    }
}
