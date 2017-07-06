<?php

/**
 * Help Subcategory Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Help Subcategory
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HelpSubCategoryDataTable;
use App\Models\HelpSubCategory;
use App\Models\Help;
use App\Models\HelpCategory;
use App\Http\Start\Helpers;
use Validator;

class HelpSubCategoryController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Help Subcategory
     *
     * @param array $dataTable  Instance of HelpSubCategoryDataTable
     * @return datatable
     */
    public function index(HelpSubCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.help_subcategory.view');
    }

    /**
     * Add a New Help Subcategory
     *
     * @param array $request  Input values
     * @return redirect     to Help Subcategory view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            $data['category'] = HelpCategory::active_all();
            return view('admin.help_subcategory.add', $data);
        }
        else if($request->submit)
        {
            // Add Help Subcategory Validation Rules
            $rules = array(
                    'name'    => 'required|unique:help_subcategory',
                    'category_id'  => 'required',
                    'status'  => 'required'
                    );

            // Add Help Subcategory Validation Custom Names
            $niceNames = array(
                        'name'    => 'Name',
                        'category_id'  => 'Category',
                        'status'  => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $help_subcategory = new HelpSubCategory;

                $help_subcategory->name        = $request->name;
                $help_subcategory->category_id = $request->category_id;
                $help_subcategory->description = $request->description;
                $help_subcategory->status      = $request->status;

                $help_subcategory->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/help_subcategory');
            }
        }
        else
        {
            return redirect('admin/help_subcategory');
        }
    }

    /**
     * Update Help Subcategory Details
     *
     * @param array $request    Input values
     * @return redirect     to Help Subcategory View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['category'] = HelpCategory::active_all();
			$data['result'] = HelpSubCategory::find($request->id);

            return view('admin.help_subcategory.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Help Subcategory Validation Rules
            $rules = array(
                    'name'    => 'required|unique:help_subcategory,name,'.$request->id,
                    'category_id'  => 'required',
                    'status'  => 'required'
                    );

            // Edit Help Subcategory Validation Custom Fields Name
            $niceNames = array(
                        'name'    => 'Name',
                        'category_id'  => 'Category',
                        'status'  => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $help_subcategory = HelpSubCategory::find($request->id);

                $help_subcategory->name        = $request->name;
                $help_subcategory->category_id = $request->category_id;
                $help_subcategory->description = $request->description;
                $help_subcategory->status      = $request->status;

                $help_subcategory->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/help_subcategory');
            }
        }
        else
        {
            return redirect('admin/help_subcategory');
        }
    }

    /**
     * Delete Help Subcategory
     *
     * @param array $request    Input values
     * @return redirect     to Help Subcategory View
     */
    public function delete(Request $request)
    {
        $count = Help::where('help_subcategory', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Help have this Help Subcategory. So, Delete that Help or Change that Help Help Subcategory.'); // Call flash message function
        else {
            HelpSubCategory::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/help_subcategory');
    }
}
