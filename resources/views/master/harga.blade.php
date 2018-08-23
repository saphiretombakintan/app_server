<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>Smile In Properti</title>
    <meta name="description" content="Scrollable datatables examples">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!--end::Web font -->

    <!--begin::Page Vendors Styles -->
    <link href="{{ URL::asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--RTL version:<link href="../../../assets/vendors/custom/datatables/datatables.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

    <!--end::Page Vendors Styles -->

    <!--begin::Base Styles -->
    <link href="{{ URL::asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--RTL version:<link href="../../../assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
    <link href="{{ URL::asset('assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--RTL version:<link href="../../../assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

    <!--end::Base Styles -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/icon-biru.png') }}" />

</head>
<!-- begin::Body -->
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-aside-left--minimize m-brand--minimize m-footer--push m-aside--offcanvas-default">

<div class="m-grid m-grid--hor m-grid--root m-page">
    <!-- ini awal header -->
    @extends('layouts.header')

<!-- ini awal body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
    @extends('layouts.menu')
    <!-- END: Left Aside -->
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <!-- BEGIN: Subheader -->
            <div class="m-subheader ">
                <div class="d-flex align-items-center">
                    <div class="mr-auto">
                        <h3 class="m-subheader__title m-subheader__title--separator">DATA HARGA UNIT</h3>
                        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                            <li class="m-nav__item m-nav__item--home">
                                <a href="#" class="m-nav__link m-nav__link--icon">
                                    <i class="m-nav__link-icon la la-home"></i>
                                </a>
                            </li>
                            <li class="m-nav__separator">-</li>
                            <li class="m-nav__item">
                                <a href="" class="m-nav__link">
                                    <span class="m-nav__link-text">Master</span>
                                </a>
                            </li>
                            <li class="m-nav__separator">-</li>
                            <li class="m-nav__item">
                                <a href="" class="m-nav__link">
                                    <span class="m-nav__link-text">Harga</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- END: Subheader -->
            <div class="m-content">

                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Master Harga Unit
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">

                                <li class="m-portlet__nav-item"></li>
                                <li class="m-portlet__nav-item">
                                    <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                                        <a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                            <i class="la la-ellipsis-h m--font-brand"></i>
                                        </a>
                                        <div class="m-dropdown__wrapper">
                                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                      <!--begin: Datatable -->
                      <div id="table-1"></div>
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                          <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipe Unit</th>
                                <th>Kode Unit</th>
                                <th>Pembayaran</th>
                                <th>Harga</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($price as $data)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $data->type_unit }}</td>
                                <td>{{ $data->code_unit }}</td>
                                @foreach($code_p as $code)
                                  @if($data->type_unit == $code->type_unit && $data->code_payment == $code->code_payment)
                                  <td>{{ $code->name_payment }}</td>
                                  @endif
                                @endforeach
                                <td>Rp. {{ number_format($data->price) }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                          <tfoot>
                          <tr>
                            <th>#</th>
                            <th>Tipe Unit</th>
                            <th>Kode Unit</th>
                            <th>Pembayaran</th>
                            <th>Harga</th>
                          </tr>
                          </tfoot>
                      </table>
                    </div>
                </div>

                <!-- END EXAMPLE TABLE PORTLET-->
            </div>

        </div>
    </div>
</div>
<!-- begin::Quick Nav -->

<!--begin::Base Scripts -->
<script src="{{ URL::asset('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>

<!--end::Base Scripts -->

<!--begin::Page Vendors Scripts -->
<script src="{{ URL::asset('assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>

<!--end::Page Vendors Scripts -->

<!--begin::Page Resources -->
<script type="text/javascript">
$(document).ready( function () {
    $('#m_table_1').DataTable({
      columnDefs: [
        {
          targets: 3,
          render: function(s) {
            if (s === 'CASH') {
              return '<span class="m-badge m-badge--danger m-badge--wide">CASH</span>'
            } else if (s === 'KPR') {
              return '<span class="m-badge m-badge--primary m-badge--wide">KPR</span>'
            } else if (s === 'BERTAHAP 12x') {
              return '<span class="m-badge m-badge--success m-badge--wide">BERTAHAP 12x</span>'
            } else if (s === 'BERTAHAP 24x') {
              return '<span class="m-badge m-badge--warning m-badge--wide">BERTAHAP 24x</span>'
            } else if (s === 'BERTAHAP 36x') {
              return '<span class="m-badge m-badge--info m-badge--wide">BERTAHAP 36x</span>'
            }
          }
        }
      ],
      footerCallback: function(t, e) {
          var o = this.api(),
              l = function(t) {
                  return "string" == typeof t ? 1 * t.replace(/[\Rp.,]/g, "") : "number" == typeof t ? t : 0
              },
              u = o.column(4).data().reduce(function(t, e) {
                  return l(t) + l(e)
              }, 0),
              i = o.column(4, {
                  page: "current"
              }).data().reduce(function(t, e) {
                  return l(t) + l(e)
              }, 0);
          $(o.column(4).footer()).html("Rp. " + mUtil.numberString(i.toFixed(0)) + "<br/>(Grand Total Rp. " + mUtil.numberString(u.toFixed(0)) + ")")
      }
    });
});
</script>

<!--end::Page Resources -->
</body>

<!-- end::Body -->
</html>
