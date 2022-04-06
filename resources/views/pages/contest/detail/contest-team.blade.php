@extends('layouts.main')
@section('title', 'Chi tiết cuộc thi')
@section('page-title', 'Chi tiết cuộc thi')
@section('content')
    <div class="row">
        <div class="col-lg-2">
            <div class="card card-flush ">
                <img src="{{ $datas->img == null
                    ? 'https://skillz4kidzmartialarts.com/wp-content/uploads/2017/04/default-image.jpg'
                    : $datas->img }}"
                    alt="">

            </div>
            <ul class="nav flex-row flex-md-column nav-custom  border-0 fs-4 fw-bold mb-n2 mt-3">

                <li class="nav-item">
                    <a class="tabbar_detail_contest nav-link text-active-primary" href="javascript:void()">
                        Chi tiết
                    </a>
                </li>

                <li class="nav-item">
                    <a class="tabbar_round_contest nav-link text-active-primary " href="javascript:void()">
                        Vòng thi</a>
                </li>

                <li class="nav-item">
                    <a class="tabbar_teams_contest nav-link text-active-primary " href="javascript:void()">Đội
                        thi</a>
                </li>
            </ul>
        </div>

        <div class="col-lg-10">
            <div class="card card-flush">
                <h1 class="text-center pt-3">Danh sách đội thi</h1>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped gy-7 gs-7">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th>Tên</th>
                                            <th>Ảnh</th>
                                            <th>Thành viên</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datas->teams as $team)
                                            <tr>
                                                <td>{{ $team->name }}</td>
                                                <td>
                                                    <img class="image-input-wrapper w-100px h-100px"
                                                        src="{{ (\Storage::disk('google')->has($team->image) ? 111 : null) == null
                                                            ? 'https://skillz4kidzmartialarts.com/wp-content/uploads/2017/04/default-image.jpg'
                                                            : \Storage::disk('google')->url($team->image) }}"
                                                        alt="">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_{{ $team->id }}">
                                                        Xem
                                                    </button>

                                                    <div class="modal fade" tabindex="-1"
                                                        id="kt_modal_{{ $team->id }}">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Thành viên</h5>

                                                                    <!--begin::Close-->
                                                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                                                        data-bs-dismiss="modal" aria-label="Close">
                                                                        <span class="svg-icon svg-icon-2x"></span>
                                                                    </div>
                                                                    <!--end::Close-->
                                                                </div>

                                                                <div class="modal-body">
                                                                    <table
                                                                        class="table table-striped table-responsive gy-7 gs-7">
                                                                        <thead>
                                                                            <tr
                                                                                class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                                                                <th>Tên</th>
                                                                                <th>Email</th>

                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($team->members as $user)
                                                                                <tr>
                                                                                    <td>{{ $user->name }}</td>
                                                                                    <td>{{ $user->email }}</td>

                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light"
                                                                        data-bs-dismiss="modal">Close</button>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.delete.teams', $team->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button style=" background: none ; border: none ; list-style : none"
                                                            type="submit">
                                                            <span role="button"
                                                                class="svg-icon svg-icon-danger svg-icon-2x">
                                                                <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo2/dist/../src/media/svg/icons/Home/Trash.svg--><svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                                                            fill="#000000" fill-rule="nonzero" />
                                                                        <path
                                                                            d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                                            fill="#000000" opacity="0.3" />
                                                                    </g>
                                                                </svg>
                                                                <!--end::Svg Icon-->
                                                            </span>
                                                            Xóa bỏ
                                                        </button>
                                                    </form>
                                                    {{-- {{ route('admin.delete.teams', $valueTeam->id) }} --}}
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        const URL_ROUTE = `{{ route('admin.contest.show', ['id' => $datas->id]) }}`
    </script>
    <script src="assets/js/system/contest/detail-contest.js"></script>
@endsection
