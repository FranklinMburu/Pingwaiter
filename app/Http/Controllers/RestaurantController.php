<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RestaurantController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $restaurant = $user->restaurant;

        $currencies = currency()->getCurrencies();

        return view('restaurant.index', [
            'restaurant' => $restaurant,
            'currencies' => $currencies,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'location' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'email' => 'nullable|email',
            'owner_name' => 'nullable|string',
            'owner_whatsapp' => 'nullable|string',
            'manager_name' => 'nullable|string',
            'manager_whatsapp' => 'nullable|string',
            'cashier_name' => 'nullable|string',
            'cashier_whatsapp' => 'nullable|string',
            'supervisor_name' => 'nullable|string',
            'website' => 'nullable|string',
            'supervisor_whatsapp' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'picture.*' => 'nullable|image',
            'picture' => 'nullable|array',
            'video' => 'nullable|string|max:255',
            'remove_logo' => 'nullable|boolean',
            'remove_pictures' => 'nullable|string',
            'remove_video' => 'nullable|boolean',
            'instagram_link' => 'nullable|url|max:255',
            'facebook_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
        ]);

        $id = isset(auth()->user()->restaurant->id) ? auth()->user()->restaurant->id : '';
        $adminEmailsArray = $request->input('admin_email');

        // Handle admin email updates
        if ($id) {
            $this->handleAdminEmails($id, $request);
        }

        $restaurant = Restaurant::updateOrCreate(
            ['id' => $id],
            [
                'name' => $request->name,
                'address' => $request->address,
                'location' => $request->location,
                'phone_number' => $request->phone_number,
                'whatsapp_number' => $request->whatsapp_number,
                'email' => $request->email,
                'owner_name' => $request->owner_name,
                'owner_whatsapp' => $request->owner_whatsapp,
                'manager_name' => $request->manager_name,
                'manager_whatsapp' => $request->manager_whatsapp,
                'cashier_name' => $request->cashier_name,
                'cashier_whatsapp' => $request->cashier_whatsapp,
                'supervisor_name' => $request->supervisor_name,
                'supervisor_whatsapp' => $request->supervisor_whatsapp,
                'website' => $request->website,
                'admin_email' => $adminEmailsArray,
                'description' => $request->description,
                'allow_place_order' => $request->has('allow_place_order'),
                'allow_call_owner' => $request->has('allow_call_owner'),
                'allow_call_manager' => $request->has('allow_call_manager'),
                'allow_call_cashier' => $request->has('allow_call_cashier'),
                'allow_call_supervisor' => $request->has('allow_call_supervisor'),
                'instagram_link' => $request->instagram_link,
                'facebook_link' => $request->facebook_link,
                'twitter_link' => $request->twitter_link,
                'video' => $request->video,
                'currency_symbol' => $request->currency_symbol,
            ]
        );

        // Handle media updates
        $this->handleMediaUpdates($restaurant, $request);

        $restaurant->save();

        // Handle admin user creation/updates
        $this->handleAdminUsers($restaurant, $request);

        return redirect()->back()->with('success', 'Restaurant information updated successfully');
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_whatsapp' => 'required|string|max:20',
            'manager_name' => 'required|string|max:255',
            'manager_whatsapp' => 'required|string|max:20',
            'cashier_name' => 'required|string|max:255',
            'cashier_whatsapp' => 'required|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'supervisor_whatsapp' => 'required|string|max:20',
            'website' => 'required|url|max:255',
            'currency_symbol' => 'required|string|max:10',
            'logo' => 'nullable|image|max:2048',
            'picture.*' => 'nullable|image',
            'picture' => 'nullable|array',
            'video' => 'nullable|string|max:255',
            'google_maps_link' => 'nullable|url|max:255',
            'google_review_link' => 'nullable|url|max:255',
            'opening_hours' => 'nullable|string|max:255',
            // 'notify_customer' => 'nullable|boolean',
            'instagram_link' => 'nullable|url|max:255',
            'facebook_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'remove_logo' => 'nullable|boolean',
            'remove_pictures' => 'nullable|string',
            'remove_video' => 'nullable|boolean',
        ]);

        $adminEmailsArray = $request->input('admin_email');

        // Update restaurant fields
        $restaurant->update([
            'name' => $request->name,
            'address' => $request->address,
            'location' => $request->location,
            'phone_number' => $request->phone_number,
            'whatsapp_number' => $request->whatsapp_number,
            'email' => $request->email,
            'owner_name' => $request->owner_name,
            'owner_whatsapp' => $request->owner_whatsapp,
            'manager_name' => $request->manager_name,
            'manager_whatsapp' => $request->manager_whatsapp,
            'cashier_name' => $request->cashier_name,
            'cashier_whatsapp' => $request->cashier_whatsapp,
            'supervisor_name' => $request->supervisor_name,
            'supervisor_whatsapp' => $request->supervisor_whatsapp,
            'website' => $request->website,
            'admin_email' => $adminEmailsArray,
            'description' => $request->description,
            'allow_place_order' => $request->has('allow_place_order'),
            'allow_call_owner' => $request->has('allow_call_owner'),
            'allow_call_manager' => $request->has('allow_call_manager'),
            'allow_call_cashier' => $request->has('allow_call_cashier'),
            'allow_call_supervisor' => $request->has('allow_call_supervisor'),
            'currency_symbol' => $request->currency_symbol,
            'rewards_per_dollar' => $request->rewards_points,
            'google_maps_link' => $request->google_maps_link,
            'google_review_link' => $request->google_review_link,
            'opening_hours' => $request->opening_hours,
            'notify_customer' => $request->has('notify_customer'),
            'instagram_link' => $request->instagram_link,
            'facebook_link' => $request->facebook_link,
            'twitter_link' => $request->twitter_link,
            'video' => $request->video,
        ]);

        // Handle media updates
        $this->handleMediaUpdates($restaurant, $request);

        $restaurant->save();

        // Handle admin email deletions
        $this->handleAdminEmails($restaurant->id, $request);

        // Handle admin account creation
        $this->handleAdminUsers($restaurant, $request);

        return redirect()->back()->with('success', 'Restaurant updated successfully.');
    }

    private function handleMediaUpdates($restaurant, $request)
    {
        // Handle logo removal and update
        if ($request->remove_logo == '1') {
            $this->removeFile($restaurant->logo, 'logos');
            $restaurant->logo = null;
        }
        if ($request->hasFile('logo')) {
            $this->removeFile($restaurant->logo, 'logos');
            $restaurant->logo = $this->uploadFile($request->file('logo'), 'logos');
        }

        // Handle pictures removal and update
        if ($request->remove_pictures) {
            $existingPictures = json_decode($restaurant->picture, true) ?: [];
            $removedIndices = explode(',', $request->remove_pictures);

            foreach ($removedIndices as $index) {
                if (isset($existingPictures[$index])) {
                    $this->removeFile($existingPictures[$index], 'pictures');
                    unset($existingPictures[$index]);
                }
            }
            $restaurant->picture = json_encode(array_values($existingPictures));
        }
        if ($request->hasFile('picture')) {
            $existingPictures = json_decode($restaurant->picture, true) ?: [];
            foreach ($request->file('picture') as $image) {
                $existingPictures[] = $this->uploadFile($image, 'pictures');
            }
            $restaurant->picture = json_encode($existingPictures);
        }

        // Handle video removal and update
        if ($request->remove_video == '1') {
            $this->removeFile($restaurant->video, 'videos');
            $restaurant->video = null;
        }
        if ($request->hasFile('video')) {
            $this->removeFile($restaurant->video, 'videos');
            $restaurant->video = $this->uploadFile($request->file('video'), 'videos');
        }
    }

    private function uploadFile($file, $folder)
    {
        $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $destinationPath = public_path("/uploads/restaurant/{$folder}");

        if (! File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }

        $file->move($destinationPath, $name);

        return $name;
    }

    private function removeFile($filename, $folder)
    {
        if ($filename) {
            $path = public_path("/uploads/restaurant/{$folder}/".$filename);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }

    private function handleAdminEmails($id, $request)
    {
        $restaurant = Restaurant::find($id);
        $adminEmails = json_decode($restaurant->admin_email, true);

        if (is_array($adminEmails)) {
            $newEmails = $request->input('admin_email', []);
            $emailsToDelete = array_diff($adminEmails, $newEmails);

            foreach ($emailsToDelete as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    User::where('email', $email)->delete();
                }
            }
        }
    }

    private function handleAdminUsers($restaurant, $request)
    {
        if ($request->has('admin_email') && is_array($request->input('admin_email'))) {
            foreach ($request->input('admin_email') as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    User::updateOrCreate(
                        ['email' => $email],
                        [
                            'name' => 'Admin',
                            'role' => 'restaurant',
                            'restaurant_id' => $restaurant->id,
                            'password' => bcrypt('default_password'), // Consider generating & emailing a secure password
                        ]
                    );
                }
            }
        }
    }
}
