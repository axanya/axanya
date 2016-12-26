<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(CurrencyTableSeeder::class);

         $this->call(LanguageTableSeeder::class);

         $this->call(PropertyTypeTableSeeder::class);

         $this->call(RoomTypeTableSeeder::class);

         $this->call(SiteSettingsTableSeeder::class);

         $this->call(ApiCredentialsTableSeeder::class);

         $this->call(AmenitiesTypeTableSeeder::class);

         $this->call(AmenitiesTableSeeder::class);

         $this->call(BedTypeTableSeeder::class);

         $this->call(CountryTableSeeder::class);

         $this->call(PaymentGatewayTableSeeder::class);

         $this->call(RolesTableSeeder::class);

         $this->call(PermissionsTableSeeder::class);

         $this->call(AdminTableSeeder::class);

         $this->call(TimezoneTableSeeder::class);

         $this->call(EmailSettingsTableSeeder::class);

         $this->call(ThemeSettingsTableSeeder::class);

         $this->call(MessageTypeTableSeeder::class);

         $this->call(UsersTableSeeder::class);

         $this->call(PayoutPreferencesTableSeeder::class);

         $this->call(RoomsTableSeeder::class);

         $this->call(RoomsAddressTableSeeder::class);

         $this->call(RoomsDescriptionTableSeeder::class);

         $this->call(RoomsPhotosTableSeeder::class);

         $this->call(RoomsPriceTableSeeder::class);

         $this->call(RoomsStepsStatusTableSeeder::class);

         $this->call(ReservationTableSeeder::class);

         $this->call(MessagesTableSeeder::class);

         $this->call(CalendarTableSeeder::class);

         $this->call(PayoutsTableSeeder::class);

         $this->call(ReviewsTableSeeder::class);

         $this->call(PagesTableSeeder::class);

         $this->call(FeesTableSeeder::class);

         $this->call(JoinUsTableSeeder::class);

         $this->call(MetasTableSeeder::class);

         $this->call(HomeCitiesTableSeeder::class);

         $this->call(HelpCategoryTableSeeder::class);
         $this->call(HelpSubCategoryTableSeeder::class);
         $this->call(HelpTableSeeder::class);
         
         $this->call(ReferralSettingsTableSeeder::class);
         
         $this->call(WishlistsTableSeeder::class);
         $this->call(SavedWishlistsTableSeeder::class);

         $this->call(PlacesTableSeeder::class);
         $this->call(ReligiousAmenitiesTypeTableSeeder::class);
         $this->call(ReligiousAmenitiesTableSeeder::class);

        Model::reguard();
    }
}
