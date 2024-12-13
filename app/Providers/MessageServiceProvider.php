<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Contact;
use SebastianBergmann\Diff\Diff;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Sharing both 'messages' and 'time' globally

    }
    
    private function getTimeDifference()
    {
        // Fetch the latest contact
        $contact = Contact::latest()->first();
    
        if ($contact) {
            // Calculate the difference between the contact time and now
            $time = \Carbon\Carbon::parse($contact->created_at)->diffForHumans();
            return $time;
        }
    
        return 'No contact found';  // In case no contact is found
    }
}
