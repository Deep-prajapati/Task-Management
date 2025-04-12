<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\User;
use Illuminate\Http\{
    JsonResponse,
    Request
};
use Illuminate\Support\Facades\{
    Hash,
    Mail,
    Validator
};

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function LogIn(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'email' => ['required', 'email', 'max:255', 'exists:users'],
            'password' => ['required', 'string', 'min:8'],
        ],[
            'email.required' => 'Email is required',
            'email.exists' => 'Email not found | SignUp with Link Below',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {
            if(!User::where('email' , $request->email)->exists())
            {
                return redirect()->json([
                    'status' => false,
                    'message' => 'Email not found',
                    'required' => 'Valid Email',
                    'data' => []
                ], 401);
            }

            $user = User::where('email' , $request->email)->first();
            
            if (!Hash::check($request->password, $user->password))
            {
                return redirect()->json([
                    'status' => false,
                    'message' => 'Invalid credentials',
                    'required' => 'Valid credentials',
                    'data' => []
                ], 401);
            }

            if($user->id_verified == 0)
            {
                $user->otp = rand(1000, 9999);
                $user->expired_at = now()->addMinutes(10);
                $user->save();

                Mail::to($user->email)->send(new \App\Mail\WelcomeVerification($user->name, $user->otp));

                return redirect()->json([
                    'status' => true,
                    'message' => 'OTP send to your email',
                    'required' => 'OTP Verification',
                    'data' => [
                        'otp' => $user->otp
                    ]
                ], 203);
            }

            $user->save();
            $token = $user->createToken(($user->name) != null ? $user->name : $user->email)->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'login Successfully',
                'required' => null,
                'token' => $token,
                'data' => [
                    
                    'user' => $user
                ],
            ], 202);
        } 
        catch (\Throwable $th) 
        {
            return redirect()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'required' => null,
                'data' => []
            ],401);
        }
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function Registration(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
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

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {
            $otp = rand(1000, 9999);
            $expired_at = now()->addMinutes(10);

            Mail::to($request->email)->send(new \App\Mail\WelcomeVerification($request->name, $otp));
            
            $request['otp'] = $otp;
            $request['expired_at'] = $expired_at;

            return response()->json([
                'status' => true,
                'message' => 'OTP send to your email',
                'required' => 'OTP Verification',
                'data' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    'expired_at' => $expired_at,
                    'otp' => $otp,
                    'password' => $request->password
                ]
            ], 202);
        } 
        catch (\Throwable $th) 
        {
            return redirect()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'required' => null,
                'data' => []
            ],401);
        }
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function ForgetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
        ], [
            'email.required' => 'Email is required',
            'email.exists' => 'Email Not Found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {
            $user = User::where('email' , $request->email)->first();

            $user->otp = rand(1000, 9999);
            $user->expired_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new \App\Mail\ResetPassword($user->name, $user->otp));

            return response()->json([
                'status' => true,
                'message' => 'OTP send to your email',
                'required' => 'OTP Verification',
                'data' => [
                    'user' => $user
                ]
            ], 202);
        } 
        catch (\Throwable $th) 
        {
            return redirect()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'required' => null,
                'data' => []
            ],401);
        }
    }

    /**
     * Create a new controller instance.
     * @param string $mode
     * @param Request $request
     * @return JsonResponse
     */
    public function OTPVerification(Request $request, $mode): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'otp' => ['required', 'numeric', 'digits:4'],
            'data.name' => ['required', 'string', 'max:255'],
            'data.email' => ['required', 'string', 'email', 'max:255'],
            'data.otp' => ['required', 'numeric', 'digits:4'],
            'data.expired_at' => ['required'],
            'data.password' => ['required'],
        ],[
            'data.name.required' => 'Name is required',
            'data.email.required' => 'Email is required',
            'data.otp.required' => 'OTP is required',
            'data.expired_at.required' => 'Expired Date Required',
            'data.password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {
            if ($mode == 'welcome') 
            {
                if($request->data['otp'] != $request->otp)
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid OTP',
                        'required' => 'Corect OTP',
                        'data' => []
                    ], 401);
                }

                if($request->data['expired_at'] < now())
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'OTP Expired',
                        'required' => 'Resend OTP',
                        'data' => []
                    ], 401);
                }
                
                if(!User::where('email', $request->data['email'])->exists())
                {
                    User::create([
                        'name' => $request->data['name'],
                        'email' => $request->data['email'],
                        'password' => $request->data['password'],
                        'id_verified' => true,
                        'email_verified_at' => now()->format('Y-m-d H:i:s'),
                    ]);
                }else{
                    User::where('email', $request->data['email'])->update([
                        'id_verified' => true,
                        'email_verified_at' => now()->format('Y-m-d H:i:s')
                    ]);
                }
                $user = User::where('email', $request->data['email'])->first();
                
                $token = $user->createToken(($user->name != null) ? $user->name : $user->email)->plainTextToken;

                return response()->json([
                    'status' => true,
                    'message' => 'Login Successfully',
                    'required' => null,
                    'token' => $token,
                    'data' => [
                        'user' => $user
                    ]
                ], 202);
            }else{

                if($request->data['otp'] != $request->otp)
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid OTP',
                        'required' => 'Corect OTP',
                        'data' => []
                    ], 401);
                }

                if($request->data['expired_at'] < now())
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'OTP Expired',
                        'required' => 'Resend OTP',
                        'data' => []
                    ], 401);
                }
                
                return response()->json([
                    'status' => true,
                    'message' => 'OTP Accepted',
                    'required' => 'Reset Password',
                    'data' => []
                ], 202);
            }
        } 
        catch (\Throwable $th) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'. $th->getMessage(),
                'required' => null,
                'data' => []
            ],401);
        }
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function ResetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
        ], [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Confirm Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password_confirmation.min' => 'Confirm Password must be at least 8 characters',
            'password.same' => 'Password and Confirm Password must be same',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {

            $user = User::where('email' , $request->email)->first();

            $user->password = $request->password;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password Reset Successfully',
                'required' => null,
                'data' => [
                    'user' => $user
                ]
            ], 202);
        } 
        catch (\Throwable $th) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'. $th->getMessage(),
                'required' => null,
                'data' => []
            ],401);
        }
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function ResendOTP(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email is required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {
            if(!User::where('email', $request->email)->exists())
            {
                $data['name'] = 'User';
            }else{
                $user = User::where('email', $request->email)->first();
                $user->otp = rand(1000, 9999);
                $user->expired_at = now()->addMinutes(10);
                $user->save();
                $data['name'] = $user->name;
            }

            $data['otp'] = $user->otp;
            $data['expired_at'] = $user->expired_at;

            Mail::to($request->email)->send(new \App\Mail\WelcomeVerification($data['name'], $data['otp']));

            return response()->json([
                'status' => true,
                'message' => 'OTP Sent Successfully',
                'required' => 'OTP Verification',
                'data' => [
                    'otp' => $data['otp'],
                    'expired_at' => $data['expired_at']
                ]
            ], 202);
        } 
        catch (\Throwable $th) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'required' => null,
                'data' => []
            ],401);
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
                'error' => $this->error_processor($validation)
            ], 406);
        }

        try {
            if(User::where('email', $request->email)->exists())
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Email already exists',
                    'exists' => true
                ]);
            }else{
                return response()->json([
                    'status' => true,
                    'message' => 'Email not exists',
                    'exists' => false
                ]);
            }
        } 
        catch (\Throwable $th) 
        {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => []
            ]);
        }
    }

    /**
     * Create a new controller instance.
     * @param $validator
     * @return array
     */
    private function error_processor($validator): array
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $err_keeper[] = ['code' => $index, 'message' => $error[0]];
        }
        return $err_keeper;
    }
}
