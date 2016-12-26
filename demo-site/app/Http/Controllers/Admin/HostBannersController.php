<?php

/**
 * Host Banners Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Host Banners
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HostBannersDataTable;
use App\Models\HostBanners;
use App\Http\Start\Helpers;
use Validator;

class HostBannersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Host Banners
     *
     * @param array $dataTable  Instance of HostBannersDataTable
     * @return datatable
     */
    public function index(HostBannersDataTable $dataTable)
    {
        return $dataTable->render('admin.host_banners.view');
    }

    /**
     * Add a New Host Banners
     *
     * @param array $request  Input values
     * @return redirect     to Host Banners view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.host_banners.add');
        }
        else if($request->submit)
        {
            // Add Host Banners Validation Rules
            $rules = array(
                    'title'         => 'required',
                    'description'   => 'required', 
                    'link_title'    => 'required',
                    'link'          => 'required',
                    'image'         => 'required'
                    );

            // Add Host Banners Validation Custom Names
            $niceNames = array(
                        'title'         => 'Title',
                        'description'   => 'Description', 
                        'link_title'    => 'Link Title',
                        'link'          => 'Link',
                        'image'         => 'Image'
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
                $filename  =   'host_banners_'.time() . '.' . $extension;

                $success = $image->move('images/host_banners', $filename);
        
                if(!$success)
                    return back()->withError('Could not upload Image');

                $host_banners = new HostBanners;

                $host_banners->title  = $request->title;
                $host_banners->description  = $request->description;
                $host_banners->link  = $request->link;
                $host_banners->link_title  = $request->link_title;
                $host_banners->image = $filename;

                $host_banners->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect('admin/host_banners');
            }
        }
        else
        {
            return redirect('admin/host_banners');
        }
    }

    /**
     * Update Host Banners Details
     *
     * @param array $request    Input values
     * @return redirect     to Host Banners View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = HostBanners::find($request->id);

            return view('admin.host_banners.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Host Banners Validation Rules
            $rules = array(
                    'title'         => 'required',
                    'description'   => 'required', 
                    'link_title'    => 'required',
                    'link'          => 'required',
                    );

            // Edit Host Banners Validation Custom Names
            $niceNames = array(
                        'title'         => 'Title',
                        'description'   => 'Description', 
                        'link_title'    => 'Link TItle',
                        'link'          => 'Link',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $host_banners = HostBanners::find($request->id);

                $host_banners->title  = $request->title;
                $host_banners->description  = $request->description;
                $host_banners->link  = $request->link;
                $host_banners->link_title  = $request->link_title;

                $image     =   $request->file('image');

                if($image) {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'host_banners_'.time() . '.' . $extension;
    
                    $success = $image->move('images/host_banners', $filename);
                    $compress_success = $this->helper->compress_image('images/host_banners/'.$filename, 'images/host_banners/'.$filename, 80);
                    
                    if(!$success)
                        return back()->withError('Could not upload Image');

                    chmod('images/host_banners/'.$filename, 0777);
                    $host_banners->image = $filename;
                }

                $host_banners->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect('admin/host_banners');
            }
        }
        else
        {
            return redirect('admin/host_banners');
        }
    }

    /**
     * Delete Host Banners
     *
     * @param array $request    Input values
     * @return redirect     to Host Banners View
     */
    public function delete(Request $request)
    {
        HostBanners::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/host_banners');
    }
}
