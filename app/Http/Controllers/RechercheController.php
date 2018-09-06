<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Agence;
use App\Voiture;
use App\Client;
use App\Reservation;
use Auth;
class RechercheController extends Controller
{
    
    
    public function prepare(Request $request)
    {
       /*print_r($request['modele']);
       print_r($request['region']);
       print_r($request['dateD']);
       print_r($request['dateF']);

		
       */

       

       if ($request['region'] and !$request['modele'] and !$request['dateD'] and !$request['dateF']) {
       	
       	$agences = DB::table('agences')->where('adresse', 'like', '%' . $request['region'] . '%')
       								   ->where('nom','!=','vide')->get();
       	
       	return view('recherche1',compact('agences'));
       }

       if ($request['modele'] and !$request['region'] and !$request['dateD'] and !$request['dateF']) {

       	$voitures= DB::table('voitures')->where('description', 'like', '%' . $request['modele'] . '%')->paginate(4);
       	 return view('recherche',compact('voitures'));
       }

        if ($request['region'] and $request['modele'] and !$request['dateD'] and !$request['dateF']) {
       			$reg=$request['region'];$reg1=$request['modele'];


		       

		        $voitures=DB::table('agences')->where('agences.adresse', 'like', '%' . $reg . '%')
		        						->join('voitures', function ($join) use($reg1){
								            $join->on('agences.id', '=','voitures.id_agence' )
								                 ->where('voitures.description', 'like', '%' . $reg1 . '%');
								             })
		        						->get();						
		       	
		       	return view('recherche',compact('voitures'));
       }

       if ($request['region'] and $request['modele'] and $request['dateD'] and $request['dateF']) {
       			$reg=$request['region'];$reg1=$request['modele'];
       			$dateD=date("Y-m-d", strtotime($request['dateD']));
		        $dateF=date("Y-m-d", strtotime($request['dateF']));

		       $agences=DB::table('voitures')->where('voitures.description', 'like', '%' . $request['modele'] . '%')
		        						->join('agences', function ($join) use($reg){
								            $join->on('voitures.id_agence', '=', 'agences.id')
								                 ->where('agences.adresse', 'like', '%' . $reg . '%');
								             })
		        						->get();

		        $voitures=DB::table('agences')->where('agences.adresse', 'like', '%' . $reg . '%')
		        						->join('voitures', function ($join) use($reg1,$dateD,$dateF){
								            $join->on('agences.id', '=','voitures.id_agence' )
								                 ->where('voitures.description', 'like', '%' . $reg1 . '%')
								                 ->leftJoin('reservations',function($leftJoin) use($dateD,$dateF){
				        							$leftJoin->on('voitures.id', '=', 'reservations.id_voiture')
				        									 ->where('dateD',$dateD)
				        									 ->where('dateF',$dateF);
				        									});
								             })
		        						->get();						
		       	
		       	return view('recherche',compact('voitures'));
       }
       
       

       if ($request['dateD'] and $request['dateF'] and !$request['region'] and !$request['modele']) {

		       	$dateD=date("Y-m-d", strtotime($request['dateD']));
		        $dateF=date("Y-m-d", strtotime($request['dateF']));

		        $reservations1=DB::table('reservations')->where('dateD',$dateD)->where('dateF',$dateF)->select('id_voiture')->get();

		        $voitures_reserved=array();$i=0;
		       	foreach ($reservations1 as $reservation) {
		       		$voitures_reserved[$i]=$reservation->id_voiture;
		       		$i++;
		       	}

		       	$voitures=DB::table('voitures')->whereNotIn('id', $voitures_reserved)->get();

		       	

		       	
		       	return view('recherche',compact('voitures'));
		    }

      
    }

   
    public function show($id)
    {
       
    }

    
}
