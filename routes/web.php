<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use GuzzleHttp\Client;
Route::get('/', function () {
    $client = new Client();

    //$response = $client->request('POST','http://api.hakkisabah.com/test/testdata',['json' => ['finddata' => '']])->getBody();

    return view('welcome');
});
Route::post('/finddata', function (\Illuminate\Http\Request $request) {

   $received = $request->json()->all();

 if (!$received['finddata']) return response()->json("Error", 200);

    if ($received['finddata']){
        $client = new Client();
        $response = $client->request('POST','http://api.hakkisabah.com/test/testdata',['json' => ['finddata' => json_decode($request->getContent(),true)['finddata']]])->getBody();

        $getoneforrequest = DB::table('testdata')->where(
            'data',
            json_decode($request->getContent(),true)['finddata'])
            ->first();
        $localdb = $getoneforrequest;
        $remotedb = json_decode($response->getContents(),true);
        $allresults = array('Local' =>$localdb,'Remote'=> $remotedb);
        $jsonresult = json_encode($allresults);
        return response()->json(json_decode($jsonresult), 200);
    }


});

Route::post('/findalldata', function (\Illuminate\Http\Request $request) {
    $received = $request->json()->all();

    $client = new Client();
    $response = $client->request('POST','http://api.hakkisabah.com/test/testdata',['json' => ['finddata' => '']])->getBody();

    if ($received['findalldata'] == "") {
        $getallforrequest = DB::table('testdata')->get();
        $localdb = $getallforrequest;
        $remotedb = json_decode($response->getContents(),true);
        $allresults = array('Local' =>$localdb,'Remote'=> $remotedb);
        $jsonresult = json_encode($allresults);
        return response()->json(json_decode($jsonresult,true), 200);
    }
    return response()->json("Error", 200);
});

Route::post('/adddata', function (\Illuminate\Http\Request $request) {
    $received = $request->json()->all();

    if (!$received['data'])  return response()->json("Request Error", 200);

    if ($received['data']){
        $getoneforrequest = DB::table('testdata')->where(
            'data',
            json_decode($request->getContent(),true)['data'])
            ->first();
        if ($getoneforrequest !=null) return response()->json(json_encode(array("Result"=>"Data already here")), 200);
        if ($getoneforrequest == null) {
            $client = new Client();
            $response = $client->request('POST','http://api.hakkisabah.com/test/testdata',['json' => ['data' => json_decode($request->getContent(), true)['data']]])->getBody();

            $inserteddata = DB::table('testdata')->insert(
                ['data' => json_decode($request->getContent(), true)['data']]
            );
            $localdb = $inserteddata;
            $remotedb = json_decode($response->getContents(),true);
            $allresults = array('Local' =>$localdb,'Remote'=> $remotedb);
            $jsonresult = json_encode($allresults);
            if ($inserteddata ==true) return response()->json($jsonresult, 200);
            return response()->json("Insert Error", 200);
        }

    }
});