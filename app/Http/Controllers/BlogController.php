<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function index()
    {
        $blogs = Blog::all();
        return response()->json($blogs);
    }

    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        return response()->json($blog);
    }

    public function store(Request $request)
    {
        $request->validate([
            'author' => 'required|string|max:20',
            'publish_date' => 'required|date',
            'description' => 'required|array',
            'picture' => 'required|array',
        ]);

        $blog = Blog::create([
            'author' => $request->author,
            'publish_date' => $request->publish_date,
            'description' => $request->description,
            'picture' => $request->picture,
        ]);

        return response()->json([
            'message' => 'Blog created successfully',
            'data' => $blog,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $request->validate([
            'author' => 'sometimes|string|max:20',
            'publish_date' => 'sometimes|date',
            'description' => 'sometimes|array',
            'picture' => 'sometimes|array',
        ]);

        $blog->update($request->only(['author', 'publish_date', 'description', 'picture']));

        return response()->json([
            'message' => 'Blog updated successfully',
            'data' => $blog,
        ]);
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully']);
    }
}
