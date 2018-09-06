<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Agence;
use App\user;
use Auth;
class AgenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $agences = DB::table('agences')->where('nom','!=','vide')->paginate(4);

        
        return view('agences',compact('agences'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agence = DB::table('agences')->where('id',$id)->get();
        $voitures=DB::table('voitures')->where('id_agence',$agence['0']->id)->get();
        
        return view('page3',compact('agence','voitures'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $agence=Agence::where('id_user',$id)->get();
        return view('user.profileAgence',compact('agence'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $agence=Agence::where('id_user',$id)->first();

       $chemin = config('images.path');

       if ($request['logo']) {
          $logo=$request['logo'];
          $nom = $logo ->getClientOriginalName();
          $logo->move($chemin,$nom);
         $agence->logo='uploads/'.$nom;
       }
        
       if ($request['image']) {
            $image=$request['image'];
            $nomIm = $image ->getClientOriginalName();
            $image->move($chemin,$nomIm);
            $agence->image='uploads/'.$nomIm;
       }
       

    
       $agence->nom=$request->nom;
       $agence->adresse=$request->adresse;
       $agence->tel=$request->tel;
       $agence->email=$request->email;
       $agence->lat=$request->lat;
       $agence->lng=$request->lng;
       $agence->description=$request->description;
      
       
       $agence->save();

       return redirect()->action('AgenceController@edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
