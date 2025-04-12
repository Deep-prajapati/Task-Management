<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{
    JsonResponse,
    RedirectResponse,
    Request
};
use Illuminate\Support\Facades\{
    Auth,
    Hash,
    Mail,
    Session,
    Validator
};

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     * @return View
     */
    public function LogIn(): View
    {
        return view('auth.login');
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return RedirectResponse
     */
    public function LogInSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'exists:users'],
            'password' => ['required', 'string', 'min:8'],
        ],[
            'email.required' => 'Email is required',
            'email.exists' => 'Email not found | SignUp with Link Below',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        try {
            if(!User::where('email' , $request->email)->exists())
            {
                flash()->error('Email not found');
                return redirect()->back();
            }

            $user = User::where('email' , $request->email)->first();
            
            if (!Hash::check($request->password, $user->password))
            {
                flash()->error('Invalid credentials');
                return redirect()->back();    
            }

            if($user->id_verified == 0)
            {
                $user->otp = rand(1000, 9999);
                $user->expired_at = now()->addMinutes(10);
                $user->save();

                Mail::to($user->email)->send(new \App\Mail\WelcomeVerification($user->name, $user->otp));

                Session::put('data', $user);

                return redirect()->route('otp.verification', 'welcome');
            }

            if(Auth::attempt(['email' => $request->email , 'password' => $request->password]))
            {
                return redirect()->route('dashboard');
            }else{
                flash()->error('Invalid credentials');
                return redirect()->back();
            }

            
        } 
        catch (\Throwable $th) 
        {
            flash()->error($th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Create a new controller instance.
     * @return View
     */
    public function Register(): View
    {
        return view('auth.signup');
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return RedirectResponse
     */
    public function RegisterSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists | Try to Login',
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Confirm Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password_confirmation.min' => 'Confirm Password must be at least 8 characters',
            'password.same' => 'Password and Confirm Password must be same',
        ]);

        try {
            $otp = rand(1000, 9999);
            $expired_at = now()->addMinutes(10);

            Mail::to($request->email)->send(new \App\Mail\WelcomeVerification($request->name, $otp));
            
            $request['otp'] = $otp;
            $request['expired_at'] = $expired_at;

            Session::put('data', $request->all());

            return redirect()->route('otp.verification', 'welcome');
        } 
        catch (\Throwable $th) 
        {
            flash()->error('Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Create a new controller instance.
     * @return View
     */
    public function ForgetPassword(): View
    {
        return view('auth.forget_password');
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return RedirectResponse
     */
    public function ForgetPasswordSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
        ], [
            'email.required' => 'Email is required',
            'email.exists' => 'Email Not Found',
        ]);

        try {
            $user = User::where('email' , $request->email)->first();

            $user->otp = rand(1000, 9999);
            $user->expired_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new \App\Mail\ResetPassword($user->name, $user->otp));

            Session::put('data', $user);

            return redirect()->route('otp.verification', 'reset');
        } 
        catch (\Throwable $th) 
        {
            flash()->error('Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Create a new controller instance.
     * @param string $mode
     * @return View|RedirectResponse
     */
    public function OTPVerification($mode): View|RedirectResponse
    {
        if(!Session::has('data')) 
        {
            return redirect()->back();
        }
        $data = Session::get('data');
        return view('auth.otp' , compact('mode' , 'data'));
    }

    /**
     * Create a new controller instance.
     * @param string $mode
     * @param Request $request
     * @return RedirectResponse
     */
    public function VerifyOTP(Request $request, $mode): RedirectResponse
    {
        $request->validate([
            'otp.*' => ['required', 'numeric', 'digits:1'],
        ]);

        try {
            if ($mode == 'welcome') 
            {
                $data = Session::get('data');

                if($data['otp'] != implode('', $request->otp))
                {
                    flash()->error('Invalid OTP');
                    return redirect()->back();
                }

                if($data['expired_at'] < now())
                {
                    flash()->error('OTP Expired');
                    return redirect()->back();
                }
                
                if(!User::where('email', $data['email'])->exists())
                {
                    $user = new User();
                    $user->name = $data['name'];
                    $user->email = $data['email'];
                    $user->password = $data['password'];
                    $user->id_verified = true;
                    $user->email_verified_at = now()->format('Y-m-d H:i:s');
                    $user->save();
                }else{
                    User::where('email', $data['email'])->update([
                        'id_verified' => true,
                        'email_verified_at' => now()->format('Y-m-d H:i:s')
                    ]);
                }
                $user = User::where('email', $data['email'])->first();
                

                Auth::login($user);
                Session::forget('data');

                return redirect()->route('dashboard');
            }else{
                $data = Session::get('data');

                if($data['otp'] != implode('', $request->otp))
                {
                    flash()->error('Invalid OTP');
                    return redirect()->back();
                }

                if($data['expired_at'] < now())
                {
                    flash()->error('OTP Expired');
                    return redirect()->back();
                }
                
                return redirect()->route('reset.password');
            }
        } 
        catch (\Throwable $th) 
        {
            flash()->error($th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Create a new controller instance.
     * @return View
     */
    public function ResetPassword(): View
    {
        return view('auth.reset_password');
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return RedirectResponse
     */
    public function ResetPasswordSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
        ], [
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Confirm Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password_confirmation.min' => 'Confirm Password must be at least 8 characters',
            'password.same' => 'Password and Confirm Password must be same',
        ]);

        try {
            if(!Session::has('data'))
            {
                return redirect()->route('panel.login');
            }
            $data = Session::get('data');

            $user = User::where('email' , $data['email'])->first();

            $user->password = $request->password;
            $user->save();
            
            Session::forget('data');
            Auth::login($user);

            return redirect()->route('dashboard');
        } 
        catch (\Throwable $th) 
        {
            flash()->error('Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Create a new controller instance.
     * @param string $email
     * @return RedirectResponse
     */
    public function ResendOTP($email): RedirectResponse
    {
        try {
            if(!Session::has('data'))
            {
                return redirect()->route('panel.login');
            }

            $data = Session::get('data');

            $data['otp'] = rand(1000, 9999);
            $data['expired_at'] = now()->addMinutes(10);

            Mail::to($email)->send(new \App\Mail\WelcomeVerification($data['name'], $data['otp']));

            Session::put('data', $data);

            flash()->success('OTP sent successfully');
            return redirect()->back();
        } 
        catch (\Throwable $th) 
        {
            flash()->error('Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function CheckEmail(Request $request): JsonResponse
    {
        $validation = Validator::make( $request->all(), [
           'email' => ['required', 'email'],
        ]);

        if($validation->fails())
        {
            return response()->json([
                'status' => false,
                'error' => $validation->errors()->first()
            ]);
        }

        try {
            if(User::where('email', $request->email)->exists())
            {
                return response()->json([
                    'status' => true,
                    'exists' => true
                ]);
            }else{
                return response()->json([
                    'status' => true,
                    'exists' => false
                ]);
            }
        } 
        catch (\Throwable $th) 
        {
            return response()->json([
                'status' => false,
                'exists' => false
            ]);
        }
    }

    /**
     * Create a new controller instance.
     * @return View
     */
    public function TeamsAndConditions(): View
    {
        return view('_pages.terms_condition');
    }

    /**
     * Create a new controller instance.
     * @return RedirectResponse
     */
    public function Logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('panel.login');
    }
}
