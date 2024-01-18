<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Conversation;
use App\Model\CustomerAddress;
use App\Model\Newsletter;
use App\Model\Order;
use App\Model\OrderDetail;
use App\User;
use App\Model\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Http\JsonResponse;


class CustomerController extends Controller
{
    public function __construct(
        private Conversation $conversation,
        private CustomerAddress $customer_address,
        private Newsletter $newsletter,
        private Order $order,
        private OrderDetail $order_detail,
        private User $user,
        private Review $product_review
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function address_list(Request $request): JsonResponse
    {
        $response['billing_address'] = $this->customer_address->where('user_id', $request->user()->id)->latest()->get();

         return response()->json($response, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add_new_address(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            // 'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $address = [
            'user_id' => $request->user()->id,
            'full_name' => !empty($request->full_name) ? $request->full_name : null,
            'contact_person_name' => !empty($request->contact_person_name) ? $request->contact_person_name : null,
            'contact_person_number' => !empty($request->contact_person_number) ? $request->contact_person_number : null,
            // 'address_type' => !empty($request->address_type) ? $request->address_type : null,
            'address' => !empty($request->address) ? $request->address : null,
            'road' => !empty($request->road) ? $request->road : null, 
            'house' => !empty($request->house) ? $request->house : null,
            'floor' => !empty($request->floor) ? $request->floor : null,
            'longitude' => !empty($request->longitude) ? $request->longitude : null,
            'latitude' => !empty($request->latitude) ? $request->latitude : null,
            'country' => !empty($request->country) ? $request->country : null,
            'state' => !empty($request->state) ? $request->state : null,
            'post_code' => $request->post_code,
            'city' => !empty($request->city) ? $request->city : null, 
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('customer_addresses')->insert($address);
        return response()->json(['message' => 'successfully added!'], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update_address(Request $request, $id=null): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            // 'contact_person_name' => 'required',
            // 'address_type' => 'required',
            // 'contact_person_number' => 'required',
            'address' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        // $address = [
        //     'user_id' => $request->user()->id,
        //     'full_name' => !empty($request->full_name) ? $request->full_name : null,
        //     'contact_person_name' => !empty($request->contact_person_name) ? $request->contact_person_name : null,
        //     'contact_person_number' => !empty($request->contact_person_number) ? $request->contact_person_number : null,
        //     'address_type' => !empty($request->address_type) ? $request->address_type : null,
        //     'address' => !empty($request->address) ? $request->address : null,
        //     'road' => !empty($request->road) ? $request->road : null,
        //     'house' => !empty($request->house) ? $request->house : null,
        //     'floor' => !empty($request->floor) ? $request->floor : null,
        //     'longitude' => !empty($request->longitude) ? $request->longitude : null,
        //     'latitude' => !empty($request->latitude) ? $request->latitude : null,
        //     'country' => !empty($request->country) ? $request->country: null,
        //     'state' => !empty($request->state) ? $request->state : null,
        //     'post_code' => !empty($request->post_code) ? $request->post_code : null,
        //     'city' => !empty($request->city) ? $request->city : null, 
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ];
        $address = [
            'user_id' => $request->user()->id,
            'full_name' => !empty($request->full_name) ? $request->full_name : null,
            'contact_person_name' => !empty($request->contact_person_name) ? $request->contact_person_name : null,
            'contact_person_number' => !empty($request->contact_person_number) ? $request->contact_person_number : null,
            // 'address_type' => !empty($request->address_type) ? $request->address_type : null,
            'address' => !empty($request->address) ? $request->address : null,
            'road' => !empty($request->road) ? $request->road : null, 
            'house' => !empty($request->house) ? $request->house : null,
            'floor' => !empty($request->floor) ? $request->floor : null,
            'longitude' => !empty($request->longitude) ? $request->longitude : null,
            'latitude' => !empty($request->latitude) ? $request->latitude : null,
            'country' => !empty($request->country) ? $request->country : null,
            'state' => !empty($request->state) ? $request->state : null,
            'post_code' => $request->post_code,
            'city' => !empty($request->city) ? $request->city : null, 
            'created_at' => now(),
            'updated_at' => now()
        ];
        if(empty($id)){
            DB::table('customer_addresses')->insert($address);
        }else{
            DB::table('customer_addresses')->where('id',$id)->update($address);
        }
        
        return response()->json(['message' => 'successfully updated!'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete_address(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (DB::table('customer_addresses')->where(['id' => $request['address_id'], 'user_id' => $request->user()->id])->first()) {
            DB::table('customer_addresses')->where(['id' => $request['address_id'], 'user_id' => $request->user()->id])->delete();
            return response()->json(['message' => 'successfully removed!'], 200);
        }
        return response()->json(['message' => 'No such data found!'], 404);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_list(Request $request): JsonResponse
    {
        $orders = $this->order->where(['user_id' => $request->user()->id])->get();
        return response()->json($orders, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_details(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = $this->order_detail->where(['order_id' => $request['order_id']])->get();
        foreach ($details as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }

        return response()->json($details, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function info(Request $request): JsonResponse
    {
       return response()->json($request->user(), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_profile(Request $request): JsonResponse
    {
        
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => ['required', 'unique:users,email,'.auth()->user()->id]
        ], [
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!',
            'email.required' => 'Email is required!',
            'email.unique' => translate('Email must be unique!'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $image = $request->file('image');

        if ($image != null) {
            $data = getimagesize($image);
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . 'png';
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }
            $note_img = Image::make($image)->fit($data[0], $data[1])->stream();
            Storage::disk('public')->put('profile/' . $imageName, $note_img);
        } else {
            $imageName = $request->user()->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 7) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $request->user()->password;
        }

        $userDetails = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'image' => $imageName,
            'password' => $pass,
            'updated_at' => now()
        ];

        $this->user->where(['id' => $request->user()->id])->update($userDetails);

        return response()->json(['message' => 'successfully updated!'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_cm_firebase_token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id',$request->user()->id)->update([
            'cm_firebase_token'=>$request['cm_firebase_token']
        ]);

        return response()->json(['message' => 'successfully updated!'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribe_newsletter(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $newsLetter = $this->newsletter->where('email', $request->email)->first();
        if (!isset($newsLetter)) {
            $newsLetter = $this->newsletter;
            $newsLetter->email = $request->email;
            $newsLetter->save();

            return response()->json(['message' => 'Successfully subscribed'], 200);

        } else {
            return response()->json(['message' => 'Email Already exists'], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_account(Request $request): JsonResponse
    {
        $customer = $this->user->find($request->user()->id);
        if(isset($customer)) {
            Helpers::file_remover('profile/', $customer->image);
            $customer->delete();

        } else {
            return response()->json(['status_code' => 404, 'message' => translate('Not found')], 200);
        }

        $conversations = $this->conversation->where('user_id', $customer->id)->get();
        foreach ($conversations as $conversation){
            if ($conversation->checked == 0){
                $conversation->checked = 1;
                $conversation->save();
            }
        }

        return response()->json(['status_code' => 200, 'message' => translate('Successfully deleted')], 200);
    }

    public function submit_review(Request $request): \Illuminate\Http\JsonResponse
    {
        
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            // 'user_id' => 'required',
            'comment' => 'required',
            'rating' => 'required|numeric|max:5',
        ]);

        $customer = $this->user->find($request->user()->id);
        if (!isset($customer)) {
            $validator->errors()->add('user_id', 'There is no such user!');
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        // $image_array = [];
        // if (!empty($request->file('attachment'))) {
        //     foreach ($request->file('attachment') as $image) {
        //         if ($image != null) {
        //             if (!Storage::disk('public')->exists('review')) {
        //                 Storage::disk('public')->makeDirectory('review');
        //             }
        //             $image_array[] = Storage::disk('public')->put('review', $image);
        //         }
        //     }
        // }
        $review = new $this->product_review();
        // $multi_review = $this->dm_review->where([
        //     'delivery_man_id' => $request->delivery_man_id,
        //     'order_id' => $request->order_id,
        //     'user_id' => $request->user()->id
        // ])->first();
        // if (isset($multi_review)) {
        //     $review = $multi_review;
        // } else {
        //     $review = $this->dm_review;
        // }
        $review->user_id = $request->user()->id;
        // $review->delivery_man_id = $request->delivery_man_id;
        $review->order_id = !empty($request->order_id) ? $request->order_id : [];
        $review->product_id = $request->product_id;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        // $review->attachment = json_encode($image_array);
        $review->save();

        return response()->json(['message' => 'successfully review submitted!'], 200);
    }

    public function get_reviews(Request $request): \Illuminate\Http\JsonResponse
    {        
        $reviews = $this->product_review->with('customer','product')->where(['user_id' => $request->user()->id])->get();        
        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $storage[] = $item;
        }

        return response()->json($storage, 200);
    }

    public function get_rating($id): \Illuminate\Http\JsonResponse
    {
        // try {
            $response = [];
            $totalReviews = $this->product_review->where(['product_id' => $id,'is_active' => 1])->get();
            $AllReviews = $totalReviews;
            $all_ratings = '';
            $ratings = [];
            $ratingPercentage = [];
            if(!empty($AllReviews)){
                $all_ratings = $AllReviews->pluck('rating')->toArray();
                $ratingsCounts = array_count_values($all_ratings);
                $totalRatings = count($ratingsCounts);
                
                for ($i = 1; $i <= 5; $i++) {
                    $ratingPercentage[$i] = isset($ratingsCounts[$i]) ? round(($ratingsCounts[$i] / $totalRatings) * 100,2) : 0;
                }
            }
            $rating = 0;
            foreach ($totalReviews as $key => $review) {
                $rating += $review->rating;
            }

            if ($rating == 0) {
                $overallRating = 0;
            } else {
                $overallRating = number_format($rating / $totalReviews->count(), 2);
            }
            $response['overall_rating'] = floatval($overallRating);
            $response['ratings_details'] = $ratingPercentage;
            return response()->json($response, 200);
            // return response()->json(floatval($overallRating), 200);
        // } catch (\Exception $e) {
        //     return response()->json(['errors' => $e], 403);
        // }
    }

}
