<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index($tableId)
    {

        $table = Table::with('restaurant')->where('table_code', $tableId)->firstOrFail();

        $restaurant = $table->restaurant;

        return view('customer.contact', [
            'tableCode' => $table->table_code,
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'logo_url' => $restaurant->logo ?? asset('/logo-2.png'),
                'photos' => $restaurant->photos ?? [],
                'video_url' => $restaurant->video_url ?? null,
                'manager_whatsapp' => $restaurant->manager_whatsapp,
                'owner_whatsapp' => $restaurant->owner_whatsapp,
                'cashier_whatsapp' => $restaurant->cashier_whatsapp,
                'supervisor_whatsapp' => $restaurant->supervisor_whatsapp,
                'allow_call_manager' => $restaurant->allow_call_manager,
                'allow_call_owner' => $restaurant->allow_call_owner,
                'allow_call_cashier' => $restaurant->allow_call_cashier,
                'allow_call_supervisor' => $restaurant->allow_call_supervisor,
                'currency_symbol' => $restaurant->currency,
            ],
        ]);
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $user = auth()->user();
        $name = $user->name;
        $email = $user->email;

        // Send email to admin/support
        Mail::raw("Message from: {$name} ({$email})\n\n{$validated['message']}", function ($message) {
            $message->to('support@pingwaiter.com')
                ->subject('Customer Contact Message');
        });

        return back()->with('success', 'Your message has been sent successfully.');
    }

    // Handle admin bulk email
    public function sendBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|email',
            'bcc' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // max 10MB each
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $emails = array_filter(explode(',', $request->input('bcc')));

        foreach ($emails as $email) {
            Mail::send([], [], function ($message) use ($request, $email) {
                $message->from($request->input('from'), 'Admin')
                    ->to($request->input('from')) // sends to self
                    ->bcc($email)
                    ->subject($request->input('subject'))
                    ->setBody($request->input('body'), 'text/html');

                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $message->attach($file->getRealPath(), [
                            'as' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                        ]);
                    }
                }
            });
        }

        return back()->with('success', 'Bulk email sent successfully to selected customers.');
    }
}
