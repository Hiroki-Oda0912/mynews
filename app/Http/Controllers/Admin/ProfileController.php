<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\profiles;

class ProfileController extends Controller
{
    //
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
      // Varidationを行う
        $this->validate($request, profiles::$rules);
        $profiles = new profiles;
        $form= $request->all(); 
        
      // データベースに保存する
        $profiles->fill($form);
        $profiles->save();
      
        return redirect('admin/profile/create');
    }
    
    public function index(Request $request)
  {
      $cond_name = $request->cond_name;
      if ($cond_name != '') {
          // 検索されたら検索結果を取得する
          $posts = profiles::where('name', $cond_name)->get();
      } else {
          // それ以外はすべてのニュースを取得する
          $posts = profiles::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_name' => $cond_name]);
  }

    public function edit(Request $request)
  {
      // Profile Modelからデータを取得する
      $profile = profiles::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
      return view('admin.profile.edit', ['profile_form' => $profile]);
  }

    public function update(Request $request)
  {
      // Validationをかける
      $this->validate($request, profiles::$rules);
      // Profile Modelからデータを取得する
      $profile = profiles::find($request->id);
      // 送信されてきたフォームデータを格納する
      $profile_form = $request->all();
      unset($profile_form['_token']);

      // 該当するデータを上書きして保存する
      $profile->fill($profile_form)->save();

      return redirect('admin/profile/');
  }

  public function delete(Request $request)
  {
      // 該当するProfile Modelを取得
      $profile = profiles::find($request->id);
      // 削除する
      $profile->delete();
      return redirect('admin/profile/');
  }  
    
}

