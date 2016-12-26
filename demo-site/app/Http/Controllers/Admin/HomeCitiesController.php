<?php

/**
 * Home Cities Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Home Cities
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HomeCitiesDataTable;
use App\Models\HomeCities;
use App\Http\Start\Helpers;
use Validator;

class HomeCitiesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Home Cities
     *
     * @param array $dataTable  Instance of HomeCitiesDataTable
     * @return datatable
     */
    public function index(HomeCitiesDataTable $dataTable)
    {
        return $dataTable->render('admin.home_cities.view');
    }

    /**
     * Add a New Home Cities
     *
     * @param array $request  Input values
     * @return redirect     to Home Cities view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.home_cities.add');
        }
        else if($request->submit)
        {
            // Add Home Cities Validation Rules
            $rules = array(
                    'name'    => 'required',
                    'image'   => 'required'
                    );

            // Add Home Cities Validation Custom Names
            $niceNames = array(
                        'name'    => 'City Name',
                        'image'    => 'Image'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {

                $image     =   $request->file('image');
                $extension =   $image->getClientOriginalExtension();
                $filename  =   'home_city_'.time() . '.' . $extension;
                echo "<pre>";
                print_r($filename);
                exit;

                $success = $image->move('images/home_cities', $filename);
        
                if(!$success)
                    return back()->withError('Could not upload Image');

                $home_cities = new HomeCities;

                $home_cities->name  = $request->name;
                $home_cities->image = $filename;

                $home_cities->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect('admin/home_cities');
            }
        }
        else
        {
            return redirect('admin/home_cities');
        }
    }

    /**
     * Update Home Cities Details
     *
     * @param array $request    Input values
     * @return redirect     to Home Cities View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = HomeCities::find($request->id);

            return view('admin.home_cities.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Home Cities Validation Rules
            $rules = array(
                    'name'    => 'required'
                    );

            // Edit Home Cities Validation Custom Names
            $niceNames = array(
                        'name'    => 'City Name'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $home_cities = HomeCities::find($request->id);

                $home_cities->name  = $request->name;

                $image     =   $request->file('image');

                if($image) {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'home_city_'.time() . '.' . $extension;
    
                    $success = $image->move('images/home_cities', $filename);
                    $compress_success = $this->helper->compress_image('images/home_cities/'.$filename, 'images/home_cities/'.$filename, 80);
                    
                    if(!$success)
                        return back()->withError('Could not upload Image');

                    chmod('images/home_cities/'.$filename, 0777);
                    $home_cities->image = $filename;
                }

                $home_cities->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect('admin/home_cities');
            }
        }
        else
        {
            return redirect('admin/home_cities');
        }
    }

    /**
     * Delete Home Cities
     *
     * @param array $request    Input values
     * @return redirect     to Home Cities View
     */
    public function delete(Request $request)
    {
        HomeCities::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/home_cities');
    }
}
