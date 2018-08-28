<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
//use Carbon\Carbon;
use DateTime;
use Session;
use Excel;
use File;
 
class DailyReportController extends Controller
{
    public function index()
    {
        return view('addfile');
    }
 
    public function import(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                //dd($data);
                if(!empty($data) && $data->count()){
                    foreach ($data as $key => $value) {
                        $ymd= DateTime::createFromFormat('d/m/Y', $value->fecha)->format('Y-m-d');
                        //dd($ymd);
                        // $time = strtotime($value->fecha);
                        // $newformat = date('Y-m-d', $time);
                        //Funcionan pero no colocan fecha luego del día 12
                        //----------------||----------------------
                        //dd(gettype($newformat), gettype($time));
                        // $date=date_create($value->fecha);
                        // $format=date_format($date, "Y-m-d");
                        //dd(gettype($format));
                        //dd($format, $date, $value->fecha);
                        $insert[] = [
                        'ci' => $value->{'ac_no.'},
                        'nombre' => $value->nombre,
                        'fecha' => $ymd,
                        'entrada' => $value->marc_ent,
                        'salida' => $value->marc_sal,
                        'tardanza' => $value->tardanza,
                        'saliotempr' => $value->salioTempr,
                        'horaextra' => $value->horaextra,
                        ];
                    }
                    if(!empty($insert)){
 
                        $insertData = DB::table('daily_reports')->insert($insert);
                        if ($insertData) {
                            Session::flash('success', 'Your Data has successfully imported');
                        }else {                        
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
                    }
                }
                 return back();
 
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }     
}