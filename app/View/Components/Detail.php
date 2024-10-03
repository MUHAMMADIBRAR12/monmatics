<?php

namespace App\View\Components;

use Illuminate\View\Component;
use DB;
class Detail extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    
    public $id;
    public $tasks;
    public $calls;
    public $opportunities;
    
    public function __construct($id)
    {
       $this->tasks=DB::table('crm_tasks')
                        ->join('users','users.id','=','crm_tasks.assigned_to')
                        ->select('crm_tasks.*',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
                        ->where('crm_tasks.related_id','=',$id)
                        ->get();
       $this->calls=DB::table('crm_calls')->where('related_id','=',$id)->get();
       $this->opportunities=DB::table('crm_opportunities')
                                ->join('users','users.id','=','crm_opportunities.assigned_to')
                                ->select('crm_opportunities.*',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
                                ->where('cust_id','=',$id)
                                ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.detail');
    }
}
