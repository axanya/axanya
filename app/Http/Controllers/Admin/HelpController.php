<?php

/**
 * Help Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Help
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HelpDataTable;
use App\Models\Help;
use App\Models\HelpCategory;
use App\Models\HelpSubCategory;
use App\Http\Start\Helpers;
use Validator;

class HelpController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Help
     *
     * @param array $dataTable  Instance of HelpDataTable
     * @return datatable
     */
    public function index(HelpDataTable $dataTable)
    {
        return $dataTable->render('admin.help.view');
    }

    /**
     * Add a New Help
     *
     * @param array $request  Input values
     * @return redirect     to Help view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            $data['category'] = HelpCategory::active_all();
            $data['subcategory'] = HelpSubCategory::active_all();
            return view('admin.help.add', $data);
        }
        else if($request->submit)
        {
            // Add Help Validation Rules
            $rules = array(
                    'question'    => 'required',
                    'category_id' => 'required',
                    'answer'      => 'required',
                    'status'      => 'required'
                    );

            // Add Help Validation Custom Names
            $niceNames = array(
                        'question'    => 'Question',
                        'category_id' => 'Category',
                        'answer'      => 'Answer',
                        'status'      => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $help = new Help;

                $help->category_id    = $request->category_id;
                $help->subcategory_id = $request->subcategory_id;
                $help->question       = $request->question;
                $help->answer         = $request->answer;
                $help->suggested      = $request->suggested;
                $help->status         = $request->status;

                $help->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect('admin/help');
            }
        }
        else
        {
            return redirect('admin/help');
        }
    }

    /**
     * Update Help Details
     *
     * @param array $request    Input values
     * @return redirect     to Help View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['category'] = HelpCategory::active_all();
            $data['subcategory'] = HelpSubCategory::active_all();
			$data['result'] = Help::find($request->id);

            return view('admin.help.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Help Validation Rules
            $rules = array(
                    'question'    => 'required',
                    'category_id' => 'required',
                    'answer'      => 'required',
                    'status'      => 'required'
                    );

            // Edit Help Validation Custom Fields Name
            $niceNames = array(
                        'question'    => 'Question',
                        'category_id' => 'Category',
                        'answer'      => 'Answer',
                        'status'      => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $help = Help::find($request->id);

                $help->category_id    = $request->category_id;
                $help->subcategory_id = $request->subcategory_id;
                $help->question       = $request->question;
                $help->answer         = $request->answer;
                $help->suggested      = $request->suggested;
                $help->status         = $request->status;

                $help->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect('admin/help');
            }
        }
        else
        {
            return redirect('admin/help');
        }
    }

    /**
     * Delete Help
     *
     * @param array $request    Input values
     * @return redirect     to Help View
     */
    public function delete(Request $request)
    {
        Help::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        return redirect('admin/help');
    }

    public function ajax_help_subcategory(Request $request)
    {
        $result = HelpSubCategory::where('category_id', $request->id)->get();
        return json_encode($result);
    }
}
