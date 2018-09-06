<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Gallerie;
use App\Voiture;
use App\Agence;
use App\User;
use Auth;

class VoitureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $voitures = DB::table('voitures')->paginate(4);

        
        return view('voitures',compact('voitures'));
    }
    public function indexUser()
    {
        $agence= DB::table('agences')->where('id_user',Auth::user()->id)->first();
        /*echo($agence->id);*/
        $voitures = DB::table('voitures')->where('id_agence',$agence->id)->get();

        
        return view('user.Cars',compact('voitures'));
    }

    public function filter($voitures)
    {
                
        return view('voitures',compact('voitures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.addCar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chemin = config('images.path');

        $imageG=$request['imageG'];
        $nom = $imageG ->getClientOriginalName();
        $imageG->move($chemin,$nom);

        

        $id_agence=Agence::where('id_user',Auth::user()->id)->first()->id;
        
        $id=Voiture::create([
            'nom' =>$request['nom'],
            'description'=>$request['description'],
            'id_agence'=>$id_agence, 
            'imageG'=>'uploads/'.$nom,
            ])->id;
        for ($i=0; $i <sizeof($request['image']) ; $i++) { 
          
           $imageG=$request['image'][$i];
           $nomG = $imageG ->getClientOriginalName();
            $imageG->move($chemin,$nomG);

            

            Gallerie::create(['id_voiture'=>$id,
                            'imageG'=>'uploads/'.$nomG,
                            ]);
         }
         $voitures = DB::table('voitures')->get();

        return redirect()->action('VoitureController@indexUser');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $voiture = DB::table('voitures')->where('id',$id)->get();
        foreach ($voiture as $v) {
             $agence=DB::table('agences')->where('id',$v->id)->get();
             $galleries=DB::table('galleries')->where('id_voiture',$v->id)->get();
        }

       
        return view('voiture',compact('voiture','agence','galleries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $voiture=Voiture::where('id',$id)->get();
        foreach ($voiture as $v) {
             
             $galleries=DB::table('galleries')->where('id_voiture',$v->id)->get();
        }


        return view('user.editCar',compact('voiture','galleries'));
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
        $voiture=Voiture::where('id',$id)->first();

       $chemin = config('images.path');
       if ($request['imageG']) {
            $imageG=$request['imageG'];
            $nom = $imageG ->getClientOriginalName();
            $imageG->move($chemin,$nom);
            $voiture->imageG='uploads/'.$nom;
       }
        $voiture->nom=$request->nom;
       $voiture->description=$request->description;
       
       $voiture->save();

       for ($i=0; $i <sizeof($request['image']) ; $i++) { 
          
           $imageG=$request['image'][$i];
           $nomG = $imageG ->getClientOriginalName();
            $imageG->move($chemin,$nomG);

           

            Gallerie::create(['id_voiture'=>$id,
                            'imageG'=>'uploads/'.$nomG,
                            ]);
         }

       return redirect()->action('VoitureController@edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Voiture::find($id)->delete();
        
       
        return redirect()->action('VoitureController@indexUser');
    }
}
