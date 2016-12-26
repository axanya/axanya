<?php

/**
 * Help Category Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Help Category
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HelpCategoryDataTable;
use App\Models\HelpCategory;
use App\Models\Help;
use App\Http\Start\Helpers;
use Validator;

class HelpCategoryController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Help Category
     *
     * @param array $dataTable  Instance of HelpCategoryDataTable
     * @return datatable
     */
    public function index(HelpCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.help_category.view');
    }

    /**
     * Add a New Help Category
     *
     * @param array $request  Input values
     * @return redirect     to Help Category view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.help_category.add');
        }
        else if($request->submit)
        {
            // Add Help Category Validation Rules
            $rules = array(
                    'name'    => 'required|unique:help_category',
                    'status'  => 'required'
                    );

            // Add Help Category Validation Custom Names
            $niceNames = array(
                        'name'    => 'Name',
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
                $help_category = new HelpCategory;

			    $help_category->name        = $request->name;
			    $help_category->description = $request->description;
			    $help_category->status      = $request->status;

                $help_category->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect('admin/help_category');
            }
        }
        else
        {
            return redirect('admin/help_category');
        }
    }

    /**
     * Update Help Category Details
     *
     * @param array $request    Input values
     * @return redirect     to Help Category View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = HelpCategory::find($request->id);

            return view('admin.help_category.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Help Category Validation Rules
            $rules = array(
                    'name'    => 'required|unique:help_category,name,'.$request->id,
                    'status'  => 'required'
                    );

            // Edit Help Category Validation Custom Fields Name
            $niceNames = array(
                        'name'    => 'Name',
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
                $help_category = HelpCategory::find($request->id);

			    $help_category->name        = $request->name;
			    $help_category->description = $request->description;
			    $help_category->status      = $request->status;

                $help_category->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/help_category');
            }
        }
        else
        {
            return redirect('admin/help_category');
        }
    }

    /**
     * Delete Help Category
     *
     * @param array $request    Input values
     * @return redirect     to Help Category View
     */
    public function delete(Request $request)
    {
        $count = Help::where('help_category', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Help have this Help Category. So, Delete that Help or Change that Help Help Category.'); // Call flash message function
        else {
            HelpCategory::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect('admin/help_category');
    }
}
