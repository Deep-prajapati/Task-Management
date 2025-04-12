<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class PanelController extends Controller
{
    /**
     * Create a new controller instance.
     * @return View
     */
    public function Dashboard(): View
    {
        $data = [];

        $data['total'] = Tasks::where(['user_id' => Auth::user()->id])->count();
        $data['completed'] = Tasks::where(['user_id' => Auth::user()->id , 'status' => 1])->count();
        $data['active'] = Tasks::where(['user_id' => Auth::user()->id , 'status' => 0])->wheredate('start_date', '<=', date('Y-m-d'))->wheredate('due_date', '>=', date('Y-m-d'))->count();
        $data['cancel'] = Tasks::where(['user_id' => Auth::user()->id , 'status' => 2])->count();

        return view('panel.dashboard', compact('data'));
    }

    /**
     * Create a new controller instance.
     * @return View
     */
    public function AddTask(): View
    {
        return view('panel.task.add');
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return RedirectResponse
     */
    public function TaskStore(Request $request): RedirectResponse
    {
        $request->validate([
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

        try {
            $task = new Tasks();
            $task->user_id = Auth::user()->id;
            $task->title = $request->name;
            $task->description = $request->description;
            $task->start_date = $request->start;
            $task->due_date = $request->end;
            $task->save();

            flash()->success('Task added successfully');

        } catch (\Throwable $th) {
            flash()->error('Something went wrong'. $th->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return View
     */
    public function TaskList(Request $request): View
    {
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

        return view('panel.task.list', compact('tasks'));
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @return RedirectResponse
     */
    public function TaskStatus($id): RedirectResponse
    {
        if(Tasks::where('id', $id)->exists()){
            $task = Tasks::find($id);
            $task->status = !$task->status;
            $task->save();
        }

        flash()->success('Task status updated successfully');
        return redirect()->back();
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @return View
     */
    public function TaskEdit($id): View
    {
        $task = Tasks::find($id);

        return view('panel.task.edit', compact('task'));
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function TaskUpdate($id, Request $request): RedirectResponse
    {
        $request->validate([
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

        try {
            $task = Tasks::find($id);
            $task->title = $request->name;
            $task->description = $request->description;
            $task->start_date = $request->start;
            $task->due_date = $request->end;
            $task->save();

            flash()->success('Task Updated successfully');

        } catch (\Throwable $th) {
            flash()->error('Something went wrong'. $th->getMessage());
        }
        return redirect()->route('task.list');
    }

    /**
     * Create a new controller instance.
     * @param int $id
     * @return JsonResponse
     */
    public function TaskCancel($id): JsonResponse
    {
        if(Tasks::where('id', $id)->exists()){
            $task = Tasks::find($id);
            $task->status = 2;
            $task->save();

            flash()->success('Task Cancelled');

        }else{
            flash()->error('Task does not exist');
        }
        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return JsonResponse
     */
    public function TaskDelete(Request $request): JsonResponse
    {
        $request->validate([
            'id' => ['required', 'exists:tasks,id'],
        ]);

        if(Tasks::where('id', $request->id)->exists()){
            $task = Tasks::find($request->id);
            $task->delete();

            flash()->success('Task Deleted');

        }else{
            flash()->error('Task does not exist');
        }
        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Create a new controller instance.
     * @return View
     */
    public function Profile(): View
    {
        $user = Auth::user();

        return view('panel.profile', compact('user'));
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return RedirectResponse
     */
    public function ProfileUpdate(Request $request): RedirectResponse
    {
        $request->validate([
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

            flash()->success('Profile Updated successfully');

        } catch (\Throwable $th) {
            flash()->error('Something went wrong'. $th->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Create a new controller instance.
     * @param Request $request
     * @return RedirectResponse
     */
    public function PasswordUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'max:255', 'same:password'],
        ],[
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password does not match',
            'password_confirmation.required' => 'Confirm Password is required',
            'password_confirmation.same' => 'Password does not match',
        ]);

        try {
            $user = User::find(Auth::user()->id);
            $user->password = $request->password;
            $user->save();

            flash()->success('Password Updated successfully');

        } catch (\Throwable $th) {
            flash()->error('Something went wrong');
        }
        return redirect()->back();
    }
}
