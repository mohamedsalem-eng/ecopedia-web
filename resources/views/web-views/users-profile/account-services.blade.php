@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('my_service_forms'))

@push('css_or_js')
    <style>
        .headerTitle {
            font-size: 24px;
            font-weight: 600;
            margin-top: 1rem;
        }

        body {
            font-family: 'Titillium Web', sans-serif
        }

        .product-qty span {
            font-size: 14px;
            color: #6A6A6A;
        }

        .font-nameA {
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0;
            font-size: 17px;
            color: #030303;
        }

        .spandHeadO {
            color: #FFFFFF !important;
            font-weight: 600 !important;
            font-size: 14px !important;

        }

        .tdBorder {
            text-align: center;
        }

        .bodytr {
            text-align: center;
        }

        .modal-footer {
            border-top: none;
        }

        .sidebarL h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}}         !important;
            transition: .2s ease-in-out;
        }

        .marl {
            margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 7px;
        }

        tr td {
            padding: 3px 5px !important;
        }

        td button {
            padding: 3px 13px !important;
        }

        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{$web_config['primary_color']}};
            }

            .sidebar_heading h1 {
                text-align: center;
                color: aliceblue;
                padding-bottom: 17px;
                font-size: 19px;
            }
        }
    </style>
@endpush

@section('content')

    <!-- Page Title-->
    <div class="container rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9 sidebar_heading">
                <h1 class="h3  mb-0 float-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} headerTitle">{{\App\CPU\translate('support_enquiry')}}</h1>
            </div>
        </div>
    </div>
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 col-md-9">
                <!-- Toolbar-->
                <!-- enquirys list-->
                <div class="card box-shadow-sm">
                    <div style="overflow: auto">
                        <table class="table">
                            <thead>
                            <tr style="background: #6b6b6b">
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO ">{{\App\CPU\translate('service')}}</span></div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"><span
                                            class="d-block spandHeadO ">{{\App\CPU\translate('submition_date')}}</span>
                                    </div>
                                </td>

                                <td class="tdBorder">
                                    <div class="py-2">
                                        <span class="d-block spandHeadO">
                                            {{\App\CPU\translate('Status')}}
                                        </span>
                                    </div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2">
                                        <span class="d-block spandHeadO"><i class="fa fa-eye"></i></span>
                                    </div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO">{{\App\CPU\translate('Action')}} </span></div>
                                </td>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($serviceEnquiries as $enquiry)
                                <tr>
                                    <td class="bodytr font-weight-bold" style="color: {{$web_config['primary_color']}}">
                                        <span class="marl">{{$enquiry->product->name}}</span>
                                    </td>
                                    <td class="bodytr">
                                        <span>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$enquiry->created_at)->format('Y-m-d h:i A')}}</span>
                                    </td>
                                    <td class="bodytr"><span class="">{{$enquiry->status}}</span></td>

                                    <td class="bodytr">
                                        <span class="">
                                            <a class="btn btn-primary btn-sm"
                                               href="{{route('service-form.index',$enquiry->id)}}">{{\App\CPU\translate('View')}}
                                            </a>
                                        </span>
                                    </td>

                                    <td class="bodytr">
                                        <a href="javascript:"
                                           onclick="Swal.fire({
                                               title: '{{\App\CPU\translate('Do you want to delete this?')}}',
                                               showDenyButton: true,
                                               showCancelButton: true,
                                               confirmButtonColor: '{{$web_config['primary_color']}}',
                                               cancelButtonColor: '{{$web_config['secondary_color']}}',
                                               confirmButtonText: `Yes`,
                                               denyButtonText: `Don't Delete`,
                                               }).then((result) => {
                                               if (result.value) {
                                               Swal.fire('Deleted!', '', 'success')
                                               location.href='{{ route('service-form.delete',['id'=>$enquiry->id])}}';
                                               } else{
                                               Swal.fire('Cancelled', '', 'info')
                                               }
                                               })"
                                           id="delete" class=" marl">
                                            <i class="czi-trash" style="font-size: 25px; color:#e81616;"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </div>
    </div>
@endsection

@push('script')
@endpush
