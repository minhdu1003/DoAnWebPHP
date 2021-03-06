<?php

namespace App\Http\Controllers\Api\Home;
use Session;
use App\Models\Customer;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product_type;
use App\Models\details_discount;
use App\Models\discount;
use App\Models\product_rating;
use App\Models\details_bill;
use App\Models\Slide;
use Illuminate\Support\Facades\Redirect;
session_start();
use Socialite;

class UserController extends Controller
{
    public function login()
    {     
        $types = product_type::where('Status',1)->get();
        return view('home.user.login_User')->with(compact('types'));
    }
    public function regis()
    {    
        $types = product_type::where('Status',1)->get();
        return view('home.user.regis_User')->with(compact('types'));
    }
    public function check(Request $request)
    {     
        $types = product_type::where('Status',1)->get();
        $user = $request->user;
        $pass = $request->pass;
        $slides = Slide::all();
        $result = Customer::where('UserName',$user)->where('PassWord',$pass)->first();

        if($result){
                          
                Session::put('username',$result->CustomerName);
                Session::put('idcustomer',$result->CustomerID);

            return view('home.home.pages')->with(compact('types','slides'));
        }else{
                Session::put('message','Login name or password is incorrect. Please enter again !');
            return view('home.user.login_User')->with(compact('types'));
        }
        return view('home.user.login_User');
    }
   
    public function  logoutUser()
    {    
        $types = product_type::where('Status',1)->get();
        
        Session::forget('username');
        Session::forget('idcustomer');
        $slides = Slide::all();
        return view('home.home.pages')->with(compact('types','slides'));
    }

    public function registerUser(Request $request)
    {
        Session::forget('err0');
        Session::forget('err1');
        $types = product_type::where('Status',1)->get();

        $surname = $request->surname;
        $name = $request->name;
        $a = ' ';
        $b = ', ';
        $customername = '';
        $customername .= $surname .= $a .=  $name;


        $pro_dis = ["","An Giang","B?? R???a - V??ng T??u","B???c Li??u","B???c K???n","B???c Giang","B???c Ninh","B???n Tre","B??nh D????ng","B??nh ?????nh","B??nh Ph?????c","B??nh Thu???n","C?? Mau","Cao B???ng","C???n Th??","???? N???ng","?????k L???k","?????k N??ng","?????ng Nai","?????ng Th??p","??i???n Bi??n","Gia Lai","H?? Giang","H?? Nam","H?? N???i","H?? T??nh","H???i D????ng","H???i Ph??ng","H??a B??nh","H???u Giang","H??ng Y??n","Th??nh ph??? H??? Ch?? Minh","Kh??nh H??a","Ki??n Giang","Kon Tum","Lai Ch??u","L??o Cai","L???ng S??n","L??m ?????ng","Long An","Nam ?????nh","Ngh??? An","Ninh B??nh","Ninh Thu???n","Ph?? Th???","Ph?? Y??n","Qu???ng B??nh","Qu???ng Nam","Qu???ng Ng??i","Qu???ng Ninh","Qu???ng Tr???","S??c Tr??ng","S??n La","T??y Ninh","Th??i B??nh","Th??i Nguy??n","Thanh H??a","Th???a Thi??n - Hu???","Ti???n Giang","Tr?? Vinh","Tuy??n Quang","V??nh Long","V??nh Ph??c","Y??n B??i"] ;

        $province = $pro_dis[$request -> calc_shipping_provinces];
        $district =$request->calc_shipping_district;
        $ward = $request->ward;
        $address=$request ->address;

        $customeraddress = $address. ', '.$ward. ', ' .$district. ', ' .$province;

        $customer = new Customer();

        $userdata = Customer::where('UserName',$request -> user)->first();
        if( $request -> pass != $request->password_re )
        {
            Session::put('err0',"M???t kh???u kh??ng tr??ng kh???p !");
            return redirect()->back();

        }else if($userdata){
            Session::put('err1',"T??n t??i kho???n ???? t???n t???i !");
            return redirect()->back();
        } else{
            $customer -> CustomerName = $customername;
            $customer -> CustomerSex = $request -> sex;
            $customer -> CustomerAdress = $customeraddress;
            $customer -> CustomerEmail = $request-> email;
            $customer -> CustomerPhone =$request -> phone;
            $customer -> UserName =$request -> user;
            $customer -> PassWord =$request -> pass;
    
            $customer->save();
           
            return view('home.user.login_User')->with(compact('types'));
        }  
    }

    /// D??? code nh?? b??i

    public function infoUser ($id){
        
        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);

