<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Testable;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use Testable;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Todo make complex query by eloquent  and compare same query with raw db query
//        $items = User::has('blogs', '>=', 2)
//            ->withCount('blogs')
//            ->get();
//        $items = User::has('blogs', '>=', 3)
//            ->withCount(['blogs as comment_count' => function ($query) {
//                $query->withCount('comments');
//            }])
//            ->get();
        return $this->getItems();
    }
    public function getItems()
    {
        return ['ami','tumi'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
