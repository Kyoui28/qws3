<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\TaskAdded;

class PersonController extends Controller
{
    public function index(Request $request){
        $hasItems = Person::has('boards')->get();
        $noItems = Person::doesntHave('boards')->get();
        $param=['hasItems'=>$hasItems,'noItem'=>$noItems];
        return view('person.index',$param);
    }

    public function job(Request $request){
        $id="1";
        // $id = $request->input('id');
        $person = Person::find($id);
        var_dump($person->getData());
        dispatch(
            function() use ($person){
                //sleep(10);
//                Storage::append('person_access_log.txt',$person->getData());
//                $html=file_get_contents('https://user-analytics.net/ga_api/ga_search.php?rid=3hCUm45LUYdZ&wpid=A5%2FDeq5aU1k2AzTdjoQUMg%3D%3D&vid=30b2804af56a6ed05ed0a4432e65271c&goal_type=goal&goal_value=2&goal_name=%E5%95%8F%E3%81%84%E5%90%88%E3%82%8F%E3%81%9B%E5%AE%8C%E4%BA%86/Thanks%E3%83%9A%E3%83%BC%E3%82%B8%E5%88%B0%E9%81%94&date_from=2020-11-04&date_to=2021-01-08');
                $task = ['id' => 1, 'name' => 'メールの確認']; 
                event(new TaskAdded($task));
            }
        )->onConnection('database');
        // $hasItems = Person::has('boards')->get();
        // $noItems = Person::doesntHave('boards')->get();
        // $param=['hasItems'=>$hasItems,'noItem'=>$noItems];
        //return redirect()->route('person');    
    }


    public function find(Request $request){
        return view('person.find',['input'=>'']);
    }
    public function search(Request $request){
        $min=$request->input*1;
        $max=$min+10;
        $item = Person::ageGreaterThan($min)->ageLessThan($max)->first();
        $param = ['input' => $request->input, 'item' => $item];
        return view('person.find',$param);
    }
    public function add(Request $request){
        return view('person.add');
    }
    public function create(Request $request){
        $this->validate($request, Person::$rules);
        $person = new Person;
        $form =$request->all();
        unset($form['?token']);
        $person->fill($form)->save();
        return redirect('/person');
    }
    public function edit(Request $request){
        $person = Person::find($request->id);
        return view('person.edit',['form'=>$person]);
    }
    public function update(Request $request){
        $this->validate($request, Person::$rules);
        $person = Person::find($request->id);
        $form =$request->all();
        unset($form['?token']);
        $person->fill($form)->save();
        return redirect('/person');
    }
    public function delete(Request $request){
        $person = Person::find($request->id);
        return view('person.del',['form'=>$person]);
    }
    public function remove(Request $request){
        Person::find($request->id)->delete();
        return redirect('/person');
    }


}
