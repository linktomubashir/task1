<?php

namespace App\Http\Controllers;
use App\Models\Slider;
use App\Models\WhatWeDo;
use App\Models\Portfolio;
use App\Models\Category;
use App\Models\Admin\Blog;
use App\Models\Admin\SocialLink;
use App\Models\Admin\FunFact;
use App\Models\Service;
use Illuminate\Http\Request;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sliders = Slider::all();
        $services = Service::all();
        $whatWeDo = WhatWeDo::all();
        $portfolios = Portfolio::all();
        $categories = Category::all();
        $socialLinks = SocialLink::all();
        $blogs = Blog::all();
        $funFacts = FunFact::all();

        $pageData = [
            'sliders' => $sliders,
            'services' => $services,
            'whatWeDo' => $whatWeDo,
            'portfolios' => $portfolios,
            'categories' => $categories,
            'blogs' => $blogs,
            'funFacts' => $funFacts,
            'socialLinks' => $socialLinks
        ];

        return response()->json($pageData);
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
