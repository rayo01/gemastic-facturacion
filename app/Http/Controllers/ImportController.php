<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Ubigeo;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;



class ImportController extends Controller
{
  public function import()
  {
    //$data = Excel::load('public/Ubigeos.csv')->get();
    $datos=Excel::load('/public/Ubigeos.csv')->get();
    //$datos=$reader->get();
    set_time_limit(120);
    if(DB::table('ubigeos')->get()->isEmpty())
    {
       foreach ($datos as $key => $ubigeo)
       {
         $arr[] = [
           'CodDepartamento' => $ubigeo->departamento,
           'CodProvincia' =>$ubigeo->provincia,
           'CodDistrito' =>$ubigeo->distrito,
           'Nombre' =>$ubigeo->nombre
         ];

        }
        if(!empty($arr))
         {
             DB::table('ubigeos')->insert($arr);
             return redirect('/home');
         }
    }
    else
     return redirect('/home');
  }

}
