<?php

use Illuminate\Database\Seeder;

class RoomsPhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms_photos')->delete();
    	
        DB::table('rooms_photos')->insert([
        		['id' => 1, 'room_id' => 10001, 'name' => '1454760500_6ed3bd5d_original.jpg', 'highlights' =>'Castro one-bedroom suite', 'featured' => 'Yes'],
        		['id' => 2, 'room_id' => 10001, 'name' => '1454760505_4d8ef718_original.jpg', 'highlights' =>'Castro one-bedroom suite', 'featured' => NULL ],
        		['id' => 3, 'room_id' => 10001, 'name' => '1454760509_674b7f87_original.jpg', 'highlights' =>'Castro one-bedroom suite', 'featured' => NULL],
        		['id' => 4, 'room_id' => 10001, 'name' => '1454760514_6051dbd4_original.jpg', 'highlights' =>'Castro one-bedroom suite', 'featured' => NULL],
        		['id' => 5, 'room_id' => 10001, 'name' => '1454760521_fede9e2b_original.jpg', 'highlights' =>'Castro one-bedroom suite', 'featured' => NULL],
        		['id' => 6, 'room_id' => 10002, 'name' => '1454761199_764b894b_original.jpg', 'highlights' =>'large, comfy br in fun neighborhood', 'featured' => 'Yes'],
        		['id' => 7, 'room_id' => 10002, 'name' => '1454761213_c20c4c53_original.jpg', 'highlights' =>'large, comfy br in fun neighborhood', 'featured' => NULL],
        		['id' => 8, 'room_id' => 10002, 'name' => '1454761220_a4a6d88e_original.jpg', 'highlights' =>'large, comfy br in fun neighborhood', 'featured' => NULL],
        		['id' => 9, 'room_id' => 10002, 'name' => '1454761224_540cef4b_original.jpg', 'highlights' =>'large, comfy br in fun neighborhood', 'featured' => NULL],
        		['id' => 10, 'room_id' => 10002, 'name' => '1454761227_241c62bd_original.jpg', 'highlights' =>'large, comfy br in fun neighborhood', 'featured' => NULL],
        		['id' => 11, 'room_id' => 10003, 'name' => '1454911487_beautiful.jpg', 'highlights' =>'The city at your doorstep', 'featured' => 'Yes'],
        		['id' => 12, 'room_id' => 10003, 'name' => '1454911491_76a6b892_original.jpg', 'highlights' =>'The city at your doorstep', 'featured' => NULL],
        		['id' => 13, 'room_id' => 10003, 'name' => '1454911494_2063a7a8_original.jpg', 'highlights' =>'The city at your doorstep', 'featured' => NULL],
        		['id' => 14, 'room_id' => 10003, 'name' => '1454911499_bb72e3a3_original.jpg', 'highlights' =>'The city at your doorstep', 'featured' => NULL],
        		['id' => 15, 'room_id' => 10003, 'name' => '1454911504_dd0a9ad8_original.jpg', 'highlights' =>'The city at your doorstep', 'featured' => NULL],
        		['id' => 16, 'room_id' => 10003, 'name' => '1454911508_fdaf6a46_original.jpg', 'highlights' =>'The city at your doorstep', 'featured' => NULL],
        		['id' => 17, 'room_id' => 10004, 'name' => '1454913686_05ab76e9-e4ae-43bc-a495-75904cc9b3e3.jpg', 'highlights' =>'', 'featured' => 'Yes'],
        		['id' => 18, 'room_id' => 10004, 'name' => '1454913690_102b8b5f_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 19, 'room_id' => 10004, 'name' => '1454913693_2611c682-af97-4de2-a0f2-349e8e50da59.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 20, 'room_id' => 10004, 'name' => '1454913697_b5133c0a_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 21, 'room_id' => 10004, 'name' => '1454913706_77a6c967_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 22, 'room_id' => 10005, 'name' => '1454914420_179af786_original.jpg', 'highlights' =>'', 'featured' => 'Yes'],
        		['id' => 23, 'room_id' => 10005, 'name' => '1454914428_24044fe5_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 24, 'room_id' => 10005, 'name' => '1454914434_52197715_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 25, 'room_id' => 10005, 'name' => '1454914439_dcbc28dd_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 26, 'room_id' => 10005, 'name' => '1454914452_612684ef-85e5-494f-ad68-7b8b50452147.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 27, 'room_id' => 10005, 'name' => '1454914463_33148590-369e-4afd-bef3-c59e583f83da.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 28, 'room_id' => 10006, 'name' => '1454914892_b9d50ba8_original.jpg', 'highlights' =>'Right by the Thames with Great View', 'featured' => 'Yes'],
        		['id' => 29, 'room_id' => 10006, 'name' => '1454914895_1be42764_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 30, 'room_id' => 10006, 'name' => '1454914899_1c13517e_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 31, 'room_id' => 10006, 'name' => '1454914903_6b10832b_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 32, 'room_id' => 10006, 'name' => '1454914907_73a3ba86_original.jpg', 'highlights' =>'', 'featured' => NULL],
        		['id' => 33, 'room_id' => 10006, 'name' => '1454914914_84c5cccc_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 39, 'room_id' => 10007, 'name' => '1455189624_d0c46730_original.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 40, 'room_id' => 10007, 'name' => '1455189640_7ca1bb65_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 41, 'room_id' => 10007, 'name' => '1455189643_9eb1a3b0_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 42, 'room_id' => 10007, 'name' => '1455189647_73a1aeeb_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 43, 'room_id' => 10007, 'name' => '1455189653_688d38a3_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 44, 'room_id' => 10008, 'name' => '1455189940_a8136267_original.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 45, 'room_id' => 10008, 'name' => '1455189946_2b078a37_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 46, 'room_id' => 10008, 'name' => '1455189953_46b9b911_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 47, 'room_id' => 10008, 'name' => '1455189958_e2760ad0_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 48, 'room_id' => 10008, 'name' => '1455189967_700e60f5_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 49, 'room_id' => 10009, 'name' => '1455190212_53bc6e4a_original.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 50, 'room_id' => 10009, 'name' => '1455190216_b1ca36f8_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 51, 'room_id' => 10009, 'name' => '1455190221_28857d2d_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 52, 'room_id' => 10009, 'name' => '1455190227_052b1ef7_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 53, 'room_id' => 10009, 'name' => '1455190232_4f64b94e_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 54, 'room_id' => 10010, 'name' => '1455192362_c663ef2b-a805-49be-8654-cae76962c35f.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 55, 'room_id' => 10010, 'name' => '1455192366_45b4ee25-b724-4325-8c18-bbe2fd258c72.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 56, 'room_id' => 10010, 'name' => '1455192370_161fc7a7-4680-4616-abb5-f8cbeead83a3.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 57, 'room_id' => 10010, 'name' => '1455192374_a157498e-867d-4148-9ec8-09606bdd2392.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 58, 'room_id' => 10010, 'name' => '1455192381_eb43a75d_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 64, 'room_id' => 10011, 'name' => '1455192780_1448616029_MEATPACKING-Superior-Room11.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 65, 'room_id' => 10011, 'name' => '1455192788_167b6371-bca3-4bb3-88fa-4a53237ef386.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 66, 'room_id' => 10011, 'name' => '1455192791_4f9dfbf9-9ebb-47f0-a798-18979db6909f.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 67, 'room_id' => 10011, 'name' => '1455192796_800fda4e-7d0d-46d9-b8cb-8bfc1baf8355.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 68, 'room_id' => 10011, 'name' => '1455192802_75188876-06da-449a-a36b-ccb5a093b7ef.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 69, 'room_id' => 10012, 'name' => '1455193026_8665a24d_original.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 70, 'room_id' => 10012, 'name' => '1455193030_07bd65ee_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 71, 'room_id' => 10012, 'name' => '1455193034_9a3628b2_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 72, 'room_id' => 10012, 'name' => '1455193039_700c26e7_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 73, 'room_id' => 10012, 'name' => '1455193044_3099ab9b_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 74, 'room_id' => 10013, 'name' => '1455193940_1d2efc59_original.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 75, 'room_id' => 10013, 'name' => '1455193944_3987b37c_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 76, 'room_id' => 10013, 'name' => '11455193948_215fee2f_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 77, 'room_id' => 10013, 'name' => '1455193951_c03aa297_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 78, 'room_id' => 10013, 'name' => '1455193954_e721a3f6_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 79, 'room_id' => 10014, 'name' => '1455194520_4832ab2d_original.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 80, 'room_id' => 10014, 'name' => '1455194525_753dceeb_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 81, 'room_id' => 10014, 'name' => '1455194529_963f9559_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 82, 'room_id' => 10014, 'name' => '1455194539_a563af51_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 83, 'room_id' => 10014, 'name' => '1455194543_f0c32dd5_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 84, 'room_id' => 10015, 'name' => '1455195273_cfc0416e-9c53-4487-aca4-9df608aee243.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 85, 'room_id' => 10015, 'name' => '1455195277_de5f5bc2-336d-4262-8406-8a78d73a1014.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 86, 'room_id' => 10015, 'name' => '1455195281_43e9bd57-9c99-436b-bfd5-e575788f6c69.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 87, 'room_id' => 10015, 'name' => '1455195286_c4e6a726-d8ed-4e36-aa32-e262237cca76.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 88, 'room_id' => 10016, 'name' => '1455196022_307f0b91-a805-4ff1-9345-e1864f6bd8df.jpg', 'highlights' =>'', 'featured' => 'Yes'],
                ['id' => 89, 'room_id' => 10016, 'name' => '1455196027_5efce424_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 90, 'room_id' => 10016, 'name' => '1455196031_4527e51c_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 91, 'room_id' => 10016, 'name' => '1455196034_b97d67d3_original.jpg', 'highlights' =>'', 'featured' => NULL],
                ['id' => 92, 'room_id' => 10016, 'name' => '1455196038_e6957229_original.jpg', 'highlights' =>'', 'featured' => NULL]

        	]);
    }
}
