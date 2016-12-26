<?php

/**
 * BottomSlider Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    BottomSlider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\BottomSliderDataTable;
use App\Models\BottomSlider;
use App\Http\Start\Helpers;
use Validator;

class BottomSliderController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for BottomSlider
     *
     * @param array $dataTable  Instance of BottomSliderDataTable
     * @return datatable
     */
    public function index(BottomSliderDataTable $dataTable)
    {
        return $dataTable->render('admin.bottom_slider.view');
    }

    /**
     * Add a New Bottom Slider
     *
     * @param array $request  Input values
     * @return redirect     to Bottom Slider view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.bottom_slider.add');
        }
        else if($request->submit)
        {
            // Add Bottom Slider Validation Rules
            $rules = array(
                    'image'   => 'required', 
                    'order'   => 'required', 
                    'title'   => 'required', 
                    'description'   => 'required', 
                    'status'  => 'required',
                    );

            // Add Bottom Slider Validation Custom Names
            $niceNames = array(
                        'image'    => 'Image',
                        'title'    => 'Title',
                        'des'    => 'Title',
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

                $success = $image->move('images/bottom_slider', $filename);
                $this->helper->compress_image('images/bottom_slider/'.$filename, 'images/bottom_slider/'.$filename, 80);
        
                if(!$success)
                    return back()->withError('Could not upload Image');

                $slider = new BottomSlider;

                $slider->image = $filename;
                $slider->order = $request->order; 
                $slider->title = $request->title; 
                $slider->description = $request->description; 
                $slider->status = $request->status;

                $slider->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect('admin/bottom_slider');
            }
        }
        else
        {
            return redirect('admin/bottom_slider');
        }
    }

    /**
     * Update Bottom Slider Details
     *
     * @param array $request    Input values
     * @return redirect     to Bottom Slider View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = BottomSlider::find($request->id);

            return view('admin.bottom_slider.edit', $data);
        }
        else if($request->submit)
        {

            // Update Bottom Slider Validation Rules
            $rules = array(
                    'order'   => 'required', 
                    'status'  => 'required',
                    'title'  => 'required',
                    'description'  => 'required',
                    );

            // Update Bottom Slider Validation Custom Names
            $niceNames = array(
                        'order'   => 'Position', 
                        'status'  => 'Status',
                        'title'  => 'Title',
                        'description'  => 'Description',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }

                $slider = BottomSlider::find($request->id);

                $image     =   $request->file('image');
                if($image) {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'slider_'.time() . '.' . $extension;
    
                    $success = $image->move('images/bottom_slider', $filename);
                    $this->helper->compress_image('images/bottom_slider/'.$filename, 'images/bottom_slider/'.$filename, 80);
        
                    if(!$success)
                        return back()->withError('Could not upload Image');

                    $slider->image = $filename;
                }

                $slider->order = $request->order;
                $slider->status = $request->status;
                $slider->title = $request->title;
                $slider->description = $request->description;
                $slider->updated_at = date('Y-m-d H:i:s');
                
                $slider->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect('admin/bottom_slider');
        }
        else
        {
            return redirect('admin/bottom_slider');
        }
    }

    /**
     * Delete Bottom Slider
     *
     * @param array $request    Input values
     * @return redirect     to Bottom Slider View
     */
    public function delete(Request $request)
    {
        BottomSlider::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/bottom_slider');
    }
}
