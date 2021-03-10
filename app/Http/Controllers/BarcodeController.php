<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use App\Models\Barcode;

class BarcodeController extends Controller
{
    //
    public function index(){
        return view('barcode/index');
    }

    public function generate(Request $request){
        $profile = $request->file('profile') ? $request->file('profile')->getClientOriginalName() :null;

        if ($request->hasFile('profile')) {
            $request->file('profile')->storeAs('/public/profile/', $profile);
        }

        $data = Barcode::create([
            'full_name'=>$request->full_name,
            'email'=>$request->email,
            'phone'=> $request->phone,
            'street'=>$request->street,
            'city'=>$request->city,
            'zip_code'=>$request->zip_code,
            'state'=>$request->state,
            'website'=>$request->website,
            'profile'=>$profile,
        ]);

        $data->save();
        
            $qrCode = new QrCode();
            $qrCode
                ->setText(env('APP_URL').'/get_barcode/'.$data->id)
                ->setSize(300)
                ->setPadding(10)
                ->setErrorCorrection('high')
                ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                ->setLabel('Scan Qr Code')
                ->setLabelFontSize(16)
                ->setImageType(QrCode::IMAGE_TYPE_PNG)
            ;

            $user = Barcode::find($data->id);
            $user->update([
                'barcode'=>'data:'.$qrCode->getContentType().';base64,'.$qrCode->generate()
            ]);
             return view('barcode.view_code',['data'=>$user]);


            // echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
    }

    public function get(Request $request,$id){
        $data = Barcode::find($id);
        return view('barcode.view_code',['data'=>$data]);
    }
}
