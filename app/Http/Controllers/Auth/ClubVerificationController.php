<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class ClubVerificationController extends Controller
{
    /**
     * Show verification form for admin creating club
     */
    public function showAdminVerificationForm()
    {
        if (!session()->has('club_data')) {
            return redirect()->route('clubs.create')
                ->withErrors(['email' => 'Please create a club first to verify the email.']);
        }
        
        return view('auth.admin-club-verify-email')
            ->with('status', session('status'));
    }
    
    /**
     * Verify the club email as admin
     */
    public function verifyAdminClubEmail(Request $request)
    {
        // Log the received data for debugging
        \Illuminate\Support\Facades\Log::info('Received verification request', [
            'verification_code' => $request->verification_code,
            'has_session_data' => session()->has('club_data'),
            'has_verification_code' => session()->has('verification_code'),
        ]);
        
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'verification_code' => 'required|string|size:6',
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        
        if (!session()->has('club_data')) {
            \Illuminate\Support\Facades\Log::warning('Club verification attempted but no club_data in session');
            return redirect()->route('clubs.create')
                ->withErrors(['email' => 'Please create a club first to verify the email.']);
        }
        
        $expiresAt = session('verification_expires_at');
        
        if (!$expiresAt || Carbon::now()->gt(Carbon::parse($expiresAt))) {
            \Illuminate\Support\Facades\Log::warning('Verification code expired');
            session()->forget(['club_data', 'verification_code', 'verification_expires_at']);
            return redirect()->route('clubs.create')->withErrors(['verification_code' => 'The verification code has expired. Please create the club again.']);
        }
        
        if ($request->verification_code == session('verification_code')) {
            $data = session('club_data');
            
            \Illuminate\Support\Facades\Log::info('Verification code correct - proceeding with club creation');
            
            // Check if we have all required fields
            $requiredFields = ['name', 'email', 'password', 'admin_id'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                \Illuminate\Support\Facades\Log::error('Missing required fields for club creation: ' . implode(', ', $missingFields));
                return back()->withErrors(['general' => 'Missing required club data: ' . implode(', ', $missingFields)]);
            }
            
            try {
                \Illuminate\Support\Facades\DB::beginTransaction();
                
                \Illuminate\Support\Facades\Log::info('Attempting to create club with data: ' . json_encode(array_keys($data)));
                
                $club = Club::create([
                    ...$data,
                    'email_verified' => true,
                    'email_verified_at' => now(),
                ]);
                
                if (!$club || !$club->id) {
                    throw new \Exception('Club was not created properly');
                }
                
                \Illuminate\Support\Facades\Log::info('Club created successfully with ID: ' . $club->id);
                
                session()->forget(['club_data', 'verification_code', 'verification_expires_at']);
                
                $resetLinkSent = \App\Http\Controllers\Auth\ClubPasswordResetHandlerController::sendPasswordResetLink($club);
                
                \Illuminate\Support\Facades\DB::commit();
                
                $message = 'Club created successfully and email verified.';
                if ($resetLinkSent) {
                    $message .= ' A password reset link has been sent to the club\'s email.';
                }
                
                return redirect()->route('clubs.show', $club->encoded_id)
                    ->with('success', $message);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                \Illuminate\Support\Facades\Log::error('Failed to create club: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
                return back()->withErrors(['general' => 'Failed to create club: ' . $e->getMessage()]);
            }
        }
        
        \Illuminate\Support\Facades\Log::warning('Invalid verification code provided');
        return back()->withErrors(['verification_code' => 'Invalid verification code. Please try again.']);
    }
    
    /**
     * Resend the verification code for admin creating club
     */
    public function resendAdminVerificationCode()
    {
        if (!session()->has('club_data')) {
            return redirect()->route('clubs.create')
                ->withErrors(['email' => 'Please create a club first to verify the email.']);
        }
        
        $data = session('club_data');
        $verificationCode = rand(100000, 999999);
        
        session([
            'verification_code' => $verificationCode,
            'verification_expires_at' => now()->addMinutes(30),
        ]);
        
        Mail::send('emails.club-verification-code', ['verificationCode' => $verificationCode], function ($message) use ($data) {
            $message->to($data['email'])
                ->subject('ProGymHub Club Account Verification');
        });
        
        return redirect()->back()
            ->with('status', 'A new verification code has been sent to the club\'s email (' . $data['email'] . ').');
    }
    
    /**
     * Send verification code to the club's email
     * For existing clubs that need verification
     *
     * @param  Club  $club
     * @return bool
     */
    public static function sendVerificationCode(Club $club)
    {
        try {
            $verificationCode = rand(100000, 999999);
            
            $expiresAt = now()->addMinutes(30);
            
            $club->update([
                'verification_code' => $verificationCode,
                'verification_code_expires_at' => $expiresAt,
                'email_verified' => false,
                'email_verified_at' => null
            ]);
            
            Mail::send('emails.club-verification-code', ['verificationCode' => $verificationCode], function ($message) use ($club) {
                $message->to($club->email)
                    ->subject('ProGymHub Club Account Verification');
            });
            
            \Illuminate\Support\Facades\Log::info("Verification code sent to club: {$club->email}");
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send verification code to club: {$club->email}. Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Show verification form for existing club
     */
    public function showVerificationForm($encoded_id)
    {
        $club = Club::findByEncodedId($encoded_id);
        
        if (!$club) {
            return redirect()->route('home')
                ->with('error', 'Club not found.');
        }
        
        if ($club->email_verified) {
            return redirect()->route('club.dashboard')
                ->with('info', 'This email is already verified.');
        }
        
        return view('auth.club-verify-email', compact('club'));
    }
    
    /**
     * Verify the email for existing club
     */
    public function verifyEmail(Request $request, $encoded_id)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);
        
        $club = Club::findByEncodedId($encoded_id);
        
        if (!$club) {
            return redirect()->route('home')
                ->with('error', 'Club not found.');
        }
        
        if (Carbon::now()->gt(Carbon::parse($club->verification_code_expires_at))) {
            self::sendVerificationCode($club);
            
            return redirect()->back()
                ->with('error', 'The verification code has expired. A new code has been sent to your email.');
        }
        
        if ($request->verification_code == $club->verification_code) {
            $club->update([
                'email_verified' => true,
                'email_verified_at' => now(),
                'verification_code' => null,
                'verification_code_expires_at' => null,
            ]);
            
            $resetLinkSent = \App\Http\Controllers\Auth\ClubPasswordResetHandlerController::sendPasswordResetLink($club);
            
            $message = 'Email verified successfully. Your account is now active.';
            if ($resetLinkSent) {
                $message .= ' A password reset link has been sent to your email to set your password.';
            }
            
            return redirect()->route('club.dashboard')
                ->with('success', $message);
        }
        
        return redirect()->back()
            ->withErrors(['verification_code' => 'Invalid verification code. Please try again.']);
    }
    
    /**
     * Resend the verification code for existing club
     */
    public function resendCode($encoded_id)
    {
        $club = Club::findByEncodedId($encoded_id);
        
        if (!$club) {
            return redirect()->route('home')
                ->with('error', 'Club not found.');
        }
        
        if ($club->email_verified) {
            return redirect()->route('club.dashboard')
                ->with('info', 'This email is already verified.');
        }
        
        if (self::sendVerificationCode($club)) {
            return redirect()->back()
                ->with('success', 'A new verification code has been sent to your email.');
        }
        
        return redirect()->back()
            ->with('error', 'Failed to send verification code. Please try again later.');
    }
}
