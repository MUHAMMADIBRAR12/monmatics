<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\User;
 
class AutoCompleteController extends Controller
{
 
    public function index()
    {
        return view('search');
    }
    public function indexs()
    {
        return view('search2');
    }
 
    public function search(Request $request)
    {
          $search = $request->get('name');
      
          $results = User::where('name', 'LIKE', '%'. $search. '%')->get();
          
          $response = array();
            foreach($results as $employee){
               $response[] = array("value"=>$employee->id,"label"=>$employee->name);
            }
 
          return response()->json($response);
            
    } 
}