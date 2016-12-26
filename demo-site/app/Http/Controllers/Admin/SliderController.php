<?php

/**
 * Slider Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Slider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\SliderDataTable;
use App\Models\Slider;
use App\Http\Start\Helpers;
use Validator;

class SliderController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Slider
     *
     * @param array $dataTable  Instance of SliderDataTable
     * @return datatable
     */
    public function index(SliderDataTable $dataTable)
    {
        return $dataTable->render('admin.slider.view');
    }

    /**
     * Add a New Slider
     *
     * @param array $request  Input values
     * @return redirect     to Slider view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.slider.add');
        }
        else if($request->submit)
        {
            // Add Slider Validation Rules
            $rules = array(
                    'image'   => 'required', 
                    'order'   => 'required', 
                    'status'  => 'required',
                    );

            // Add Slider Validation Custom Names
            $niceNames = array(
                        'image'    => 'Image',
                        'order'   => 'Position', 
                        'status'  => 'Status',
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
                $filename  =   'slider_'.time() . '.' . $extension;

                $success = $image->move('images/slider', $filename);
                $this->helper->compress_image('images/slider/'.$filename, 'images/slider/'.$filename, 80);
        
                if(!$success)
                    return back()->withError('Could not upload Image');

                $slider = new Slider;

                $slider->image = $filename;
                $slider->order = $request->order; 
                $slider->status = $request->status;
                $slider->created_at = date('Y-m-d H:i:s');
                $slider->updated_at = date('Y-m-d H:i:s');

                $slider->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect('admin/slider');
            }
        }
        else
        {
            return redirect('admin/slider');
        }
    }

    /**
     * Update Slider Details
     *
     * @param array $request    Input values
     * @return redirect     to Slider View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = Slider::find($request->id);

            return view('admin.slider.edit', $data);
        }
        else if($request->submit)
        {

            // Update Slider Validation Rules
            $rules = array(
                    'order'   => 'required', 
                    'status'  => 'required',
                    );

            // Update Slider Validation Custom Names
            $niceNames = array(
                        'order'   => 'Position', 
                        'status'  => 'Status',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }

                $slider = Slider::find($request->id);

                $image     =   $request->file('image');

                if($image) {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'slider_'.time() . '.' . $extension;
    
                    $success = $image->move('images/slider', $filename);
                    $this->helper->compress_image('images/slider/'.$filename, 'images/slider/'.$filename, 80);
        
                    if(!$success)
                        return back()->withError('Could not upload Image');

                    $slider->image = $filename;
                }

                $slider->order = $request->order;
                $slider->status = $request->status;
                $slider->updated_at = date('Y-m-d H:i:s');
                
                $slider->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect('admin/slider');
        }
        else
        {
            return redirect('admin/slider');
        }
    }

    /**
     * Delete Slider
     *
     * @param array $request    Input values
     * @return redirect     to Slider View
     */
    public function delete(Request $request)
    {
        Slider::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/slider');
    }
}
