<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Mail;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    /*
    Laravel 会自动解析定义在控制器方法（变量名匹配路由片段）中的 Eloquent 模型类型声明。
    在上面代码中，由于 show() 方法传参时声明了类型 —— Eloquent 模型 User，对应的变量名
     $user 会匹配路由片段中的 {user}，这样，Laravel 会自动注入与请求 URI 中传入的 ID
     对应的用户模型实例。
    */
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    /*
    处理用户创建的相关逻辑
    在实际开发中，我们经常需要对用户输入的数据进行 验证，在验证成功后再将数据存入数据库。
    在 Laravel 开发中，提供了多种数据验证方式，在本教程中，我们使用其中一种对新手较为友
    好的验证方式 - validator 来进行讲解。validator 由 App\Http\Controllers\Controller
    类中的 ValidatesRequests 进行定义，因此我们可以在所有的控制器中使用 validate 方法来
    进行数据验证。validate 方法接收两个参数，第一个参数为用户的输入数据，第二个参数为该输
    入数据的验证规则。
    */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);

        $this->sendEmailConfirmationTo($user);

        session()->flash('success','验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }

    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name'=> 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update',$user);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password']=bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资更新成功！');

        return redirect()->route('users.show',$user->id);
    }

    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show', 'create', 'store','index','confirmEmail']
        ]);

        $this->middleware('guest',[
            'only'=> ['create']
        ]);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();
    }

    public function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@yousails.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册Sample应用！请确认你的邮箱。";

        Mail::send($view,$data, function($message) use ($from, $name, $to, $subject){
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功！');
        return redirect()->route('users.show',[$user]);
    }
}
