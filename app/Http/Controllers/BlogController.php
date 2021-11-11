<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function store(Request $request) {
        $data = new Blog();
        $data->title = $request->title;
        $data->user_id = $request->user_id;
        $data->subject = $request->subject;
        $data->body = $request->body;
        if($data->save()) {
            return $data;
        }
    }

    public function getBlogs($id, Request $request)
    {
        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $params = json_decode(json_encode($params));

        if($params->search) {

            $data = Blog::where(function ($query) use ($params) {
                $query->orWhere('title', 'LIKE', "%$params->search%");
            })->where('user_id', $id)->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return response()->json($data, 200);
        }

        return response()->json(Blog::where('user_id', $id)->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page), 200);
    }

    public function getWelcomeBlogs(Request $request)
    {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search
        ];

        $params = json_decode(json_encode($params));

        if($params->search) {

            $data = Blog::where(function ($query) use ($params) {
                $query->orWhere('title', 'LIKE', "%$params->search%");
            })->with('user')->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page);

            return response()->json($data, 200);
        }

        return response()->json(Blog::with('user')->orderBy('id', 'desc')->paginate($params->count, ['*'], 'page', $params->page), 200);

    }
}
