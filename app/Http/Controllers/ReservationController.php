<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Agence;
use App\Voiture;
use App\Client;
use App\Reservation;
use Auth;
class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agence=DB::table('agences')->where('id_user',Auth::user()->id)->get();
        
        $reservations = DB::table('reservations')->where('id_agence',$agence[0]->id)->get();

        
        $events = json_decode($reservations, true);
        $voitures=DB::table('voitures')->where('id',$events[0]['id_voiture'])->get();
        $voiture = json_decode($voitures, true);
        $results= array();

        foreach ($events as $i=>$event) {
            
            $results[$i] = array('title'=>''.$voiture['0']['nom'].'',
                                'url' => asset('/reservations/'.$event['id']),
                                 'start' =>''.$event['dateD'].'',
                                 'end'=>''.$event['dateF'].'');
        }
        //print_r($results);
       
        $result = json_encode($results, true);
       //print_r($result);
       return view('user.calendrier',compact('result'));
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
    
    public function prepare(Request $request)
    {
       $dateD=date("Y-m-d", strtotime($request['dateD']));
        $dateF=date("Y-m-d", strtotime($request['dateF']));
        //echo $request['id_car'];
        $voiture=Voiture::where('id',$request['id_car'])->first();
        //echo $voiture->id;
        $agence=Agence::where('id',$voiture->id_agence)->first();
         
        return view('firstStep',compact('dateD','dateF','voiture','agence'));
    }

    public function store(Request $request)
    {

        $id_client=Client::create([
            'nom'=>$request['nom'],
            'tel'=>$request['tel'],
            'adresse'=>$request['adresse'],
            'email'=>$request['email'],
            ])->id;
        Reservation::create([
            'dateD'=>$request['dateD'],
            'dateF'=>$request['dateF'],
            'id_agence'=>$request['id_agence'],
            'id_voiture'=>$request['id_voiture'],
            'id_client'=>$id_client,
            'statut'=>0,
            ]);

        return view('doneReservation');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation=Reservation::where('id',$id)->get();
        $voiture=Voiture::where('id',$reservation['0']->id_voiture)->get();
        $client=Client::where('id',$reservation['0']->id_client)->get();
        return view('user.reservation',compact('reservation','voiture','client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
       //print_r($request['statut']);
       if($request['statut']==='on'){
        $reservation=Reservation::where('id',$id)->first();
        $reservation->statut=1;
        $reservation->save();
      return redirect()->action('ReservationController@show',$id);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res= Reservation::where('id',$id)->first();
    }
}
