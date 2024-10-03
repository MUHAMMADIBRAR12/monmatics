<?php

namespace App\Http\Controllers;

use App\Libraries\appLib;
use App\Libraries\dbLib;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;


class BlogController extends BaseController
{

        protected function generateUniqueId()
    {
        $id = mt_rand(100000, 999999); // Generate a random ID between 100000 and 999999
        // Check if the ID already exists in the database
        while (DB::table('blog_categories')->where('id', $id)->exists()) {
            $id = mt_rand(100000, 999999); // Generate a new ID
        }
        return $id;
    }
    function dashboard(){
        $DeactivePosts = DB::table('blog_posts')->where('status', 'Draft')->get();

        $activePosts = DB::table('blog_posts')->where('status', 'Publish')->get();
        $posts = DB::table('blog_posts')->get();
        $categories = DB::table('blog_categories')->get();
    	return view('blog.dashboard',compact('categories','posts','activePosts','DeactivePosts'));
    }

   

    function list(){
        return view('blog.list');
    }

    function grid(){
        return view('blog.grid');
    }

    function detail(){
    	return view('blog.detail');
    }



    // section for category crud

    public function categoryList()
    {
                  
        $categories=DB::table('blog_categories')->get();
        return view('blog.category_list',compact('categories'));
    }

    public function createCategory($id=null)
    {
        if($id)
        {  
            $category=DB::table('blog_categories')->select()->where('id','=',$id)->get()->first();
            return view('blog.blog_category',compact('category'));
        }
        else
        {
          return view('blog.blog_category');
        }
    }


    public function save(Request $request)
    {
        $id = $request->input('id');
        $category = $request->input('category');
    
        $categoryData = [
            "id" => $this->generateUniqueId(), // Manually generate a unique ID
            "category" => $category,
            "created_at" => now(),
        ];
    
        if ($id) {
            // Update an existing category
            DB::table('blog_categories')->where('id', $id)->update($categoryData);
        } else {
            // Insert a new category
            DB::table('blog_categories')->insert($categoryData);
        }
    
        return redirect('blog/category');
    }

    public function delete($id)
    {
        DB::table('blog_categories')->where('id',$id)->delete();
        return back()->with('msg','Record Deleted successfully');
    }




    // section blog post crud




    public function posts(){
        
        $table = appLib::$related_table;

        $posts = DB::table('blog_posts')->paginate(10); 
        return view('blog.posts', compact('posts'));
    }
    

    public function postForm($id = null)
    {
        $categories = DB::table('blog_categories')->get();
        $posts = null; // Initialize $posts variable
        
    
        if ($id) {
            $posts = DB::table('blog_posts')->select()->where('id', '=', $id)->get()->first();
        }
        $attachmentRecord = dbLib::getAttachment($id);  
        return view('blog.post_form', compact('categories', 'posts','attachmentRecord'));
    }


    


            public function postSave(Request $request)
        {
            $title = $request->input('title');
            $category = $request->input('category');
            $status = $request->input('status');
            $description = $request->input('bodytext');
            
            // Save the data using query builder
            $data = DB::table('blog_posts')->insertGetId([
                'title' => $title,
                'category' => $category,
                'status' => $status,
                'description' => $description,
                'created_at' => Carbon::now(),
            ]); 

            
 
            if ($request->file('file')) {
                dbLib::uploadDocument($data, $request->file('file')); // Pass the newly inserted post ID
            }
            
            // Redirect to a suitable route after saving
            return redirect('blog/post');
        }

    
            public function postUpdate(Request $request, $id)
            {
                // Retrieve the post based on the provided ID
                $post = DB::table('blog_posts')->where('id', $id)->first();
                
                // Proceed with updating only if the post exists
                if ($post) {
                    // Get the input values
                    $title = $request->input('title');
                    $category = $request->input('category');
                    $status = $request->input('status');
                    $description = $request->input('bodytext');

                    // Handling image upload
                 

                    // Update the data using query builder
                  $data =  DB::table('blog_posts')
                        ->where('id', $id)
                        ->update([
                            'title' => $title,
                            'category' => $category,
                            'status' => $status,
                            'description' => $description,
                            // 'image' => $imageName,
                            'created_at' => Carbon::now(),
                        ]);

                    // Redirect to a suitable route after updating
                    if ($request->file('file')) {
                        dbLib::uploadDocument($id, $request->file('file')); // Pass the newly inserted post ID
                    }
                    return redirect('blog/post');
                }


                // Redirect to a suitable route if post doesn't exist
                return redirect()->route('blog.postForm');
            }


            public function deletePost($id)
            {
                DB::table('blog_posts')->where('id',$id)->delete();
                return back()->with('msg','Your post is successfully deleted');
            }


           
            public function viewPost($id)
            {
                $post = DB::table('blog_posts')
                            ->where('id', $id)
                            ->first();
                
                $user = auth()->user(); // Get the authenticated user
                
                return view('blog.post_view', compact('post', 'user'));
            }
            
            

  


}