@extends('layouts.main')
@section('title', 'Bàn làm việc')
@section('page-title', 'Bàn làm việc')
@section('content')
    <div class="row">
        <div class="col-xl-4 mb-5 mb-xl-10">
            <div class="card card-flush h-xl-100">
                <!--begin::Heading-->
                <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px"
                    style="background-image:url('assets/media/svg/shapes/top-green.png')">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column text-white pt-15">
                        <span class="fw-bolder fs-2x mb-3">Xin chào, {{ Auth::user()->name }}</span>
                        <div class="fs-4 text-white">
                            <span class="opacity-75">Thông tin tổng quan hệ thống</span>
                        </div>
                    </h3>
                </div>
                <div class="card-body mt-n20">
                    <div class="mt-n20 position-relative">
                        <div class="row g-3 g-lg-6">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Items-->
                                <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-30px me-5 mb-8">
                                        <span class="symbol-label">
                                            <!--begin::Svg Icon | path: icons/duotune/medicine/med005.svg-->
                                            <i class="las fs-2x text-primary la-award"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <span
                                            class="text-gray-700 fw-boldest d-block fs-2qx lh-1 mb-1">{{ number_format($totalContestGoingOn, 0) }}</span>
                                        <!--end::Number-->
                                        <!--begin::Desc-->
                                        <span class="text-gray-500 fw-bold fs-6">Cuộc thi đang diễn ra</span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Items-->
                                <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-30px me-5 mb-8">
                                        <span class="symbol-label">
                                            <!--begin::Svg Icon | path: icons/duotune/finance/fin001.svg-->
                                            <i class="las fs-2x text-success la-users"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <span
                                            class="text-gray-700 fw-boldest d-block fs-2qx lh-1 mb-1">{{ $totalTeamActive }}</span>
                                        <!--end::Number-->
                                        <!--begin::Desc-->
                                        <span class="text-gray-500 fw-bold fs-6">Đội đăng ký</span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Items-->
                                <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-30px me-5 mb-8">
                                        <span class="symbol-label">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen020.svg-->
                                            <i class="las fs-2x text-success la-graduation-cap"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <span
                                            class="text-gray-700 fw-boldest d-block fs-2qx lh-1 mb-1">{{ $totalStudentAccount }}</span>
                                        <!--end::Number-->
                                        <!--begin::Desc-->
                                        <span class="text-gray-500 fw-bold fs-6">Sinh viên tham gia hệ thống</span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Stats-->
                </div>
                <!--end::Body-->
            </div>
        </div>

        <div class="col-xl-8 mb-5 mb-xl-10">
            <!--begin::Chart widget 18-->
            <div class="card card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-gray-800">Danh sách đội đăng ký thi</span>
                        <span class="text-gray-400 mt-1 fw-bold fs-6"></span>
                    </h3>
                    <!--end::Title-->
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                        <input class="form-control form-control-solid" placeholder="Pick date rage"
                            id="daterange_picker_chart" />
                        <!--end::Daterangepicker-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column justify-content-between pt-3 pb-5">
                    <!--begin::Chart-->
                    <div id="kt_charts_widget_18_chart" class="h-400px min-h-auto"></div>
                    <!--end::Chart-->
                </div>
                <!--end: Card Body-->
            </div>
            <!--end::Chart widget 18-->
        </div>
        <div class="col-xl-12 mb-5 mb-xl-10">
            <!--begin::Chart widget 18-->
            <div class="card card-flush h-xl-100">
                <!--begin::Header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder text-gray-800">Lịch trình cuộc thi </span>
                        <span class="text-gray-400 mt-1 fw-bold fs-6"></span>
                    </h3>
                    <!--end::Title-->
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                        <!--end::Daterangepicker-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body d-flex flex-column justify-content-between pt-3 pb-5">
                    <div id="kt_docs_vistimeline_style"></div>
                </div>
                <!--end: Card Body-->
            </div>
            <!--end::Chart widget 18-->
        </div>
    </div>
    <input type="hidden" id="url_chart_data" value="{{ route('dashboard.chart-competity') }}">
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/system/dashboard/chart-cuoc-thi.js') }}"></script>
    <script src="{{ asset('assets/js/system/dashboard/dashboard.js') }}"></script>
    <link href="assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
    <script src="assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
    <script>
        dashboardPage.initPage();

        var container = document.getElementById("kt_docs_vistimeline_style");

        // Generate HTML content
        const getContent = (title, img) => {
            const item = document.createElement('div');
            const name = document.createElement('div');
            const nameClasses = ['fw-bolder', 'mb-2'];
            name.classList.add(...nameClasses);
            name.innerHTML = title;

            const image = document.createElement('img');
            image.setAttribute('src', img);

            const symbol = document.createElement('div');
            const symbolClasses = ['symbol', 'symbol-circle', 'symbol-30'];
            symbol.classList.add(...symbolClasses);
            symbol.appendChild(image);

            item.appendChild(name);
            item.appendChild(symbol);

            return item;
        }

        // note that months are zero-based in the JavaScript Date object
        var items = new vis.DataSet(@json($contests));

        var options = {
            editable: false,
            margin: {
                item: 40,
                axis: 40,
            },
        };

        var timeline = new vis.Timeline(container, items, options);
    </script>

@endsection
