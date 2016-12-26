<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        DB::table('permissions')->insert([
              ['id' => '1', 'name' => 'manage_admin', 'display_name' => 'Manage Admin', 'description' => 'Manage Admin Users'],
              ['id' => '2', 'name' => 'users', 'display_name' => 'View Users', 'description' => 'View Users'],
              ['id' => '3', 'name' => 'add_user', 'display_name' => 'Add User', 'description' => 'Add User'],
              ['id' => '4', 'name' => 'edit_user', 'display_name' => 'Edit User', 'description' => 'Edit User'],
              ['id' => '5', 'name' => 'delete_user', 'display_name' => 'Delete User', 'description' => 'Delete User'],
              ['id' => '6', 'name' => 'manage_amenities', 'display_name' => 'Manage Amenities', 'description' => 'Manage Amenities'],
              ['id' => '7', 'name' => 'manage_property_type', 'display_name' => 'Manage Property Type', 'description' => 'Manage Property Type'],
              ['id' => '8', 'name' => 'manage_room_type', 'display_name' => 'Manage Room Type', 'description' => 'Manage Room Type'],
              ['id' => '9', 'name' => 'manage_bed_type', 'display_name' => 'Manage Bed Type', 'description' => 'Manage Bed Type'],
              ['id' => '10', 'name' => 'manage_currency', 'display_name' => 'Manage Currency', 'description' => 'Manage Currency'],
              ['id' => '11', 'name' => 'manage_language', 'display_name' => 'Manage Language', 'description' => 'Manage Language'],
              ['id' => '12', 'name' => 'manage_country', 'display_name' => 'Manage Country', 'description' => 'Manage Country'],
              ['id' => '13', 'name' => 'api_credentials', 'display_name' => 'Api Credentials', 'description' => 'Api Credentials'],
              ['id' => '14', 'name' => 'payment_gateway', 'display_name' => 'Payment Gateway', 'description' => 'Payment Gateway'],
              ['id' => '15', 'name' => 'email_settings', 'display_name' => 'Email Settings', 'description' => 'Email Settings'],
              ['id' => '16', 'name' => 'site_settings', 'display_name' => 'Site Settings', 'description' => 'Site Settings'],
              ['id' => '17', 'name' => 'reservations', 'display_name' => 'Reservations', 'description' => 'Reservations'],
              ['id' => '18', 'name' => 'rooms', 'display_name' => 'View Rooms', 'description' => 'View Rooms'],
              ['id' => '19', 'name' => 'add_room', 'display_name' => 'Add Room', 'description' => 'Add Room'],
              ['id' => '20', 'name' => 'edit_room', 'display_name' => 'Edit Room', 'description' => 'Edit Room'],
              ['id' => '21', 'name' => 'delete_room', 'display_name' => 'Delete Room', 'description' => 'Delete Room'],
              ['id' => '22', 'name' => 'manage_pages', 'display_name' => 'Manage Pages', 'description' => 'Manage Pages'],
              ['id' => '23', 'name' => 'manage_fees', 'display_name' => 'Manage Fees', 'description' => 'Manage Fees'],
              ['id' => '24', 'name' => 'join_us', 'display_name' => 'Join Us', 'description' => 'Join Us'],
              ['id' => '25', 'name' => 'manage_metas', 'display_name' => 'Manage Metas', 'description' => 'Manage Metas'],
              ['id' => '26', 'name' => 'reports', 'display_name' => 'Reports', 'description' => 'Reports'],
              ['id' => '27', 'name' => 'manage_home_cities', 'display_name' => 'Manage Home Page Cities', 'description' => 'Manage Home Page Cities'],
              ['id' => '28', 'name' => 'manage_reviews', 'display_name' => 'Manage Reviews', 'description' => 'Manage Reviews'],
              ['id' => '29', 'name' => 'send_email', 'display_name' => 'Send Email', 'description' => 'Send Email'],
              ['id' => '30', 'name' => 'manage_help', 'display_name' => 'Manage Help', 'description' => 'Manage Help'],
              ['id' => '31', 'name' => 'manage_coupon_code', 'display_name' => 'Manage Coupon Code', 'description' => 'Manage Coupon Code'],
              ['id' => '32', 'name' => 'manage_referral_settings', 'display_name' => 'Manage Referral Settings', 'description' => 'Manage Referral Settings'],
              ['id' => '33', 'name' => 'manage_wishlists', 'display_name' => 'Manage Wish Lists', 'description' => 'Manage Wish Lists'],
        	]);
    }
}
