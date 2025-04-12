<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PanelController extends Controller
{
    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function Dashboard(Request $request): JsonResponse
    {
        $data = [];

        $data['total'] = Tasks::where(['user_id' => Auth::user()->id])->count();
        $data['completed'] = Tasks::where(['user_id' => Auth::user()->id , 'status' => 1])->count();
        $data['active'] = Tasks::where(['user_id' => Auth::user()->id , 'status' => 0])->wheredate('start_date', '<=', date('Y-m-d'))->wheredate('due_date', '>=', date('Y-m-d'))->count();
        $data['cancel'] = Tasks::where(['user_id' => Auth::user()->id , 'status' => 2])->count();

        return response()->json([
            'status' => true,
            'message' => 'Dashboard',
            'data' => $data
        ]);
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function TaskStore(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
        ],[
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'start.required' => 'Start Date is required',
            'end.required' => 'End Date is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {
            $task = new Tasks();
            $task->user_id = Auth::user()->id;
            $task->title = $request->name;
            $task->description = $request->description;
            $task->start_date = $request->start;
            $task->due_date = $request->end;
            $task->save();

            return response()->json([
                'status' => true,
                'message' => 'Task Created successfully',
                'data' => $task
            ], 202);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => []
            ],401);
        }
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function TaskList(Request $request): JsonResponse
    {
        try {
            $tasks = Tasks::where('user_id', Auth::user()->id)->orderBy('id', 'desc');

            if($request->has('status') && $request->status != '')
            {
                $tasks = $tasks->where('status', '=', $request->status);
            }

            if($request->has('start') && $request->start != '')
            {
                $tasks = $tasks->where('start_date', '=', $request->start);
            }

            if($request->has('due') && $request->due != '')
            {
                $tasks = $tasks->where('due_date', '=', $request->due);
            }

            $tasks = $tasks->get();

            return response()->json([
                'status' => true,
                'message' => 'Task List',
                'data' => $tasks
            ], 202);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => []
            ],401);
        }
        
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @return JsonResponse
     */
    public function TaskStatus($id): JsonResponse
    {
        if(Tasks::where(['id' => $id , 'user_id' => Auth::user()->id])->exists()){
            $task = Tasks::find($id);
            $task->status = !$task->status;
            $task->save();
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Task does not exist',
                'data' => []
            ],406);
        }

        return response()->json([
            'status' => true,
            'message' => 'Task status updated successfully',
            'data' => []
        ], 202);
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @return JsonResponse
     */
    public function TaskEdit($id): JsonResponse
    {
        if(Tasks::where(['id' => $id , 'user_id' => Auth::user()->id])->exists()){
            $task = Tasks::find($id);

            return response()->json([
                'status' => true,
                'message' => 'Task Edit',
                'data' => $task
            ], 202);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Task does not exist',
                'data' => []
            ],406);
        }
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function TaskUpdate($id, Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
        ],[
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'start.required' => 'Start Date is required',
            'end.required' => 'End Date is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        if(!Tasks::where(['id' => $id , 'user_id' => Auth::user()->id])->exists()){
            return response()->json([
                'status' => false,
                'message' => 'Task does not exist',
                'data' => []
            ],406);
        }

        try {
            $task = Tasks::find($id);
            $task->title = $request->name;
            $task->description = $request->description;
            $task->start_date = $request->start;
            $task->due_date = $request->end;
            $task->save();

            return response()->json([
                'status' => true,
                'message' => 'Task Updated successfully',
                'data' => $task
            ], 202);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => []
            ], 401);
        }
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @return JsonResponse
     */
    public function TaskCancel($id): JsonResponse
    {
        if(Tasks::where(['id' => $id , 'user_id' => Auth::user()->id])->exists())
        {
            $task = Tasks::find($id);
            $task->status = 2;
            $task->save();

            return response()->json([
                'status' => true,
                'message' => 'Task Cancelled successfully',
                'data' => $task
            ], 202);

        }else{
            return response()->json([
                'status' => false,
                'message' => 'Task does not exist',
                'data' => []
            ],406);
        }
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @return JsonResponse
     */
    public function TaskDelete($id): JsonResponse
    {
        if(Tasks::where(['id' => $id , 'user_id' => Auth::user()->id])->exists())
        {
            $task = Tasks::find($id);
            $task->delete();

            return response()->json([
                'status' => true,
                'message' => 'Task Deleted successfully',
                'data' => $task
            ], 202);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Task does not exist',
                'data' => []
            ],406);
        }
    }

    /**
     * Create a new controller instance.
     * @return JsonResponse
     */
    public function Profile(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Profile',
            'data' => Auth::user()
        ]);
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function ProfileUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255' , 'unique:users,email,'.Auth::user()->id],
            'image' => ['image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ],[
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'image.required' => 'Image is required',
            'image.mimes' => 'Image must be jpg, png, jpeg',
            'image.max' => 'Image must be less than 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {
            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            
            if($request->hasFile('image'))
            {
                if(File::exists($user->avtar)){
                    File::delete($user->avtar);
                }
                $image = $request->file('image');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
                $user->avtar = 'images/'.$name;
            }
            $user->save();
            
            return response()->json([
                'status' => true,
                'message' => 'Profile Updated successfully',
                'data' => $user
            ], 202);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => []
            ], 401);
        }
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function PasswordUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make( $request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'max:255', 'same:password'],
        ],[
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password does not match',
            'password_confirmation.required' => 'Confirm Password is required',
            'password_confirmation.same' => 'Password does not match',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $this->error_processor($validator)
            ], 406);
        }

        try {
            $user = User::find(Auth::user()->id);
            $user->password = $request->password;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password Updated successfully',
                'data' => $user
            ], 202);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => []
            ], 401);
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
