<?php

namespace App\Http\Controllers;

use App\BlockGroup;
use Illuminate\Http\Request;

class BlockGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlockGroup  $blockGroup
     * @return \Illuminate\Http\Response
     */
    public function show(BlockGroup $blockGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BlockGroup  $blockGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(BlockGroup $blockGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlockGroup  $blockGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlockGroup $blockGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlockGroup  $blockGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlockGroup $blockGroup)
    {
        //
    }

    public function getAllBlockGroups(){
        return BlockGroup::getAllBlockGroupsId();
    }
}
