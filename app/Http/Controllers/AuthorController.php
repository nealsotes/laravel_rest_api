<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorResource;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(AuthorResource::collection(Author::all()),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $validate= Validator::make($request->toArray(),[
            'name' => 'required',
            'title' => 'required|unique:books|max:255',
            'company' => 'required',
            'email' => 'required'
        ]);
        
        if($validate->fails()){
            return response($validate->errors(),400);
        }
        return response(new AuthorResource(Author::create($validate)),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return response(new AuthorResource($author), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $validate= Validator::make($request->toArray(),[
            'name' => 'required',
            'title' => 'required|unique:books|max:255',
            'company' => 'required',
            'email' => 'required'
        ]);
        if($validate->fails()){
            return response($validate->errors(),400);
        }
        $author->update($validate->validate());
        return response(new AuthorResource($author),201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        foreach($author->books as $book){
            $book->delete();
        }
        $author->delete();
        return response(null, 204);
    }
}
