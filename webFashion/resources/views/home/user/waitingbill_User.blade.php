
@extends('home.layoutshome')
@section('login')
<div class="container">
        <div class="info" style="padding: 80px 25px 50px 25px">
          <div class="tt">
            <p style="text-align: center; font-size: 15px; font-weight: bold; margin-bottom:20px">QUẢN LÝ ĐƠN HÀNG</p>
          </div>
          <div class="col-sm-2">
            <div class="panel panel-default">

              <div class="panel-heading">Tài khoản của tôi</div>
              <div class="panel-body">
                <a href="{{route('info-user.infoUser',$result->CustomerID)}}"><p style="font-size: 12px;"><span><i class="fas fa-clipboard-check"></i></span> Thông tin tài khoản</p></a>
                <a href="{{route('addr-user.addrUser', $result->CustomerID)}}"><p style="font-size: 12px;"><span><i class="fas fa-clipboard-check"></i></span> Địa chỉ nhận hàng</p></a>
              </div>
              <div class="panel-heading">Đơn hàng</div>
              <div class="panel-body">
              
                <a href="{{route('success-bill.successbillUser', $result->CustomerID)}}"><p style="font-size: 12px;"><span><i class="fas fa-clipboard-check"></i></span> Giao thành công</p></a>
                <a href="{{route('waiting-bill.waitingbillUser', $result->CustomerID)}}"><p style="font-size: 12px;"><span><i class="fas fa-recycle"></i></span> Đang chờ xử lý</p></a>
                <a href="{{route('transport-bill.transportbillUser', $result->CustomerID)}}"><p style="font-size: 12px;"><span><i class="fas fa-shipping-fast"></i></span> Đang vận chuyển</p></a>
                <a href="{{route('cancel-bill.cancelbillUser', $result->CustomerID)}}"><p style="font-size: 12px;"><span><i class="fas fa-ban"></i></span> Đơn hàng đã hủy </p></a>
                <a href="{{route('judge-product.judgeProduct', $result->CustomerID)}}"><p style="font-size: 12px;"><span><i class="fas fa-gavel"></i></span> Đánh giá sản phẩm </p></a>
              </div>
              <div class="panel-heading">Khuyến mãi</div>
              <div class="panel-body">           
                <a href="{{route('discount-user.discountUsser', $result->CustomerID)}}"><p style="font-size: 12px;"><span><i class="fas fa-tags"></i></span> Mã giảm giá</p></a>
              </div>
              
            </div>
          </div>
          <div class="col-md-10 col-sm-9 col-xs-12">
            <div >
                <p style=" font-size:16px;">Danh sách đơn hàng đang xử lý</p>
            </div>
            <table class="table table-hover">
            <thead>
                <tr>
                <th scope="col" style="text-align:center">Mã đơn hàng</th>
                <th scope="col" style="text-align:center">Ngày mua</th>            
                <th scope="col" style="text-align:center">Tổng tiền</th>
                <th scope="col" style="text-align:center">Trạng thái</th>
                
                </tr>
            </thead>
            <tbody >
            @foreach($bill as $bills)
                <tr style="background: #f5f5fa">
                <td style="text-align:center">{{$bills->BillID}}</td>
                <td style="text-align:center">{{$bills->DateCreated}}</td>
                <td style="text-align:center">{{$bills->TotalMoney}} <span>đ</span></td>
                <td style="text-align:center">Đang chờ xử lý</td>
                <td>           
                    <button name="changestatusbillsca" style="border:none; background: transparent; margin-left:10px;" data-url="{{ route('status-cancer.statusBillscan',$bills->BillID)}}"​ type="button" id="btn-delete" data-target="#delete" data-toggle="modal" ><img style="width:23px" src="{{asset('/icon/cancels.png')}}" alt=""></button>
                </td>
                </tr> 
            @endforeach
            </tbody>
            </table>
          </div>
        </div>
      </div> 
      <script type="text/javascript">
    $(document).on('click', "button[name='changestatusbillsca']", function () {
        var url = $(this).attr('data-url');
        if (confirm('Bạn muốn hủy đơn hàng')) {
            $.ajax({
                method: 'POST',
                url: url,
                data: { _token: '{{csrf_token()}}' },
                success: function(response) {
                    location.reload();                       
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //xử lý lỗi tại đây
                }
            })
        }
    })

</script>
@endsection