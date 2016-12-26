<?php

/**
 * Our Community Banners Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Our Community Banners
 * @author      Trioangle Product Team
 * @version     1.2
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\OurCommunityBannersDataTable;
use App\Models\OurCommunityBanners;
use App\Http\Start\Helpers;
use Validator;

class OurCommunityBannersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Our Community Banners
     *
     * @param array $dataTable  Instance of OurCommunityBannersDataTable
     * @return datatable
     */
    public function index(OurCommunityBannersDataTable $dataTable)
    {
        return $dataTable->render('admin.our_community_banners.view');
    }

    /**
     * Add a New Our Community Banners
     *
     * @param array $request  Input values
     * @return redirect     to Our Community Banners view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.our_community_banners.add');
        }
        else if($request->submit)
        {
            // Add Our Community Banners Validation Rules
            $rules = array(
                    'title'         => 'required',
                    'description'   => 'required', 
                    'link'          => 'required',
                    'image'         => 'required'
                    );

            // Add Our Community Banners Validation Custom Names
            $niceNames = array(
                        'title'         => 'Title',
                        'description'   => 'Description', 
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
                $filename  =   'our_community_banners_'.time() . '.' . $extension;

                $success = $image->move('images/our_community_banners', $filename);
        
                if(!$success)
                    return back()->withError('Could not upload Image');

                $our_community_banners = new OurCommunityBanners;

                $our_community_banners->title  = $request->title;
                $our_community_banners->description  = $request->description;
                $our_community_banners->link  = $request->link;
                $our_community_banners->image = $filename;

                $our_community_banners->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect('admin/our_community_banners');
            }
        }
        else
        {
            return redirect('admin/our_community_banners');
        }
    }

    /**
     * Update Our Community Banners Details
     *
     * @param array $request    Input values
     * @return redirect     to Our Community Banners View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = OurCommunityBanners::find($request->id);

            return view('admin.our_community_banners.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Our Community Banners Validation Rules
            $rules = array(
                    'title'         => 'required',
                    'description'   => 'required', 
                    'link'          => 'required',
                    );

            // Edit Our Community Banners Validation Custom Names
            $niceNames = array(
                        'title'         => 'Title',
                        'description'   => 'Description', 
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
                $our_community_banners = OurCommunityBanners::find($request->id);

                $our_community_banners->title  = $request->title;
                $our_community_banners->description  = $request->description;
                $our_community_banners->link  = $request->link;

                $image     =   $request->file('image');

                if($image) {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'our_community_banners_'.time() . '.' . $extension;
    
                    $success = $image->move('images/our_community_banners', $filename);
                    $compress_success = $this->helper->compress_image('images/our_community_banners/'.$filename, 'images/our_community_banners/'.$filename, 80);
                    
                    if(!$success)
                        return back()->withError('Could not upload Image');

                    chmod('images/our_community_banners/'.$filename, 0777);
                    $our_community_banners->image = $filename;
                }

                $our_community_banners->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect('admin/our_community_banners');
            }
        }
        else
        {
            return redirect('admin/our_community_banners');
        }
    }

    /**
     * Delete Our Community Banners
     *
     * @param array $request    Input values
     * @return redirect     to Our Community Banners View
     */
    public function delete(Request $request)
    {
        OurCommunityBanners::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect('admin/our_community_banners');
    }
}
