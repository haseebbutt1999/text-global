<?php namespace App\Jobs;

use App\Abandonedcartcampaign;
use App\AbandonedCartLog;
use App\Campaign;
use App\Contact;
use App\CountryShoppreference;
use App\CountryUser;
use App\Customer;
use App\Orderconfirm;
use App\Orderdispatch;
use App\Orderrefund;
use App\Shopdetail;
use App\Test;
use App\User;
use App\UserCamapignLog;
use App\Welcomecampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;
use stdClass;

class AppUninstalledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param stdClass $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Convert domain
        $this->shopDomain = ShopDomain::fromNative($this->shopDomain);

        try {
            $shop = User::where('name',$this->shopDomain->toNative())->first();
            $customers = Customer::where('user_id', $shop->id)->get();
            foreach ($customers as $customer){
                $customer->delete();
            }

            $welcomecampaigns = Welcomecampaign::where('user_id', $shop->id)->get();
            foreach ($welcomecampaigns as $welcomecampaign){
                $welcomecampaigns->delete();
            }
            $user_campaigns_logs = UserCamapignLog::where('user_id', $shop->id)->get();
            foreach ($user_campaigns_logs as $user_campaigns_log){
                $user_campaigns_log->delete();
            }
            $shop_details = Shopdetail::where('user_id', $shop->id)->get();
            foreach ($shop_details as $shop_detail){
                $shop_detail->delete();
            }
            $order_refunds = Orderrefund::where('user_id', $shop->id)->get();
            foreach ($order_refunds as $order_refund){
                $order_refund->delete();
            }
            $order_dispacths = Orderdispatch::where('user_id', $shop->id)->get();
            foreach ($order_dispacths as $order_dispacth){
                $order_dispacth->delete();
            }
            $order_conferms = Orderconfirm::where('user_id', $shop->id)->get();
            foreach ($order_conferms as $order_conferm){
                $order_conferm->delete();
            }
            $logs = Log::where('user_id', $shop->id)->get();
            foreach ($logs as $log){
                $log->delete();
            }
            $country_users = CountryUser::where('user_id', $shop->id)->get();
            foreach ($country_users as $country_user){
                $country_user->delete();
            }
            $country_prefs = CountryShoppreference::where('user_id', $shop->id)->get();
            foreach ($country_prefs as $country_pref){
                $country_pref->delete();
            }
            $contacts = Contact::where('user_id', $shop->id)->get();
            foreach ($contacts as $contact){
                $contact->delete();
            }
            $campaigns = Campaign::where('user_id', $shop->id)->get();
            foreach ($campaigns as $campaign){
                $campaign->delete();
            }
            $abandoned_carts = AbandonedCartLog::where('user_id', $shop->id)->get();
            foreach ($abandoned_carts as $abandoned_cart){
                $abandoned_cart->delete();
            }
            $abandoned_cart_campaigns = Abandonedcartcampaign::where('user_id', $shop->id)->get();
            foreach ($abandoned_cart_campaigns as $abandoned_cart_campaign){
                $abandoned_cart_campaign->delete();
            }

            $shop->forceDelete();
            return;
        } catch(\Exception $e) {
            $new = new Test();
            $new->text = "error: ".$e->getMessage();
            $new->save();
        }
    }
}