        return view('home.user.info_User')->with(compact('result','types'));
    }

    public function changepassUser ($id)
    {
        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);
        return view('home.user.changepass_User')->with(compact('result','types'));
    }

    public function updatepass(Request $request , $id){

        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);

        if($request->pass != $request->oldpass){

            Session::put('notifi','Current password does not match, please check again !');    
        }
        else if($request->newpass != $request->newpass1){
            Session::put('notification','New password does not match, please check again !');
        }
        else{

            $result -> PassWord = $request ->newpass;
        
            $result->save();
            return view('home.user.info_User')->with(compact('result','types'));
        }
        return view('home.user.changepass_User')->with(compact('result','types'));
    }

    public function addrUser ($id){

        $types = product_type::where('Status',1)->get();
        $result =Customer::find($id);

        return view('home.user.address_User')->with(compact('result','types'));   
     }

     public function successbillUser($id){

        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);
        $bill = Bill::where('CustomerID',$id)->where('Status',2)->get();
        return view('home.user.successbill_User')->with(compact('bill','result','types'));
    }

    public function waitingbillUser($id){
        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);
        $bill = Bill::where('CustomerID',$id)->where('Status',0)->get();
        return view('home.user.waitingbill_User')->with(compact('bill','result','types'));
     }

     public function transportbillUser($id){
        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);
        $bill = Bill::where('CustomerID',$id)->where('Status',1)->get();
        return view('home.user.transportbill_User')->with(compact('bill','result','types'));
     }

     public function cancelbillUser($id){
        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);
        $bill = Bill::where('CustomerID',$id)->where('Status',3)->get();
        return view('home.user.cancelbill_User')->with(compact('bill','result','types'));
    }

    public function billdetailUser($id){

        $types = product_type::where('Status',1)->get();
        $idcus = Session::get('idcustomer');
        $result = Session::get('idcustomer');
        $dataUsers = Bill::with('customer_bill','discount_bill','pay_bill')->where('BillID',$id)->get();
        
        $detailbills = details_bill::with('product_bill')->where('BillID',$id)->get();

        return view('home.user.details_Bill')->with(compact('detailbills','dataUsers','types','result'));
    }

     public function statusBillscan ($id){
        
        $bill = Bill::find($id);
        $bill-> Status = 3;
        $bill->save();
        return response()->json(['data'=>'bill'],200);
        
    }

    public function loginGoogle(){
        return Socialite::driver('google')->redirect();
    }
    public function callback_GG(){
        $types = product_type::where('Status',1)->get();
       $slides = Slide::all();
       $users = Socialite::driver('google')->stateless()->user();

       $authUser = $this -> findOrCreateUser($users,'google');
       if($authUser){
           return view('home.home.pages')->with(compact('types','slides'));
       }else{
           return view('home.home.pages')->with(compact('types','slides'));
       }

    }
    public function findOrCreateUser($users){

       $authUser = Customer::where('UserName', $users->id)->first();
       if($authUser)
       {
            Session::put('username',$authUser->CustomerName);
            Session::put('idcustomer',$authUser->CustomerID);
           return $authUser;
       }else{
           $customers = new Customer();
           $customers -> UserName = $users->id;
           $customers -> CustomerName = $users->name;
           $customers -> CustomerEmail = $users->email;
           $customers -> CustomerAdress = '';
           $customers -> CustomerSex = '';
           $customers -> CustomerPhone = '';
           $customers -> PassWord = '';
           $customers ->save();
            Session::put('username',$authUser->CustomerName);
            Session::put('idcustomer',$authUser->CustomerID);
       }
    }
    public function updateAddress(Request $request){

        $types = product_type::where('Status',1)->get();
        $data = Customer::find($request->id);
        
        $data -> CustomerPhone = $request->phone;

        $pro_dis = ["","An Giang","B?? R???a - V??ng T??u","B???c Li??u","B???c K???n","B???c Giang","B???c Ninh","B???n Tre","B??nh D????ng","B??nh ?????nh","B??nh Ph?????c","B??nh Thu???n","C?? Mau","Cao B???ng","C???n Th??","???? N???ng","?????k L???k","?????k N??ng","?????ng Nai","?????ng Th??p","??i???n Bi??n","Gia Lai","H?? Giang","H?? Nam","H?? N???i","H?? T??nh","H???i D????ng","H???i Ph??ng","H??a B??nh","H???u Giang","H??ng Y??n","Th??nh ph??? H??? Ch?? Minh","Kh??nh H??a","Ki??n Giang","Kon Tum","Lai Ch??u","L??o Cai","L???ng S??n","L??m ?????ng","Long An","Nam ?????nh","Ngh??? An","Ninh B??nh","Ninh Thu???n","Ph?? Th???","Ph?? Y??n","Qu???ng B??nh","Qu???ng Nam","Qu???ng Ng??i","Qu???ng Ninh","Qu???ng Tr???","S??c Tr??ng","S??n La","T??y Ninh","Th??i B??nh","Th??i Nguy??n","Thanh H??a","Th???a Thi??n - Hu???","Ti???n Giang","Tr?? Vinh","Tuy??n Quang","V??nh Long","V??nh Ph??c","Y??n B??i"] ;

        $province = $pro_dis[$request -> calc_shipping_provinces];
        $district =$request->calc_shipping_district;
        $ward = $request->ward;
        $address=$request ->address;

        $customeraddress = $address. ', '.$ward. ', ' .$district. ', ' .$province;
        
        $data -> CustomerAdress = $customeraddress;
        $data -> save();

        return view('home.cart.cart_delivery')->with(compact('types','data'));

     }

     public function discountUsser($id){
        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);

        $discountnew = details_discount::with('discount_code')->where('CustomerID',$id)->where('Status',1)->orderBy('Status','DESC')->get();
        $discountold = details_discount::with('discount_code')->where('CustomerID',$id)->where('Status',0)->orderBy('Status','DESC')->get();

        return view('home.user.discount_User')->with(compact('types','discountnew','discountold','result'));
     }

     public function judgeProduct($id){

        $types = product_type::where('Status',1)->get();
        $result = Customer::find($id);
        $idcus =   Session::get('idcustomer');
        $product_rating = product_rating::with('rating_product')->where('CustomerID',$idcus)->where('Status',0)->get();
        
        return view('home.user.judge_Product')->with(compact('types','result','product_rating'));
     }

     public function updateComment($id, Request $request){


        $rating_old = product_rating::find($id);

        if ($rating_old->Rate == 0){
            $rating_old->Comment = $request->comment;
            $rating_old-> Rate = 5;
            $rating_old -> Status = 1;
            $rating_old ->save();
            return redirect()->back();

        }else{
            $rating_old->Comment = $request->comment;
            $rating_old -> Status = 1;
            $rating_old ->save();
            return redirect()->back();
        }
        
     }
     
}