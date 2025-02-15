<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Show;
use App\Models\Price;
use App\Models\Billing;
use Mpdf\Mpdf;

class BillingController extends Controller
{
    
    public function create(Request $request)
    {
        // Retrieve movie_id and seat_type from the session or request
        $movieId = session('movie_id') ?? $request->query('movie_id');
        $seatType = session('seat_type') ?? $request->query('seat_type');

        if (!$movieId || !$seatType) {
            return redirect()->route('booking.index')->withErrors('Missing booking information.');
        }

        // Fetch the Show based on movie_id
        $show = Show::find($movieId);
        if (!$show) {
            return redirect()->route('booking.index')->withErrors('Show not found.');
        }

        // Fetch the price row where movie_code and seat_type match
        $price = Price::where('movie_code', $show->movie_code)
            ->where('seat_type', $seatType)
            ->first(); // Retrieve the single matching price

        if (!$price) {
            return redirect()->back()->withErrors('Price information not found for the selected movie and seat type.');
        }

        // Pass the data to the billing view
        return view('billings.create', [
            'show' => $show,
            'price' => $price, // Pass the matched price object
        ]);
    }


    public function store(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'booking_id' => 'required|exists:bookings,id',
        'movie_id' => 'required|exists:shows,id',
        'movie_name' => 'required|string',
        'date' => 'required',
        'time' => 'required',
        'seat_type' => 'required|string',
        'total_tickets' => 'required|integer|min:1',
        'full_tickets' => 'required|integer|min:0',
        'half_tickets' => 'required|integer|min:0',
        'total_price' => 'required|numeric|min:0',
        'bookingIds'     => 'nullable',
    ]);

    try {
        // Store billing data
        Billing::create([
            'booking_id' => $validated['booking_id'],
            'movie_id' => $validated['movie_id'],
            'movie_name' => $validated['movie_name'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'seat_type' => $validated['seat_type'],
            'total_tickets' => $validated['total_tickets'],
            'full_tickets' => $validated['full_tickets'],
            'half_tickets' => $validated['half_tickets'],
            'total_price' => $validated['total_price'],
             
        ]);

        if (!empty($validated['bookingIds'])) {
            try {
                // Make sure bookingIds is a string for your existing signature
                $this->generateTickets($request, $validated['bookingIds']);
            } catch (\Exception $printEx) {
                // Log error, but don't break the billing flow
                Log::error("Ticket printing failed: " . $printEx->getMessage());
            }
        }

        // Redirect to the 'selectSeats' route
        return redirect()->route('booking.selectSeats', ['id' => $validated['movie_id']])
            ->with('success', 'Booking confirmed and tickets printed!. Please proceed to select your seats.');
    } catch (\Exception $e) {
        // Log error and redirect back with an error message
        Log::error('Billing Store Error: ', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'validated' => $validated,
        ]);

        return redirect()->back()->withErrors('Failed to store billing data.')->withInput();
    }
}


    public function index(){
        // Fetch all billing data
        $billings = Billing::all();

        return view('billings.index', [
            'billings' => $billings,
        ]);
    }

    public function detail($id)
    {
        // Fetch the billing data by ID
        $billing = Billing::find($id);

        if (!$billing) {
            return redirect()->route('billing.index')->withErrors('Billing data not found.');
        }

        return view('billings.detail', [
            'billing' => $billing,
        ]);
    }

    public function edit($id)
    {
        // Fetch the billing data by ID
        $billing = Billing::find($id);

        if (!$billing) {
            return redirect()->route('billing.index')->withErrors('Billing data not found.');
        }

        return view('billings.edit', [
            'billing' => $billing,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'movie_id' => 'required|exists:shows,id',
            'movie_name' => 'required|string',
            'date' => 'required',
            'time' => 'required',
            'seat_type' => 'required|string',
            'total_tickets' => 'required|integer|min:1',
            'full_tickets' => 'required|integer|min:0',
            'half_tickets' => 'required|integer|min:0',
            'total_price' => 'required',
        ]);

        try {
            // Fetch the billing data by ID
            $billing = Billing::find($id);

            if (!$billing) {
                return redirect()->route('billing.index')->withErrors('Billing data not found.');
            }

            // Update the billing data
            $billing->update($validated);

            return redirect()->route('billing.index')->with('success', 'Billing data updated successfully.');
        } catch (\Exception $e) {
            Log::error('Billing Update Error: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'validated' => $validated,
            ]);
            return redirect()->back()->withErrors('Failed to update billing data.')->withInput();
        }
    }

    public function destroy($id){
        // Fetch the billing data by ID
        $billing = Billing::find($id);

        if (!$billing) {
            return redirect()->route('billing.index')->withErrors('Billing data not found.');
        }

        // Delete the billing data
        $billing->delete();

        return redirect()->route('billing.index')->with('success', 'Billing data deleted successfully.');
    }

    public function generateTickets(Request $request, $bookingIds)
    {
        try {
            $seatFilter = $request->input('seat_codes', []);
            $bookingIdsArray = explode(',', $bookingIds);

            $bookings = Booking::whereIn('id', $bookingIdsArray)
                ->when(!empty($seatFilter), function ($query) use ($seatFilter) {
                    $query->whereIn('seat_code', $seatFilter);
                })
                ->get();

            if ($bookings->isEmpty()) {
                return response()->json(['error' => 'No bookings found.'], 404);
            }

            // Configure mPDF with custom font directory
            $mpdfConfig = [
                'format' => [80, 150],
                'margin_top' => 0,
                'margin_right' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
                'tempDir' => storage_path('app/mpdf'), // Temporary directory for mPDF
                'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                    public_path('fonts'),
                ]),
                'fontdata' => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], [
                    'maturascript' => [
                        'R' => 'MATURASC_1.ttf', // Regular font
                    ],

                    'Montserratt' => [
                        'R' => 'Montserratt.ttf', // Regular font
                    ],

                    'Cinzel' => [
                        'R' => 'Cinzel.ttf', // Regular font
                    ],
                    

                    'latha' => [
                        'R' => 'latha.ttf',
                    ],
                ]),
                'default_font' => 'latha',
            ];

            $mpdf = new \Mpdf\Mpdf($mpdfConfig);

            $ticketHtml = '';

            foreach ($bookings as $booking) {

                // Path to the image
                $logoPath = public_path('icons/alertmsg.png'); 

                $html = "
                    <div style='width: 100%; text-align: center; font-family: Arial, sans-serif; padding: 0px;'>
                        <p style='font-size: 8px; margin:0;'>.</p>    
                        <p style='font-size: 10px; margin: 3px;'>DEL LANKA ADVANCED TICKETBOOKING</p>
                        <h2 style='font-size: 18px; font-family: maturascript; margin:0;'>Sellam Premier</h2>
                        <p style='margin: 5px 0; margin:0;'>3D Digital Cinema</p>
                        <p style='margin: 5px 0; margin:0;'>Chenkalady, Batticaloa</p>
                        <p style='margin: 5px 0; margin:0;'>TP: 065-2240064</p>
                        <hr style='border: 1px dashed #000; margin:5px;'>

                        <p style='font-size: 10px; padding-left:40px; margin:0; text-align: left;'>Serial #: {$booking->id} - - - - - Issued by: " . Auth::user()->name . "</p>
                        <p style='font-size: 10px; padding-left:40px; margin:5px; text-align: left;'>Date: " . date('d-M-Y h:i A') . "</p>
                        <hr style='border: 1px dashed #000; margin:0;'>

                        <p style='font-size: 14px; margin: 5px;'><strong>Movie Date:</strong> {$booking->date}</p>
                        <p style='font-size: 14px; margin: 0;'><strong>Movie Time:</strong> {$booking->time}</p>
                        <hr style='border: 1px dashed #000; margin:0;'>

                        <p style='font-size: 22px; margin: 5px; font-family: Montserratt; text-transform: uppercase;'><strong> {$booking->movie_name}</strong></p>
                        <p style='font-size: 26px; margin: 5px; font-family: Montserratt; text-transform: uppercase;'><strong> {$booking->seat_type}</strong></p>
                        <hr style='border: 1px dashed #000;'>

                        <p style='font-size: 20px; margin: 0;'>SEAT NO: <strong>{$booking->seat_no}</strong> .  .  . PAID</p>
                        <hr style='border: 1px dashed #000;'>
                        <img src='{$logoPath}' alt='Logo' style='width: 300px; height: auto; margin-bottom: 5px;' />
                        <hr style='border: 1px dashed #000;'>

                        <p style='font-size: 10px; margin:0;'>Software Developed By : ForcrafTech Solutions(FTS)</p>
                        <p style='font-size: 10px; margin:0;'>076-2646376</p>
                    </div>";

                    $ticketHtml .= $html . "<div></div>";
            }

            $mpdf->WriteHTML($ticketHtml);

            // Save the PDF
            $tempFilePath = storage_path('app/temp_tickets.pdf');
            $mpdf->Output($tempFilePath, 'F');

            // Ensure file path uses double backslashes for Windows
            $windowsFilePath = str_replace('/', '\\', $tempFilePath);

            // PowerShell Command for Printing
            $command = "cmd /c powershell -Command \"Start-Process -FilePath '$windowsFilePath' -Verb Print -ArgumentList 'NoScaling'\"";

            // Execute print command
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception("Printing failed: " . implode("\n", $output));
            }

            // If the request is AJAX, return a JSON response; otherwise, redirect back
        if ($request->ajax()) {
            return response()->json([
                'success' => 'Tickets printed successfully!',
                'filePath' => realpath($tempFilePath)
            ], 200);
        } else {
            return redirect()->route('booking.reaction')->with('success', 'Tickets printed successfully!');
        }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate tickets: ' . $e->getMessage()], 500);
        }
    }


    public function deleteTicket(Request $request)
{
    try {
        $filePath = $request->input('filePath'); // Get file path from request

        if (!$filePath || !file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Ensure the file is not in use before deleting
        sleep(3); // Add a short delay to prevent deletion errors

        unlink($filePath); // Delete the file

        return response()->json(['success' => 'Ticket file deleted successfully!'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete ticket: ' . $e->getMessage()], 500);
    }
}



}



